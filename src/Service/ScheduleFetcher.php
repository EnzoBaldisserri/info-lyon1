<?php
namespace App\Service;

use DateTime;
use RuntimeException;
use ICal\ICal;
use App\Entity\Schedule\Schedule;

class ScheduleFetcher
{
    /**
     * @var string
     */
    private $fetch_url;

    /**
     * @var string
     */
    private $date_format;

    /**
     * @var string
     */
    private $resources;

    /**
     * @var int
     */
    private $projectId;

    /**
     * @var DateTime
     */
    private $firstDate;

    /**
     * @var DateTime
     */
    private $lastDate;

    function __construct(string $fetch_url, int $projectId, string $date_format) {
        $this->fetch_url = $fetch_url;
        $this->projectId = $projectId;
        $this->date_format = $date_format;
    }

    function setResources(string $resources) {
        $this->resources = $resources;

        return $this;
    }

    function setDay(DateTime $day = null) {
        if ($day === null) {
            $day = new \DateTime();
        }

        $day->setTime(0, 0, 0, 0);

        $dayEnd = clone $day;
        $dayEnd->setTime(23, 59, 59, 999);

        $this->firstDate = $day;
        $this->lastDate = $dayEnd;

        return $this;
    }

    function setWeek(DateTime $week = null) {
        if ($week === null) {
            $week = new \DateTime();
        }

        $weekDay = (int) $week->format('N');

        $firstDayOfWeek = clone $week;
        $firstDayOfWeek
            ->modify('-' . ($weekDay - 1) . ' days')
            ->setTime(0, 0, 0, 0);

        $lastDayOfWeek = clone $week;
        $lastDayOfWeek
            ->modify('+' . abs(7 - $weekDay) . ' days')
            ->setTime(23, 59, 59, 999);

        $this->firstDate = $firstDayOfWeek;
        $this->lastDate = $lastDayOfWeek;

        return $this;
    }

    function load() {
        $this->verifyResources();

        $replace = array(
            '{resources}',
            '{projectId}',
            '{firstDate}',
            '{lastDate}'
        );

        $with = array(
            $this->resources,
            $this->projectId,
            $this->firstDate->format($this->date_format),
            $this->lastDate->format($this->date_format)
        );

        $url = str_replace(
            $replace,
            $with,
            $this->fetch_url
        );

        try {
            $ical = new ICal($url);
            return new Schedule($ical);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function verifyResources() {
        if (!isset($this->resources)) {
            throw new RuntimeException('Resources are not defined');
        }

        if (!isset($this->projectId)) {
            throw new RuntimeException("Project id isn\'t defined");
        }

        if (!isset($this->firstDate)) {
            throw new RuntimeException('First date isn\'t defined');
        }

        if (!isset($this->lastDate)) {
            throw new RuntimeException('Last date isn\'t defined');
        }
    }
}
