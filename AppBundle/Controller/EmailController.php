<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{


    /**
     * WEBHOOK FUNCTION
     * ----------------
     *
     * @Route("/webhook/email/open")
     * @param Request $request
     * @return JsonResponse
     */
    public function webhookEmailOpened(Request $request) {

        //Get Request
        $data = $request->request->get('MessageID');

        $this->get('mailfunnels.logger')->info('Webhook Email Opened: '.$data);
        $this->get('mailfunnels.logger')->info(json_encode($request));
        $this->get('mailfunnels.logger')->info(json_encode($request->request));

        return new JsonResponse(array(
           'success' => true,
        ));

    }
}
