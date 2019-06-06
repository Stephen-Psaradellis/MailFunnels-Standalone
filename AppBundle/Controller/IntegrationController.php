<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserIntegrationLink;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IntegrationController extends Controller
{


    /**
     * API JSON ROUTE
     * --------------
     *
     * @Route("/api_v1/integrations/get")
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserIntegrations(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $user = $this->getUser();

            $shopifyWebhooks = array();
            // Get Shopify API URL
            if ($shop = $user->getShopifyStore()) {
                $shopifyAPI = "https://".$shop.".myshopify.com/";

                //Create Purchase Webhook
                $hookTemp = array(
                    'topic'     => "orders/create",
                    'address'   => $this->getParameter('shopify_hook_purchase'),
                    'format'    => "json"
                );
                array_push($shopifyWebhooks, $hookTemp);

                //Create Refund Webhook
                $hookTemp = array(
                    'topic'     => "refunds/create",
                    'address'   => $this->getParameter('shopify_hook_refund'),
                    'format'    => "json"
                );
                array_push($shopifyWebhooks, $hookTemp);

                //Create Abandoned Webhook
                $hookTemp = array(
                    'topic'     => "checkouts/create",
                    'address'   => $this->getParameter('shopify_hook_abandoned'),
                    'format'    => "json"
                );
                array_push($shopifyWebhooks, $hookTemp);

            } else {
                $shopifyAPI = null;
            }

            // Get Integrations for User
            $integrationLinks = $this->getDoctrine()->getRepository('AppBundle:UserIntegrationLink')->getIntegrationsForUser($user);

            // Create Response JSON
            $integrationsJSON = array();
            foreach ($integrationLinks as $link) {

                if ($link->getType() == UserIntegrationLink::$TYPE_SHOPIFY) {

                    if ($link->getShopify()) {
                        $integrationInfo = $link->getShopify()->getJSON();
                    } else {
                        $integrationInfo = array();
                    }
                }

                $temp = array(
                    'type'  => $link->getType(),
                    'info'  => $integrationInfo
                );

                array_push($integrationsJSON, $temp);
            }

            return new JsonResponse(array(
                'shopify_config'        => array(
                    'client_id'         => $this->getParameter('shopify_api_key'),
                    'client_secret'     => $this->getParameter('shopify_secret'),
                    'API_URL'           => $shopifyAPI,
                    'webhooks'          => $shopifyWebhooks
                ),
                'integrations'  => $integrationsJSON
            ));
        }

    }
}
