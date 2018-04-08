<?php

namespace IL\ScheduleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IL\ScheduleBundle\Schedule;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $schedule = $this->get('il_schedule.ScheduleFetcher')
            ->setResources('4117')
            ->setWeek()
            ->load();

        return $this->render(
            '@ILSchedule/index.html.twig',
            array(
                'schedule' => $schedule->getWeek()
            )
        );
    }
}
