<?php
namespace AppBundle\Job;
use AppBundle\Entity\EmailTemplate;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use MZ\PostmarkBundle\Postmark\Message;
use Twig_Environment;

/**
 * Created by PhpStorm.
 * User: Stephen
 * Date: 6/19/2018
 * Time: 1:18 PM
 */
class SingleEmailJob
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Message
     */
    private $message;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var Logger
     *
     */
    private $logger;



    /**
     * Constructor
     * -----------
     *
     * @param EntityManager $em
     * @param Twig_Environment $twig
     */

    public function __construct(EntityManager $em, Twig_Environment $twig, Message $message, Logger $logger) {
        $this->entityManager = $em;
        $this->twig = $twig;
        $this->message = $message;
        $this->logger = $logger;
    }

    public function singleEmailAction() {

        $emailTemplate = $this->entityManager->getRepository('AppBundle:EmailTemplate')->find(20);


        try {
            $html = $this->twig->render(':EmailTemplates/base:template_style_1.html.twig', array(
                'body_html' => $emailTemplate->getHtml()
            ));
        } catch (\Exception $e) {
            $this->logger->crit("Error Rendering Email Template: ".$e->getMessage());
        }

        try {
            $this->message->setFrom('no-reply@custprotection.com');
            $this->message->addTo('snp28@miami.edu', 'Stephen');
            $this->message->setSubject("Test");
            $this->message->setHtmlMessage($html);
            $response = $this->message->send();
            $this->logger->info("Email Sent: ".$response);
            //Create Email Instance
        } catch (\Exception $e) {
            $this->logger->crit("Error Sending Email Postmark: ".$e->getMessage());
        }


        return;
    }



    public function testEmailAction(EmailTemplate $emailTemplate, $emailAddress) {

         try {
             $html = $this->twig->render(':EmailTemplates/base:template_style_1.html.twig', array(
                 'body_html' => $emailTemplate->getHtml()
             ));
         } catch (\Exception $e) {
             $this->logger->crit("Error Rendering Email Template: ".$e->getMessage());
         }

         try {
             $this->message->setFrom('no-reply@custprotection.com');
             $this->message->addTo($emailAddress);
             $this->message->setSubject($emailTemplate->getEmailSubject());
             $this->message->setHtmlMessage($html);
             $response = $this->message->send();
             $this->logger->info("Email Sent: ".$response);
         } catch (\Exception $e) {
             $this->logger->crit("Error Sending Email Postmark: ".$e->getMessage());
         }

        return;
    }



}