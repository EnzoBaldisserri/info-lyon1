<?php

namespace App\Controller;

use App\Service\NavbarBuilder;
use App\Service\NotificationBuilder;
use App\Repository\User\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    private $navbarBuilder;
    private $notificationBuilder;
    private $notificationRepository;

    public function __construct(
        NavbarBuilder $navbarBuilder,
        NotificationBuilder $notificationBuilder,
        NotificationRepository $notificationRepository
    ) {
        $this->navbarBuilder = $navbarBuilder;
        $this->notificationBuilder = $notificationBuilder;
        $this->notificationRepository = $notificationRepository;
    }

    protected function createHtmlResponse(string $view, Array $parameters = [], Response $response = null): Response
    {
        $this->validate($parameters);

        $parameters['navigation'] = $this->navbarBuilder->getNavigation();
        $parameters['notifications'] = $this->notificationRepository->findBy(
            ['user' => $this->getUser()],
            ['datetime' => 'DESC']
        );

        return parent::render($view, $parameters, $response);
    }

    /**
     * @param int|null $duration The duration of the notification
     * @return NotificationBuilder
     */
    protected function createNotification(int $duration = null)
    {
        if ($duration === null) {
            $duration = NotificationBuilder::DURATION_FLASH;
        }
        return $this->notificationBuilder->newNotification($duration);
    }

    private function validate(Array $parameters)
    {
        if (isset($parameters['navigation'])) {
            throw new \RuntimeException('"navigation" is a reserved entry in parameters');
        }

        if (isset($parameters['notifications'])) {
            throw new \RuntimeException('"notifications" is a reserved entry in parameters');
        }
    }
}
