<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Service\HomepageRedirection;

class HomepageController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(TokenStorageInterface $tokenStorage, HomepageRedirection $homepageRedirection)
    {
        $token = $tokenStorage->getToken();

        if ($token === null) {
            $this->redirectToRoute('fos_user_security_login');
        }

        return $homepageRedirection->getRedirection($token);
    }
}
