<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AfterLoginRedirection implements AuthenticationSuccessHandlerInterface
{
    private $router;
    private $translator;

    public function __construct(UrlGeneratorInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return $this->getRedirection($token);
    }

    public function getRedirection(TokenInterface $token): ?RedirectResponse
    {
        $roles = array_map(function($role) {
            return $role->getRole(); // ~toString
        }, $token->getRoles());

        if (in_array('ROLE_STUDENT', $roles, true)) {
            $redirection = new RedirectResponse($this->router->generate('dashboard_index'));
        } elseif (in_array('ROLE_TEACHER', $roles, true)
            || in_array('ROLE_SECRETARIAT', $roles, true)
        ) {
            $redirection = new RedirectResponse($this->router->generate('absence_index'));
        } else {
            throw new AccessDeniedHttpException($this->translator->trans('error.login.no_role'));
        }

        return $redirection;
    }
}
