<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EmailList;
use AppBundle\Entity\EmailListUserLink;
use AppBundle\Entity\Subscriber;
use AppBundle\Entity\SubscriberEmailListLink;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Tests\Fixtures\includes\HotPath\P1;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EmailListController extends Controller
{
    /**
     * @Route("/email_lists", name="email_lists")
     */
    public function emailListsAction()
    {
        //Get Current User
        $user = $this->getUser();

        //Get all emailLists for User
        $emailListUserLinks = $this->getDoctrine()->getRepository('AppBundle:EmailListUserLink')->getEmailListsForUser($user);

        //Render the Email Lists Template
        return $this->render("emailLists/email_list.html.twig", array(
            'emailListUserLinks' => $emailListUserLinks,
        ));
    }

    /**
     * USED WITH AJAX
     * --------------
     * Adds a new Email List
     *
     * @Route("/ajax/email_list/add")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAddEmailListAction(Request $request)
    {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get user
            $user = $this->getUser();

            //Creating an email list and setting the fields of the new email list to the values taken from the Add New Email List Modal
            $newEmailList = new EmailList();
            $newEmailList->setName($request->request->get('name'));
            $newEmailList->setDescription($request->request->get('description'));


            //Creating a EmailTemplateUserLink for the new template
            $newEmailListUserLink = new EmailListUserLink($user, $newEmailList);

            //Persisting to clone template/link to database
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($newEmailList);
            $dbManager->flush();

            $dbManager->persist($newEmailListUserLink);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Email List Successfully Created'
            ));
        }
    }


    /**
     * USED WITH AJAX
     * --------------
     * Deletes an Email List For User
     *
     * @Route("/ajax/email_list/delete")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxDeleteEmailListAction(Request $request)
    {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get Request Params
            $emailListUserLinkID = $request->request->get('email_list_user_link_id');

            //Find Email List User Link
            $emailListUserLink = $this->getDoctrine()->getRepository('AppBundle:EmailListUserLink')->find($emailListUserLinkID);

            if (!$emailListUserLink) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Email List User Link Not Found!"
                ));
            }

            //Delete Entity and Persist Changes
            $emailListUserLink->setArchived(EmailListUserLink::$ARCHIVED_YES);
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($emailListUserLink);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Email List Successfully Archived'
            ));
        }
    }

    /**
     *
     * @Route("/list/{emailListID}", name="list")
     * @param $emailListID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewListAction($emailListID)
    {

        //Get associated Email List
        $emailList = $this->getDoctrine()->getRepository('AppBundle:EmailList')->find($emailListID);

        $emailListUserLink = $this->getDoctrine()->getRepository('AppBundle:EmailListUserLink')->getEmailListUserLinkByEmailList($emailList);

        //Get all Subscribers for Email List
        $subscriberEmailListLinks = $this->getDoctrine()->getRepository('AppBundle:SubscriberEmailListLink')->getSubscriberEmailListLinksByEmailList($emailList);

        //Render the View Email List Template
        return $this->render("emailLists/view_email_list.html.twig", array(
            'subscriberEmailListLinks' => $subscriberEmailListLinks,
            'emailList' => $emailList,
            'emailListUserLink' =>$emailListUserLink
        ));
    }

    /**
     * @Route("/view_email_list/{emailListUserLinkID}", name="view_email_list")
     * @param $emailListUserLinkID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewEmailListAction($emailListUserLinkID)
    {
        //Get associated Email List User Link
        $emailListUserLink = $this->getDoctrine()->getRepository('AppBundle:EmailListUserLink')->find($emailListUserLinkID);
        //Get associated Email List
        $emailList = $emailListUserLink->getEmailList();
        //Get associated Email List ID
        $emailListID = $emailList->getId();
        //Get all Subscribers for Email List
        $subscriberEmailListLinks = $this->getDoctrine()->getRepository('AppBundle:SubscriberEmailListLink')->getSubscriberEmailListLinksByEmailList($emailList);

        //Render the View Email List Template
        return $this->render("emailLists/view_email_list.html.twig", array(
            'subscriberEmailListLinks' => $subscriberEmailListLinks,
            'emailListUserLink' => $emailListUserLink
        ));
    }

    /**
     * USED WITH AJAX
     * --------------
     * Adds a Subscriber to an Email List
     *
     * @Route("/ajax/email_list/add_subscriber")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAddSubscriberToEmailList(Request $request)
    {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            $emailListUserLinkID = $request->request->get('email_list_user_link_ID');
            $emailListUserLink = $this->getDoctrine()->getRepository('AppBundle:EmailListUserLink')->find($emailListUserLinkID);
            $emailList = $emailListUserLink->getEmailList();

            $newSubscriber = new Subscriber();
            $newSubscriber->setFirstName($request->request->get('first_name'));
            $newSubscriber->setLastName($request->request->get('last_name'));
            $newSubscriber->setEmail($request->request->get('email_address'));
            $newSubscriber->setRefType(Subscriber::$REF_TYPE_MANUAL);

            $newSubscriberEmailListLink = new SubscriberEmailListLink($newSubscriber, $emailList);

            //Persisting new Subscriber and associated links to the database
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($newSubscriber);
            $dbManager->flush();

            $dbManager->persist($newSubscriberEmailListLink);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Email List Subscriber Link Successfully Created!'
            ));
        }
    }

    /**
     * USED WITH AJAX
     * --------------
     * CSV Import Subscribers
     *
     * @Route("/ajax/email_list/import_csv")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxImportCSV(Request $request)
    {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            $subscribers = $request->request->get('subscribers');
            $emailListUserLinkID = $request->request->get('email_list_user_link_id');
            $emailListUserLink = $this->getDoctrine()->getRepository('AppBundle:EmailListUserLink')->find($emailListUserLinkID);
            $emailList = $emailListUserLink->getEmailList();
            $dbManager = $this->getDoctrine()->getManager();

            if(!$subscribers) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => 'Subscribers not found!'
                ));
            }

            foreach($subscribers as $subscriber) {
                $newSubscriber = new Subscriber();
                $newSubscriber->setFirstName($subscriber['first_name']);
                $newSubscriber->setLastName($subscriber['last_name']);
                $newSubscriber->setRefType(Subscriber::$REF_TYPE_MANUAL);
                $email = $subscriber['email'];

                if($email){
                    $newSubscriber->setEmail($email);
                    $newSubscriberEmailListLink = new SubscriberEmailListLink($newSubscriber,$emailList);

                    $dbManager->persist($newSubscriber);
                    $dbManager->flush();

                    $dbManager->persist($newSubscriberEmailListLink);
                    $dbManager->flush();


                }

            }
            $dbManager->clear();


            return new JsonResponse(array(
                'success' => true,
                'message' => 'Email List Successfully Archived'
            ));
        }
    }

    /**
     * USED WITH AJAX
     * --------------
     * Renders JSON array of email lists for user
     *
     * @Route("/api_v1/lists/get")
     * @param Request $request
     * @return JsonResponse
     */
    public function getEmailListJSON(Request $request) {

        //If Request is an AJAX Request
        if ($request->isXmlHttpRequest()) {

            //Get Current User
            $user = $this->getUser();

            //Get All EmailListLinks
            $listLinks = $this->getDoctrine()->getRepository('AppBundle:EmailListUserLink')->getEmailListsForUser($user);

            //Create new array that will hold the JSON response of body
            $listsJSON = array();

            foreach($listLinks as $link) {
                array_push($listsJSON, $link->getEmailList()->getJSON());
            }

            return new JsonResponse($listsJSON);

        }

    }



}


