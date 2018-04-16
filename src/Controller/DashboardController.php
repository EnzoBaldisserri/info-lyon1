<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;

/**
 * @Route("/dashboard", name="dashboard_")
 */
class DashboardController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->show('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
