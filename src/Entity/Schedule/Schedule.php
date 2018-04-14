<?php

namespace App\Entity\Schedule;

use App\Entity\Lesson;
use ICal\Ical;

class Schedule
{
    private $ical;

    public function __construct(ICal $ical) {
        $this->ical = $ical;
    }

    public function get() {
        return $this->build($this->ical->events());
    }

    public function getDay() {
        $build = $this->build($this->ical->events(), 'day');
        if ($build['period'] !== 'day') {
            throw new \Exception('Dates don\'t correspond to a day');
        }

        return $build['lessons'];
    }

    public function getWeek() {
        $build = $this->build($this->ical->events(), 'week');
        if ($build['period'] !== 'week') {
            throw new \Exception('Dates don\'t correspond to a week');
        }

        return $build['lessons'];
    }

    private function build(array $events, string $maxNarrowing = 'day') {
        $lessons = array();

        foreach ($events as $event) {
            $lesson = $this->lessonFromEvent($event);

            // Start date and end date should always be the same day
            $date = $lesson->getStartDate();
            $year = $date->format('Y');
            $week = $date->format('W');
            $day = $date->format('N');

            if (!array_key_exists($year, $lessons)) {
                $lessons[$year] = array();
            }

            if (!array_key_exists($week, $lessons[$year])) {
                $lessons[$year][$week] = array();
            }

            if (!array_key_exists($day, $lessons[$year][$week])) {
                $lessons[$year][$week][$day] = array();
            }

            $lessons[$year][$week][$day][] = $lesson;
        }

        // Narrow the result if needed
        $period = '';
        $years = array_keys($lessons);

        if (count($years) === 1 && $period !== $maxNarrowing) {
            $lessons = $lessons[$years[0]];
            $period = 'year';

            $weeks = array_keys($lessons);
            if (count($weeks) === 1 && $period !== $maxNarrowing) {
                $lessons = $lessons[$weeks[0]];
                $period = 'week';

                $days = array_keys($lessons);
                if (count($days) === 1 && $period !== $maxNarrowing) {
                    $lessons = $lessons[$days[0]];
                    $period = 'day';
                }
            }
        }

        return array(
            'lessons' => $lessons,
            'period' => $period
        );
    }

    private function lessonFromEvent($event) {
        $name = $event->summary;
        $startDate = $this->ical->iCalDateToDateTime($event->dtstart);
        $endDate = $this->ical->iCalDateToDateTime($event->dtend);

        $description = explode("\n", $event->description);
        $groupLimit = 0;
        while (preg_match('/^(G\d+)?S\d+$/i', $description[$groupLimit])) {
            $groupLimit++;
        }

        $groups = array_slice($description, 0, $groupLimit);
        $teachers = array_slice($description, $groupLimit, -1);
        $rooms = explode(',', $event->location);

        return (new Lesson())
            ->setName($name)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setGroups($groups)
            ->setTeachers($teachers)
            ->setRooms($rooms);
    }
}