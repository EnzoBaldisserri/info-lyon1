<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;

/**
 * @Route("/project", name="project_")
 */
class ProjectController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->createHtmlResponse('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }
}
