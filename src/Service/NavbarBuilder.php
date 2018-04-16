<?php

namespace App\Service;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class NavbarBuilder
{
    private $authChecker;
    private $routesMap;

    public function __construct(AuthorizationCheckerInterface $authChecker, Array $routesMap)
    {
        $this->authChecker = $authChecker;
        $this->routesMap = $routesMap;
    }

    public function getNavigation(): Array
    {
        $navigation = [];

        foreach ($this->routesMap as $role => $routes) {
            if ($this->authChecker->isGranted($role)) {
                foreach ($routes as $name => $route) {
                    $navigation[$name] = $route;
                }
            }
        }

        return $navigation;
    }
}
