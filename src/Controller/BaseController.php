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
        if (array_key_exists('navigation', $parameters)) {
            throw new \RuntimeException('\'navigation\' is a reserved entry in parameters');
        }

        if (array_key_exists('notifications', $parameters)) {
            throw new \RuntimeException('\'notifications\' is a reserved entry in parameters');
        }
    }

    protected function createJsonResponse(Array $data = [], int $status = 200, Array $headers = [])
    {
        if (empty($data)) {
            $data['ok'] = $status === 200;
        }

        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();

        $json = $serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($json, $status, $headers);
    }
}
