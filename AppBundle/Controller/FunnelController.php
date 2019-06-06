<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\EmailList;
use AppBundle\Entity\EmailListUserLink;
use AppBundle\Entity\funnelEmailListLink;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Funnel;
use AppBundle\Entity\FunnelUserLink;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FunnelController extends Controller
{
    /**
     * @Route("/funnels", name="funnels")
     */
    public function funnelsAction()
    {
        $user = $this->getUser();

        //Get Funnels for User
        $funnelUserLinks = $this->getDoctrine()->getRepository('AppBundle:FunnelUserLink')->getFunnelsForUser($user);
        $emailListUserLinks = $this->getDoctrine()->getRepository('AppBundle:EmailListUserLink')->getEmailListsForUser($user);


        return $this->render('Funnels/funnels.html.twig', array(
            'user' => $user,
            'funnelUserLinks' => $funnelUserLinks,
            'emailListUserLinks' => $emailListUserLinks
        ));
    }

    /**
     * @Route("/funnel_builder/{funnelUserLinkID}", name="funnel_builder")
     */
    public function funnelBuilderAction($funnelUserLinkID)
    {
        $user = $this->getUser();

        //Get Funnel for User
        $funnelUserLink = $this->getDoctrine()->getRepository('AppBundle:FunnelUserLink')->find($funnelUserLinkID);

        return $this->render('Funnels/funnel_builder.html.twig', array(
            'user' => $user,
            'funnelUserLink' => $funnelUserLink,
        ));
    }




    /**
     * USED WITH AJAX
     * --------------
     * Deletes a Funnel For User
     *
     * @Route("/ajax/funnel/delete")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxDeleteFunnelAction(Request $request) {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get Current User
            $user = $this->getUser();

            //Get Request Params
            $funnelUserLinkID = $request->request->get('funnel_user_link_ID');


            //Find FunnelUserLinks
            $funnelUserLink = $this->getDoctrine()->getRepository('AppBundle:FunnelUserLink')->find($funnelUserLinkID);

            if (!$funnelUserLink) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Funnel User Link Not Found!"
                ));
            }

            //Delete Entity and Persist Changes
            $funnelUserLink->setArchived(FunnelUserLink::$ARCHIVED_YES);
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($funnelUserLink);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Funnel User Link Successfully Archived'
            ));
        }
    }

    /**
     * USED WITH AJAX
     * --------------
     * Adds a new Funnel
     *
     * @Route("/ajax/funnel/create")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAddFunnelAction(Request $request) {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get user
            $user = $this->getUser();

            //Creating a new Funnel and getting necessary fields from Modal
            $newFunnel = new Funnel();
            $funnelName = $request->request->get('name');
            $newFunnel->setName($funnelName);
            $newFunnel->setDescription($request->request->get('description'));
            $newFunnel->setActive(Funnel::$ACTIVE_YES);
            $newFunnel->setHook($request->request->get('hook_type'));


            //Getting specified emailList
            $emailListID = $request->request->get('email_list_id');

            //If User has no Email Lists, create an email list
            if ($emailListID == '0') {

                $emailList = new EmailList();
                $emailList->setName($funnelName.' list');
                $emailList->setDescription('Auto-generated description for '.$emailList->getName());

                //Persist email list and email list user link to database
                $emailListUserLink = new EmailListUserLink($user, $emailList);
                $dbManager = $this->getDoctrine()->getManager();
                $dbManager->persist($emailList);
                $dbManager->flush();

                $dbManager->persist($emailListUserLink);
                $dbManager->flush();


            }
            else {
                $emailList = $this->getDoctrine()->getRepository('AppBundle:EmailList')->find($emailListID);
            }

            //Creating Links for the new Funnel
            $newFunnelEmailListLink = new funnelEmailListLink($newFunnel, $emailList);
            $newFunnelUserLink = new FunnelUserLink($newFunnel, $user);

            //Persisting Funnel/Links to database

            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($newFunnel);
            $dbManager->flush();


            $dbManager->persist($newFunnelEmailListLink);
            $dbManager->flush();


            $dbManager->persist($newFunnelUserLink);
            $dbManager->flush();
            $dbManager->clear();



            return new JsonResponse(array(
                'success' => true,
                'message' => 'Funnel Successfully Created'
            ));
        }
    }

    /**
     * USED WITH AJAX
     * --------------
     * Activates a Funnel For User
     *
     * @Route("/ajax/funnel/activate_funnel")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxActivateFunnelAction(Request $request) {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get Request Params
            $funnelID = $request->request->get('funnel_id');


            //Find FunnelUserLinks
            $funnel = $this->getDoctrine()->getRepository('AppBundle:Funnel')->find($funnelID);


            if (!$funnel) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Funnel Not Found!"
                ));
            }
            if($funnel->getActive() == 1) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Funnel already active!'
                ));
            }

            //Delete Entity and Persist Changes
            $funnel->setActive(1);
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($funnel);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Funnel activated!'
            ));
        }
    }

    /**
     * USED WITH AJAX
     * --------------
     * Deactivates a Funnel For User
     *
     * @Route("/ajax/funnel/deactivate_funnel")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxDeactivateFunnelAction(Request $request) {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get Request Params
            $funnelID = $request->request->get('funnel_id');


            //Find FunnelUserLinks
            $funnel = $this->getDoctrine()->getRepository('AppBundle:Funnel')->find($funnelID);


            if (!$funnel) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Funnel Not Found!"
                ));
            }
            if($funnel->getActive() == 0) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Funnel already inactive!'
                ));
            }

            //Delete Entity and Persist Changes
            $funnel->setActive(0);

            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($funnel);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Funnel deactivated!'
            ));
        }
    }




}
