<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;

/**
 * @Route("/control", name="control_")
 */
class ControlController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->show('control/index.html.twig', [
            'controller_name' => 'ControlController',
        ]);
    }
}