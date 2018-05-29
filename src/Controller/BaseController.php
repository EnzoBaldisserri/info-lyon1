<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\NavbarBuilder;

abstract class BaseController extends AbstractController
{
    private $navbarBuilder;

    public function __construct(NavbarBuilder $navbarBuilder)
    {
        $this->navbarBuilder = $navbarBuilder;
    }

    protected function createHtmlResponse(string $view, Array $parameters = [], Response $response = null): Response
    {
        $this->checkUserParameters($parameters);

        $parameters['navigation'] = $this->navbarBuilder->getNavigation();

        return parent::render($view, $parameters, $response);
    }

    private function checkUserParameters(Array $parameters)
    {
        if (isset($parameters['navigation'])) {
            throw new \RuntimeException('"navigation" is a reserved entry in parameters');
        }

        if (isset($parameters['notifications'])) {
            throw new \RuntimeException('"notifications" is a reserved entry in parameters');
        }
    }
}
