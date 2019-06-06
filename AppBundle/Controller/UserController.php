<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{

    /**
     * PAGE RENDER FUNCTION
     * --------------------
     * Renders the My Account Page
     *
     * @Route("/account", name="my_account")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myAccountPageAction() {

        $user = $this->getUser();

        return $this->render(":User:my_account.html.twig", array(
            'shopify_api_key' => $this->getParameter('shopify_api_key'),
            'shopify_api_secret' => $this->getParameter('shopify_secret'),
        ));
    }
}
