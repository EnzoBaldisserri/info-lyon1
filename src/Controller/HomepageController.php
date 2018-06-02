<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Service\AfterLoginRedirection;

class HomepageController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(TokenStorageInterface $tokenStorage, AfterLoginRedirection $afterLoginRedirection)
    {
        $token = $tokenStorage->getToken();

        if ($token === null) {
            $this->redirectToRoute('fos_user_security_login');
        }

        return $afterLoginRedirection->getRedirection($token);
    }
}
