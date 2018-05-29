<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard", name="dashboard_")
 */
class DashboardController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->createHtmlResponse('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
