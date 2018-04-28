<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;

/**
 * @Route("/administration", name="administration_")
 */
class AdministrationController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->createHtmlResponse('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
}
