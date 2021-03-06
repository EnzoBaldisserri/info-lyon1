<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/control", name="control_")
 */
class ControlController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->createHtmlResponse('control/index.html.twig', [
            'controller_name' => 'ControlController',
        ]);
    }
}
