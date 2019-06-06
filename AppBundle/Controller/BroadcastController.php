<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Broadcast;
use AppBundle\Entity\BroadcastEmailListLink;
use AppBundle\Entity\BroadcastEmailTemplateLink;
use AppBundle\Entity\BroadcastUserLink;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BroadcastController extends Controller
{
    /**
     * @Route("/broadcasts", name="broadcasts")
     */
    public function broadcastsAction()
    {
        //Get Current User
        $user = $this->getUser();

        //Get all Links for User
        $broadcastUserLinks = $this->getDoctrine()->getRepository('AppBundle:BroadcastUserLink')->getBroadcastsForUser($user);
        $emailTemplateUserLinks = $this->getDoctrine()->getRepository('AppBundle:EmailTemplateUserLink')->getTemplatesForUser($user);
        $emailListUserLinks = $this->getDoctrine()->getRepository('AppBundle:EmailListUserLink')->getEmailListsForUser($user);


        return $this->render("Broadcasts/broadcasts.html.twig", array(
            'broadcastUserLinks' => $broadcastUserLinks,
            'emailTemplateUserLinks' => $emailTemplateUserLinks,
            'emailListUserLinks' => $emailListUserLinks,
        ));
    }


    /**
     * USED WITH AJAX
     * --------------
     * Adds a new Broadcast
     *
     * @Route("/ajax/broadcast/add_broadcast")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxBroadcastAction(Request $request)
    {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get user
            $user = $this->getUser();

            //Get emailList and emailTemplate ID's from Request
            $emailListID = $request->request->get('email_list_ID');
            $emailTemplateID = $request->request->get('email_template_ID');

            //Getting associated emailList/emailTemplate from Repository
            $emailList = $this->getDoctrine()->getRepository('AppBundle:EmailList')->find($emailListID);
            $emailTemplate = $this->getDoctrine()->getRepository('AppBundle:EmailTemplate')->find($emailTemplateID);

            //Creating a new Broadcast and setting fields to the values from the modal
            $newBroadcast = new Broadcast();
            $newBroadcast->setDescription($request->request->get('description'));
            $newBroadcast->setName($request->request->get('name'));

            //Creating new links
            $newBroadcastUserLink = new BroadcastUserLink($newBroadcast, $user);
            $broadcastEmailListLink = new BroadcastEmailListLink($newBroadcast, $emailList);
            $broadcastEmailTemplateLink = new BroadcastEmailTemplateLink($newBroadcast, $emailTemplate);

            //Persisting Broadcast and Associated Links to Database
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($newBroadcast);
            $dbManager->flush();

            $dbManager->persist($newBroadcastUserLink);
            $dbManager->flush();

            $dbManager->persist($broadcastEmailListLink);
            $dbManager->flush();

            $dbManager->persist($broadcastEmailTemplateLink);
            $dbManager->flush();
            $dbManager->clear();

            //Sending Broadcast

            $this->get('mailfunnels.broadcast.service')->sendBroadcast($newBroadcast->getId(), $user->getId());

//            $this->get('swiftmailer.mailer.transport.expertcoder_swift_mailer.sendBroadcast_grid')

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Broadcast and associated Links Created!'
            ));
        }
    }

    /**
     * USED WITH AJAX
     * --------------
     * Archives a broadcast and the associated links
     *
     * @Route("/ajax/broadcast/archive_broadcast")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxArchiveBroadcastAction(Request $request)
    {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get broadcastUserLinkID from Request
            $broadcastUserLinkID = $request->request->get('broadcast_user_link_ID');

            //Get associated Broadcast User Link
            $broadcastUserLink = $this->getDoctrine()->getRepository('AppBundle:BroadcastUserLink')->find($broadcastUserLinkID);

            if (!$broadcastUserLink) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Broadcast User Link Not Found!"
                ));
            }

            //Get associated Broadcast
            $broadcast = $broadcastUserLink->getBroadcast();

            if (!$broadcast) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Broadcast Not Found!"
                ));
            }

            //Get associated Broadcast Email List Link ID
            $broadcastEmailListLinkID = $this->getDoctrine()->getRepository('AppBundle:BroadcastEmailListLink')->findBroadcastEmailListLinkIDByBroadcast($broadcast);
            //Get Broadcast Email List Link
            $broadcastEmailListLink = $this->getDoctrine()->getRepository('AppBundle:BroadcastEmailListLink')->find($broadcastEmailListLinkID);

            if (!$broadcastEmailListLink) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Broadcast Email List Not Found!"
                ));
            }

            //Get associated Broadcast Email Template Link ID
            $broadcastEmailTemplateLinkID = $this->getDoctrine()->getRepository('AppBundle:BroadcastEmailTemplateLink')->findBroadcastEmailTemplateLinkByBroadcast($broadcast);
            //Get Broadcast Email Template Link
            $broadcastEmailTemplateLink = $this->getDoctrine()->getRepository('AppBundle:BroadcastEmailTemplateLink')->find($broadcastEmailTemplateLinkID);

            if (!$broadcastEmailTemplateLink) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Broadcast Email Template Link Not Found!"
                ));
            }

            $broadcastUserLink->setArchived(BroadcastUserLink::$ARCHIVED_YES);
            $broadcastEmailListLink->setArchived(BroadcastEmailListLink::$ARCHIVED_YES);
            $broadcastEmailTemplateLink->setArchived(BroadcastEmailTemplateLink::$ARCHIVED_YES);

            //Persisting Broadcast and Associated Links to Database
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($broadcastUserLink);
            $dbManager->flush();

            $dbManager->persist($broadcastEmailTemplateLink);
            $dbManager->flush();

            $dbManager->persist($broadcastEmailListLink);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Broadcast and associated Links Archived!'
            ));
        }
    }


    /**
     * @Route("/broadcast/{broadcastUserLinkID}", name="view_broadcast")
     * @param $broadcastUserLinkID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewBroadcastAction($broadcastUserLinkID)
    {
        //Get associated Broadcast User Link
        $broadcastUserLink = $this->getDoctrine()->getRepository('AppBundle:BroadcastUserLink')->find($broadcastUserLinkID);
        //Render the View Email List Template
        return $this->render("Broadcasts/view_broadcast.html.twig", array(
            'broadcastUserLink' => $broadcastUserLink,
        ));
    }

    /**
     * USED WITH AJAX
     * --------------
     * Renders JSON array of broadcasts for user
     *
     * @Route("/api_v1/broadcasts/get")
     * @param Request $request
     * @return JsonResponse
     */
    public function getBroadcastsJSON(Request $request) {

        //If Request is an AJAX Request
        if ($request->isXmlHttpRequest()) {

            //Get Current User
            $user = $this->getUser();

            //Get All BroadcastLinks
            $broadcastLinks = $this->getDoctrine()->getRepository('AppBundle:BroadcastUserLink')->getBroadcastsForUser($user);

            //Create new array that will hold the JSON response of body
            $broadcastJSON = array();

            foreach ($broadcastLinks as $link) {
                $broadcast = $link->getBroadcast();
                $temp = array(
                    'linkID'            => $link->getId(),
                    'info'              => $broadcast->getJSON(),
                    'subscribers'       => 0,
                    'emails-sent'       => 0,
                    'emails-clicked'    => 0,
                    'emails-opened'     => 0,
                );

                array_push($broadcastJSON, $temp);

            }
            return new JsonResponse($broadcastJSON);

        }

    }

    public function sendBroadcast() {




    }

}
