<?php

namespace AppBundle\Controller;

use AppBundle\Job\WebhookShopifyWorker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopifyWebhookController extends Controller
{

    /**
     * WEBHOOK ROUTE
     * -------------
     *
     * @Route("/webhook/shopify/order_create")
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function webhookShopifyOrderCreate(Request $request) {

        if (!is_null($request->getContent())) {
            $data = print_r($request->getContent(), true);
            $data = json_decode($data, true);

            $jobData = array(
                'type'          => WebhookShopifyWorker::$TYPE_PURCHASE,
                'hook_data'     => $data,
            );

            $this->get('mailfunnels.webhook.shopify')->fire($jobData);

            return new Response('success', 200);

        } else {
            return new JsonResponse(array(
                'success' => true,
                'message' => "Hook Captured"
            ));
        }
    }

    /**
     * WEBHOOK ROUTE
     * -------------
     *
     * @Route("/webhook/shopify/refund_product")
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function webhookShopifyRefundProduct(Request $request) {

        if (!is_null($request->getContent())) {
            $data = print_r($request->getContent(), true);
            $data = json_decode($data, true);

            $jobData = array(
                'type'          => WebhookShopifyWorker::$TYPE_REFUND,
                'hook_data'     => $data,
            );

            $this->get('mailfunnels.webhook.shopify')->fire($jobData);

            return new Response('success', 200);

        } else {
            return new JsonResponse(array(
                'success' => true,
                'message' => "Hook Captured"
            ));
        }
    }

    /**
     * WEBHOOK ROUTE
     * -------------
     *
     * @Route("/webhook/shopify/abandoned_cart")
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function webhookShopifyAbandonedCart(Request $request) {

        if (!is_null($request->getContent())) {
            $data = print_r($request->getContent(), true);
            $data = json_decode($data, true);

            $jobData = array(
                'type'          => WebhookShopifyWorker::$TYPE_ABANDON_CART,
                'hook_data'     => $data,
            );

            $this->get('mailfunnels.webhook.shopify')->fire($jobData);

            return new Response('success', 200);

        } else {
            return new JsonResponse(array(
                'success' => true,
                'message' => "Hook Captured"
            ));
        }
    }
}
