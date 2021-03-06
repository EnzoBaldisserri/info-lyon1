<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mark", name="mark_")
 */
class MarkController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->createHtmlResponse('mark/index.html.twig', [
            'controller_name' => 'MarkController',
        ]);
    }
}
