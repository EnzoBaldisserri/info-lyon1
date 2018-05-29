<?php

namespace App\Controller;

use App\Service\NavbarBuilder;
use App\Repository\User\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    private $navbarBuilder;
    private $notificationRepository;

    public function __construct(NavbarBuilder $navbarBuilder, NotificationRepository $notificationRepository)
    {
        $this->navbarBuilder = $navbarBuilder;
        $this->notificationRepository = $notificationRepository;
    }

    protected function createHtmlResponse(string $view, Array $parameters = [], Response $response = null): Response
    {
        $this->checkUserParameters($parameters);

        $parameters['navigation'] = $this->navbarBuilder->getNavigation();
        $parameters['notifications'] = $this->notificationRepository->findBy(
            ['user' => $this->getUser()],
            ['datetime' => 'DESC']
        );

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
