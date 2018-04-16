<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;

/**
 * @Route("/followup", name="followup_")
 */
class FollowUpController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->show('follow_up/index.html.twig', [
            'controller_name' => 'FollowUpController',
        ]);
    }
}
