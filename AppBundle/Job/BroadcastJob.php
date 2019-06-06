<?php

namespace AppBundle\Job;


use AppBundle\Entity\Email;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Exception;
use ExpertCoder\Swiftmailer\SendGridBundle\Services\SendGridTransport;
use Monolog\Logger;
use Swift_Mime_SimpleHeaderSet;
use Swift_Mime_SimpleMessage;

class BroadcastJob
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Logger
     *
     */
    private $logger;

    /**
     * @var SendGridTransport
     */
    private $sendgrid;

    public function __construct(EntityManager $em, Logger $logger, SendGridTransport $sendgrid)
    {
        $this->entityManager = $em;
        $this->logger = $logger;
        $this->sendgrid = $sendgrid;
    }

    public function sendBroadcast($broadcastID, $userID)
    {

        $user = $this->entityManager->getRepository('AppBundle:User')->find($userID);

        if (!$user) {
            $this->logger->critical('BroadcastJob: sendBroadcast - User Not Found');
            return;
        }

        $broadcast = $this->entityManager->getRepository('AppBundle:Broadcast')->find($broadcastID);

        if (!$broadcast) {
            $this->logger->critical('BroadcastJob: sendBroadcast - Broadcast Not Found');
            return;
        }

        $broadcastUserLink = $this->entityManager->getRepository('AppBundle:BroadcastUserLink')->findBroadcastUserLinkByBroadcast($broadcast);

        if (!$broadcastUserLink) {
            $this->logger->critical('BroadcastJob: sendBroadcast - Broadcast User Link Not Found');
            return;
        }

        $broadcastEmailTemplateLink = $this->entityManager->getRepository('AppBundle:BroadcastEmailTemplateLink')->findBroadcastEmailTemplateLinkByBroadcast($broadcast);

        if (!$broadcastEmailTemplateLink) {
            $this->logger->critical('BroadcastJob: sendBroadcast - Broadcast Email Template Link Not Found');
            return;
        }

        $broadcastEmailListLink = $this->entityManager->getRepository('AppBundle:BroadcastEmailListLink')->findBroadcastEmailListLinkIDByBroadcast($broadcast);

        if (!$broadcastEmailListLink) {
            $this->logger->critical('BroadcastJob: sendBroadcast - Broadcast Email List Not Found');
            return;
        }


        $subscribers = $this->entityManager->getRepository('AppBundle:SubscriberEmailListLink')->getSubscriberEmailListLinksByEmailList($broadcastEmailListLink->getEmailList());




        foreach ($subscribers as $link) {
            try {
                $this->logger->critical('Subscriber: ' . $link->getSubscriber()->getEmail());

                $emailEntity = new Email($user, Email::$TYPE_SENDGRID, null);
                $emailEntity->setBroadcastID($broadcast->getId());
                $emailEntity->setSubscriberID($link->getSubscriber()->getId());

                $this->entityManager->persist($emailEntity);
                $this->entityManager->flush();

//            $message = (new Swift_Mime_SimpleMessage('Hello_Email'))
//                ->setFrom('mailfunnels@mailfunnels.com')
//                ->setTo($link->getSubscriber()->getEmail())
//                ->setBody();
//
//            $this->sendgrid->send();
            }
            catch (ORMException $e) {
                    $this->logger->critical("BroadcastJob: Send Broadcast - ORM Exception.");
                    return null;
                } catch (Exception $e) {
                    $this->logger->critical($e);
                    $this->logger->critical("BroadcastJob: Send Broadcast - General Exception.");
                    return null;
                }
        }
        $this->entityManager->clear();

    }

}