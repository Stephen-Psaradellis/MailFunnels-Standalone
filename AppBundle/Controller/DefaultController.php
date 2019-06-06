<?php

namespace AppBundle\Controller;

use AppBundle\Job\SingleEmailJob;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/dashboard", name="homepage")
     */
    public function dashboardAction()
    {
        //Get Current User
        $user = $this->getUser();

        //Get Funnels For User
        $funnelLinks = $this->getDoctrine()->getRepository('AppBundle:FunnelUserLink')->getFunnelsForUser($user);
        $emailListLinks = $this->getDoctrine()->getRepository('AppBundle:EmailListUserLink')->getEmailListsForUser($user);
        $templateLinks = $this->getDoctrine()->getRepository('AppBundle:EmailTemplateUserLink')->getTemplatesForUser($user);
        $subscriberLinks = $this->getDoctrine()->getRepository('AppBundle:SubscriberUserLink')->getAllSubscriberLinksForUser($user);

        //Render the Dashboard Template
        return $this->render('home/dashboard.html.twig', array(
            'user'               => $user,
            'funnelLinks'        => $funnelLinks,
            'emailListLinks'     => $emailListLinks,
            'templateLinks'      => $templateLinks,
            'subscriberLinks'    => $subscriberLinks
        ));
    }

    /**
     *
     * @Route("/", name="landing_page")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function landingPageAction() {
        return $this->render(':Info:landing.html.twig', array(

        ));
    }

    /**
     *
     * @Route("/send_email", name="send_email")
     */
    public function testSendEmail() {
        $emailService = $this->get('mailfunnels.email.single');
        $emailService->singleEmailAction();
        return $this->redirectToRoute('homepage');
    }



}
