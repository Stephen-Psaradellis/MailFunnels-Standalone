<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FunnelBuilderController extends Controller
{

    /**
     * API JSON ROUTE
     * --------------
     * Returns JSON array of all nodes
     *
     * @Route("/funnel/builder/nodes/get")
     * @param Request $request
     * @return JsonResponse
     */
    public function getFunnelNodesJSON(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $nodesJSON = array();

            $temp = array(
                'top' => 20,
                'left' => 20,
                'properties' => array(
                    'title' => 'Start',
                    'class' => 'flowchart-operator-start-node',
                    'inputs' => array(),
                    'outputs' => array(
                        'output_1' => array(
                            'label' => ' ',
                        ),
                    ),
                ),
            );

            array_push($nodesJSON, $temp);

            //Example Email Node
            $temp = array(
                'top' => 50,
                'left' => 600,
                'properties' => array(
                    'title' => 'Email 1',
                    'class' => 'flowchart-operator-email-node',
                    'inputs' => array(
                        'input_1' => array(
                            'label' => ' ',
                        ),
                    ),
                    'outputs' => array(
                        'output_1' => array(
                            'label' => ' ',
                        ),
                    ),
                ),
            );

            array_push($nodesJSON, $temp);

            $data = array(
                'operators' => $nodesJSON,
                'links'     => array(),
            );

            return new JsonResponse(array(
                'success'   => true,
                'data' => $data,
            ));

        }
    }

}
