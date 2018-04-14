<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\Schedule\ScheduleFetcher;

class ScheduleController extends Controller
{
    /**
     * @Route("/schedule", name="schedule")
     */
    public function index(ScheduleFetcher $fetcher)
    {
        $schedule = $fetcher
            ->setResources('4117')
            ->setWeek()
            ->load();

        return $this->render('schedule/index.html.twig', [
            'schedule' => $schedule,
        ]);
    }
}
