<?php

namespace AppBundle\Job;


use AppBundle\Entity\CapturedHook;
use AppBundle\Entity\Subscriber;
use AppBundle\Entity\SubscriberUserLink;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Exception;
use Monolog\Logger;

class WebhookShopifyWorker
{

    public static $TYPE_BASE = 0;
    public static $TYPE_PURCHASE = 1;
    public static $TYPE_ABANDON_CART = 2;
    public static $TYPE_REFUND = 3;


    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Logger
     *
     */
    private $logger;

    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->entityManager = $em;
        $this->logger = $logger;
    }

    public function fire($data = null)
    {

        $type = intval($data['type']);

        if ($type == $this::$TYPE_PURCHASE) {
            $this->processOrderCreateHook($data['hook_data']);
        } elseif ($type == $this::$TYPE_ABANDON_CART) {
            $this->processAbandonedCartHook($data['hook_data']);
        } elseif ($type == $this::$TYPE_REFUND) {
            $this->processRefundsCreateHook($data['hook_data']);
        }

        return;
    }


    public function processOrderCreateHook($data)
    {
        try {
            $this->logger->info("Purchase Product Hook: " . json_encode($data));


            //Search for User
            if (!$user = $this->getUserFromHook($data)) {
                return;
            }

            if(!$this->persistCapturedHook(CapturedHook::$TYPE_PURCHASE, $data)) {
                $this->logger->critical("ShopifyWebhookWorker: Failed To Create Captured Hook - Webhook Terminated.");
                return;
            }

            if (!$email = $data['email']) {
                $this->logger->critical("ShopifyWebhookWorker: Email Not Found - Webhook Terminated.");
                return;
            }

            $subscriber = $this->entityManager->getRepository('AppBundle:Subscriber')->findSubscriberByEmail($email);

            if (!$subscriber) {
                $subscriber = $this->persistNewSubscriber($email, $data, Subscriber::$REF_TYPE_PURCHASE);
                if (!$subscriber) {
                    $this->logger->critical("ShopifyWebhookWorker: Subscriber Not Created - Webhook Terminated.");
                    return;
                }
            }

            $subscriberUserLink = $this->entityManager->getRepository('AppBundle:SubscriberUserLink')->findSubscriberUserLinkBySubscriberEmail($email);

            if (!$subscriberUserLink) {
                $subscriberUserLink = $this->persistNewSubscriberUserLink($subscriber, $user);
                if (!$subscriberUserLink)
                    return;
            }

            $funnel = $this->entityManager->getRepository('AppBundle:FunnelUserLink')->findFunnelByUserAndHookType($user, WebhookShopifyWorker::$TYPE_PURCHASE);

            if (!$funnel) {
                $this->logger->critical("ShopifyWebhookWorker: Funnel Not Found - Webhook Terminated.");
                return;
            } else {
                $startNodeConnector = $this->entityManager->getRepository('AppBundle:NodeConnector')->findStartConnector($funnel);
                if(!$startNodeConnector) {
                    $this->logger->critical("ShopifyWebhookWorker: Start Node Connector Not Found - Webhook Terminated.");
                    return;
                }
            }
            return;
        } catch (ORMException $e) {
            $this->logger->critical("ShopifyWebhookWorker: Process Order Error - ORM Exception.");
            return null;
        } catch (Exception $e) {
            $this->logger->critical("ShopifyWebhookWorker: Process Order Error - General Exception.");
            return null;
        }
    }


    public
    function processAbandonedCartHook($data)
    {
        try {
            $this->logger->info("Abandoned Cart Hook: " . json_encode($data));

            //Search for User
            if (!$user = $this->getUserFromHook($data)) {
                return;
            }

            if (!$this->persistCapturedHook(CapturedHook::$TYPE_ABANDON_CART, $data)) {
                $this->logger->critical("ShopifyWebhookWorker: Failed To Create Captured Hook - Webhook Terminated.");
                return;
            }

            if (!$email = $data['email']) {
                $this->logger->critical("ShopifyWebhookWorker: Email Not Found - Webhook Terminated.");
                return;
            }

            $subscriber = $this->entityManager->getRepository('AppBundle:Subscriber')->findSubscriberByEmail($email);

            if (!$subscriber) {
                $subscriber = $this->persistNewSubscriber($email, $data, Subscriber::$REF_TYPE_ABANDON_CART);
                if (!$subscriber) {
                    $this->logger->critical("ShopifyWebhookWorker: Subscriber Not Created - Webhook Terminated.");
                    return;
                }
            }

            $subscriberUserLink = $this->entityManager->getRepository('AppBundle:SubscriberUserLink')->findSubscriberUserLinkBySubscriberEmail($email);

            if (!$subscriberUserLink) {
                $subscriberUserLink = $this->persistNewSubscriberUserLink($subscriber, $user);
                if (!$subscriberUserLink) {
                    $this->logger->critical("ShopifyWebhookWorker: Subscriber User Link Not Created - Webhook Terminated.");
                    return;
                }
            }
            $funnel = $this->entityManager->getRepository('AppBundle:FunnelUserLink')->findFunnelByUserAndHookType($user, WebhookShopifyWorker::$TYPE_ABANDON_CART);

            if (!$funnel) {
                $this->logger->critical("ShopifyWebhookWorker: Funnel Not Found - Webhook Terminated.");
                return;
            } else {
                $startNodeConnector = $this->entityManager->getRepository('AppBundle:NodeConnector')->findStartConnector($funnel);
                if(!$startNodeConnector) {
                    $this->logger->critical("ShopifyWebhookWorker: Start Node Connector Not Found - Webhook Terminated.");
                    return;
                }
            }
            return;
        } catch (ORMException $e) {
            $this->logger->critical("ShopifyWebhookWorker: Abandoned Cart Error - ORM Exception.");
            return null;
        } catch (Exception $e) {
            $this->logger->critical("ShopifyWebhookWorker: Abandoned Cart Error - General Exception.");
            return null;
        }
    }

    public function processRefundsCreateHook($data)
    {
        try {
            $this->logger->info("Refund Product Hook: " . json_encode($data));


            //Search for User
            if (!$user = $this->getUserFromHook($data)) {
                return;
            }

            if(!$this->persistCapturedHook(CapturedHook::$TYPE_REFUND, $data)) {
                $this->logger->critical("ShopifyWebhookWorker: Failed To Create Captured Hook - Webhook Terminated.");
                return;
            }

            if (!$email = $data['email']) {
                $this->logger->critical("ShopifyWebhookWorker: Email Not Found - Webhook Terminated.");
                return;
            }

            $subscriber = $this->entityManager->getRepository('AppBundle:Subscriber')->findSubscriberByEmail($email);

            if (!$subscriber) {
                $subscriber = $this->persistNewSubscriber($email, $data, Subscriber::$REF_TYPE_REFUND);
                if (!$subscriber) {
                    $this->logger->critical("ShopifyWebhookWorker: Subscriber Not Created - Webhook Terminated.");
                    return;
                }
            }

            $subscriberUserLink = $this->entityManager->getRepository('AppBundle:SubscriberUserLink')->findSubscriberUserLinkBySubscriberEmail($email);

            if (!$subscriberUserLink) {
                $subscriberUserLink = $this->persistNewSubscriberUserLink($subscriber, $user);
                if (!$subscriberUserLink)
                    return;
            }

            $funnel = $this->entityManager->getRepository('AppBundle:FunnelUserLink')->findFunnelByUserAndHookType($user, WebhookShopifyWorker::$TYPE_REFUND);

            if (!$funnel) {
                $this->logger->critical("ShopifyWebhookWorker: Funnel Not Found - Webhook Terminated.");
                return;
            } else {
                $startNodeConnector = $this->entityManager->getRepository('AppBundle:NodeConnector')->findStartConnector($funnel);
                if(!$startNodeConnector) {
                    $this->logger->critical("ShopifyWebhookWorker: Start Node Connector Not Found - Webhook Terminated.");
                    return;
                }
            }
            return;
        } catch (ORMException $e) {
            $this->logger->critical("ShopifyWebhookWorker: Process Order Error - ORM Exception.");
            return null;
        } catch (Exception $e) {
            $this->logger->critical("ShopifyWebhookWorker: Process Order Error - General Exception.");
            return null;
        }
    }


    /* ---- HELPER FUNCTIONS ---- */

    private function getUserFromHook($hookData)
    {
        try {
            //Find User
            $user = $this->entityManager->getRepository('AppBundle:User')->find(1);
            return $user;
        } catch (ORMException $e) {
            $this->logger->critical("ShopifyWebhookWorker: Error Getting User - ORM Exception.");
            return null;
        } catch (\Exception $e) {
            $this->logger->critical("ShopifyWebhookWorker: Error Getting User - General Exception.");
            return null;
        }
    }


    private function persistCapturedHook($hook, $data)
    {
        try {
            $capturedHook = new CapturedHook($hook, json_encode($data));
            $this->entityManager->persist($capturedHook);
            $this->entityManager->flush();
            return $capturedHook;
        } catch (ORMException $e) {
            $this->logger->critical("ShopifyWebhookWorker: Error Creating/Persisting Captured Hook Entity - ORM Exception.");
            return null;
        } catch (\Exception $e) {
            $this->logger->critical("ShopifyWebhookWorker: Error Creating/Persisting Captured Hook Entity - General Exception.");
            return null;
        }
    }

    /**
     * @param $email
     * @param $data
     * @param $refType
     * @return Subscriber|null
     *
     */
    private function persistNewSubscriber($email, $data, $refType)
    {
        try {
            $subscriber = new Subscriber();
            $subscriber->setEmail($email);
            $subscriber->setFirstName($data['billing_address']['first_name']);

            $subscriber->setLastName($data['billing_address']['last_name']);
            $subscriber->setRefType($refType);

            $this->entityManager->persist($subscriber);
            $this->entityManager->flush();

            return $subscriber;
        } catch (ORMException $e) {
            $this->logger->critical("ShopifyWebhookWorker: Error Creating/Persisting Subscriber Entity - ORM Exception.");
            return null;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->logger->critical("ShopifyWebhookWorker: Error Creating/Persisting Subscriber Entity - General Exception.");
            return null;
        }
    }

    private function persistNewSubscriberUserLink($subscriber, $user)
    {
        try {
            $subscriberUserLink = new SubscriberUserLink($user, $subscriber);
            $this->entityManager->persist($subscriberUserLink);
            $this->entityManager->flush();
            return $subscriberUserLink;
        } catch (ORMException $e) {
            $this->logger->critical("ShopifyWebhookWorker: Error Creating/Persisting Subscriber User Link Entity - ORM Exception.");
            return null;
        } catch (\Exception $e) {
            $this->logger->critical("ShopifyWebhookWorker: Error Creating/Persisting Subscriber User Link Entity - General Exception.");
            return null;
        }
    }
}