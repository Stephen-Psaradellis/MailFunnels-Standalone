<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EmailTemplate;
use AppBundle\Entity\EmailTemplateUserLink;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EmailTemplateController extends Controller
{
    /**
     * @Route("/email_templates", name="email_templates")
     */
    public function emailTemplatesAction()
    {
        //Get Current User
        $user = $this->getUser();

        //Get All Template Links
        $templateLinks = $this->getDoctrine()->getRepository('AppBundle:EmailTemplateUserLink')->getTemplatesForUser($user);


        return $this->render(':EmailTemplates:email_templates.html.twig', array(
            'user' => $user,
            'templateLinks' => $templateLinks
        ));
    }

    /**
     * @Route("/template_builder/{templateId}", name="template_builder")
     */
    public function templateBuilderAction($templateId)
    {
        //Get Current User
        $user = $this->getUser();

        //Get All Template Links
        $template = $this->getDoctrine()->getRepository('AppBundle:EmailTemplate')->find($templateId);


        return $this->render(':EmailTemplates:template_builder.html.twig', array(
            'user' => $user,
            'template' => $template
        ));
    }




    /**
     * USED WITH AJAX
     * --------------
     * Deletes an Email Template For User
     *
     * @Route("/ajax/template/delete")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxDeleteTemplateAction(Request $request) {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get Current User
            $user = $this->getUser();

            //Get Request Params
            $templateLinkID = $request->request->get('template_link_id');

            //Find Template
            $emailTemplateLink = $this->getDoctrine()->getRepository('AppBundle:EmailTemplateUserLink')->find($templateLinkID);
            if (!$emailTemplateLink) {
                return new JsonResponse(array(
                   'success' => false,
                   'message' => "Template Link Not Found!"
                ));
            }

            //Delete Entity and Persist Changes
            $emailTemplateLink->setArchived(EmailTemplateUserLink::$ARCHIVED_YES);
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($emailTemplateLink);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Template Successfully Archived'
            ));
        }
    }


    /**
     * USED WITH AJAX
     * --------------
     * Saves an Email Template For User
     *
     * @Route("/ajax/template/save_email_template")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxSaveTemplateAction(Request $request) {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get Request Params
            $templateLinkID = $request->request->get('id');

            //Find Template
            $emailTemplate = $this->getDoctrine()->getRepository('AppBundle:EmailTemplate')->find($templateLinkID);
            if (!$emailTemplate) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Template Link Not Found!"
                ));
            }

            //Change Fields and Persist Changes
            $emailTemplate->setHtml($request->request->get('html'));
            $emailTemplate->setIsDynamic($request->request->get('dynamic'));

            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($emailTemplate);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Template Successfully Saved'
            ));
        }
    }

    /**
     * USED WITH AJAX
     * --------------
     * Clones an Email Template For User
     *
     * @Route("/ajax/template/clone_template")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxCloneTemplateAction(Request $request) {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get user
            $user = $this->getUser();

            //Get Request Params
            $templateLinkID = $request->request->get('template_link_id');

            //Find Template
            $emailTemplate = $this->getDoctrine()->getRepository('AppBundle:EmailTemplate')->find($templateLinkID);

            if (!$emailTemplate) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Template Link Not Found!"
                ));
            }

            //Calling built-in clone function
            $templateClone = $emailTemplate->cloneTemplate();

            //Setting fields to the values given by the Clone Modal
            $templateClone->setName($request->request->get('template_name'));
            $templateClone->setDescription($request->request->get('template_description'));
            $templateClone->setEmailSubject($request->request->get('template_subject'));

            //Creating a new EmailTemplateUserLink for the clone
            $templateUserLinkClone = new EmailTemplateUserLink($user, $templateClone);

            //Persisting to clone template/link to database
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($templateClone);
            $dbManager->flush();

            $dbManager->persist($templateUserLinkClone);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Template Successfully Saved'
            ));
        }
    }



    /**
     * USED WITH AJAX
     * --------------
     * Adds a new Email Template
     *
     * @Route("/ajax/template/add_email_template")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAddEmailTemplateAction(Request $request) {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get user
            $user = $this->getUser();

            //Creating a template and setting the fields of the new template to the values taken from the Add New Template Modal
            $newTemplate = new EmailTemplate();
            $newTemplate->setEmailSubject($request->request->get('email_subject'));
            $newTemplate->setName($request->request->get('name'));
            $newTemplate->setDescription($request->request->get('description'));


            //Creating a EmailTemplateUserLink for the new template
            $newTemplateUserLink = new EmailTemplateUserLink($user, $newTemplate);

            //Persisting to clone template/link to database
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($newTemplate);
            $dbManager->flush();

            $dbManager->persist($newTemplateUserLink);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Template Successfully Created'
            ));
        }
    }

    /**
     * USED WITH AJAX
     * --------------
     * Sends a test email to the user
     *
     * @Route("/ajax/template/send_test_email")
     * @param Request $request
     * @return JsonResponse
     */

    public function testEmailAction(Request $request) {
        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get user
            $user = $this->getUser();
            $emailAddress = $user->getEmail();


            //Creating a template and setting the fields of the new template to the values taken from the Add New Template Modal
            $emailTemplateID = $request->request->get('email_template_id');
            $emailTemplate = $this->getDoctrine()->getRepository('AppBundle:EmailTemplate')->find($emailTemplateID);

            if (!$emailTemplate) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Email Template Not Found!"
                ));
            }

            $this->get('mailfunnels.email.single')->testEmailAction($emailTemplate,$emailAddress);

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Template Successfully Created'
            ));
        }
    }


    /**
     * USED WITH AJAX
     * --------------
     * Sets a user's default email template html
     *
     * @Route("/ajax/template/set_default_html")
     * @param Request $request
     * @return JsonResponse
     */
    public function setDefaultHtml(Request $request) {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {

            //Get user
            $user = $this->getUser();

            //Get html
            $html = $request->request->get('html');

            //Set html
            $user->setDefaultHtml($html);

            //Persist changes to database
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($user);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Default Html Successfully Set'
            ));
        }
    }

    /**
     * USED WITH AJAX
     * --------------
     * Edits the Email Template Information
     *
     * @Route("/ajax/template/update_template_info")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateEmailTemplateInfo(Request $request) {

        //If request is AJAX call
        if ($request->isXmlHttpRequest()) {
            //Get templateId
            $templateID = $request->request->get('template_id');
            $template = $this->getDoctrine()->getRepository('AppBundle:EmailTemplate')->find($templateID);

            if (!$template) {
                return new JsonResponse(array(
                    'success' => false,
                    'message' => "Template Not Found!"
                ));
            }

            //Update fields of the template from the modal info
            $template->setName($request->request->get('name'));
            $template->setDescription($request->request->get('description'));
            $template->setEmailSubject($request->request->get('email_subject'));

            //Persist changes to database
            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($template);
            $dbManager->flush();
            $dbManager->clear();

            return new JsonResponse(array(
                'success' => true,
                'message' => 'Template Info Successfully Updated!'
            ));
        }
    }






}
