<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project", name="project_")
 */
class ProjectController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->createHtmlResponse('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }
}
