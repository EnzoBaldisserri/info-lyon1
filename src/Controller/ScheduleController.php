<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use App\Service\ScheduleFetcher;

/**
 * @Route("/schedule", name="schedule_")
 */
class ScheduleController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(ScheduleFetcher $fetcher)
    {
        $schedule = $fetcher
            ->setResources('4117')
            ->setWeek()
            ->load();

        return $this->createHtmlResponse('schedule/index.html.twig', [
            'schedule' => $schedule,
        ]);
    }
}
