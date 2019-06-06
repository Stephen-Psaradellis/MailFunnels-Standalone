<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ShopifyIntegration;
use AppBundle\Entity\User;
use AppBundle\Entity\UserIntegrationLink;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ShopifyController extends Controller
{

    /**
     *
     * @Route("/integrations/shopify/install/url", name="integration_shopify_install_get_url")
     * @param Request $request
     * @return JsonResponse
     */
    public function installMailFunnelsShopifyAction(Request $request) {

        if ($request->isXmlHttpRequest()) {

            if (!$shop = $request->request->get('shopify_domain')) {
                $shop = "mailfunnelstesting";
            }
//            $shop = "mailfunnelstesting";
//            $scope = "read_products,read_customers,read_orders,write_orders";
            $scope = $this->getParameter('shopify_scope');
            $redirectURL = "http://localhost:8000/shopify/app/install_redirect";

            $user = $this->getUser();
            $user->setShopifyStore($shop);

            $dbManager = $this->getDoctrine()->getManager();
            $dbManager->persist($user);
            $dbManager->flush();
            $dbManager->clear();

            $url = "https://".$shop.".myshopify.com/admin/oauth/authorize?client_id=".$this->getParameter('shopify_api_key')."&amp;scope=".$scope."&amp;redirect_uri=".$redirectURL."&amp;state={nonce}";

            return new JsonResponse(array(
                'success' => true,
                'data'  => array(
                    'shop' => $shop,
                    'url' => $url,
                    'scope' => $scope,
                ),

            ));
        }
    }

    /**
     *
     * @Route("/integrations/shopify/resolve", name="integration_shopify_resolve")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function resolveShopifyIntegrationAction(Request $request) {


        if (!$shop = $this->getUser()->getShopifyStore()) {
            $shop = "mailfunnelstesting";
        }
//            $shop = "mailfunnelstesting";
//            $scope = "read_products,read_customers,read_orders,write_orders";
        $scope = $this->getParameter('shopify_scope');
        $redirectURL = "http://localhost:8000/shopify/app/install_redirect";

        $user = $this->getUser();
        $user->setShopifyStore($shop);

        $dbManager = $this->getDoctrine()->getManager();
        $dbManager->persist($user);
        $dbManager->flush();
        $dbManager->clear();

        $url = "https://".$shop.".myshopify.com/admin/oauth/authorize?client_id=".$this->getParameter('shopify_api_key')."&amp;scope=".$scope."&amp;redirect_uri=".$redirectURL."&amp;state={nonce}";

        return $this->redirect($url);
    }

    /**
     *
     * @Route("/shopify/app/install_redirect")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function installRedirectRouteAction(Request $request) {

        $code = $request->query->get('code');
        $shop = $this->getUser()->getShopifyStore();

        $shortURL = "https://".$shop.".myshopify.com/";
        $url = "https://".$shop.".myshopify.com/admin/oauth/access_token?client_id=".$this->getParameter('shopify_api_key')."&amp;client_secret=".$this->getParameter('shopify_secret')."&amp;code=".$code;

        return $this->render(':Shopify:shopify_connect_auth.html.twig', array(
            'short_url' => $shortURL,
            'auth_url' => $url,
            'code' => $code,
            'shopify_api_key' => $this->getParameter('shopify_api_key'),
            'shopify_api_secret' => $this->getParameter('shopify_secret'),
        ));

    }

    /**
     *
     * @Route("/shopify/tokens/update")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUserShopifyTokens(Request $request) {

        $user = $this->getUser();

        $accessToken = $request->request->get('access_token');

        // Create new Shopify Integration
        $shopifyIntegration = new ShopifyIntegration($user->getShopifyStore(), $accessToken);

        // Link Shopify Integration with User
        $user->setShopifyIntegration(User::$SHOPIFY_INTEGRATED_YES);
        $integrationLink = UserIntegrationLink::createNewShopifyIntegration($user, $shopifyIntegration);

        $dbManager = $this->getDoctrine()->getManager();
        $dbManager->persist($user);
        $dbManager->flush();

        $dbManager->persist($shopifyIntegration);
        $dbManager->flush();

        $dbManager->persist($integrationLink);
        $dbManager->flush();
        $dbManager->clear();

        return new JsonResponse(array(
            'success' => true,
            'token' => $accessToken
        ));
    }


}
