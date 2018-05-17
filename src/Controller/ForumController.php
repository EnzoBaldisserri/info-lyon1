<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;

/**
 * @Route("/forum", name="forum_")
 */
class ForumController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->createHtmlResponse('forum/index.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }
}
