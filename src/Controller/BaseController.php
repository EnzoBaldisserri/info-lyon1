<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\NavbarBuilder;

abstract class BaseController extends AbstractController
{
    private $navbarBuilder;

    public function __construct(NavbarBuilder $navbarBuilder)
    {
        $this->navbarBuilder = $navbarBuilder;
    }

    protected function show(string $view, Array $parameters = [], Response $response = null): Response
    {
        $this->checkUserParameters($parameters);

        $parameters['navigation'] = $this->navbarBuilder->getNavigation();

        return parent::render($view, $parameters, $response);
    }

    private function checkUserParameters(Array $parameters)
    {
        if (array_key_exists('navigation', $parameters)) {
            throw new \RuntimeException('\'navigation\' is a reserved entry in parameters');
        }
    }
}
