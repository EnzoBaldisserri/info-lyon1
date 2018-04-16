<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;

class HomepageController extends BaseController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index()
    {
        $user = $this->getUser();

        if ($user === null) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        if ($user->hasRole('ROLE_STUDENT')) {
            return $this->redirectToRoute('absence_homepage');
        }

        if ($user->hasRole('ROLE_TEACHER')) {
            return $this->redirectToRoute('absence_homepage');
        }

        if ($user->hasRole('ROLE_SECRETARIAT')) {
            return $this->redirectToRoute('absence_homepage');
        }

        throw $this->createAccessDeniedException('Accès interdit');
    }
}
