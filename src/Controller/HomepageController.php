<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index()
    {
        $user = $this->getUser();

        if ($user === null) {
            throw $this->createAccessDeniedException('Access denied');
        }

        if ($user->hasRole('ROLE_STUDENT')) {
            return $this->redirectToRoute('dashboard_index');
        }

        if ($user->hasRole('ROLE_TEACHER') || $user->hasRole('ROLE_SECRETARIAT')) {
            return $this->redirectToRoute('absence_index');
        }

        throw $this->createAccessDeniedException('Access denied');
    }
}
