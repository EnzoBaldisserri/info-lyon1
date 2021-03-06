<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/followup", name="followup_")
 */
class FollowUpController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->createHtmlResponse('follow_up/index.html.twig', [
            'controller_name' => 'FollowUpController',
        ]);
    }
}
