<?php

namespace App\Entity\Administration;

use App\Entity\Absence\Absence;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Administration\SemesterRepository")
 */
class Semester
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Course", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    /**
     * @var Array
     */
    private $months;

    public function __construct() {
        $this->active = false;
        $this->months = null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return 'S' . $this->course->getSemester();
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        // Start date's time must be the beginning of the day
        $startDate->setTime(0, 0, 0, 0);

        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        // End date's time must be the end of the day
        $endDate->setTime(23, 59, 59, 999);

        $this->endDate = $endDate;

        return $this;
    }

    public function isActive(\DateTimeInterface $datetime = null): ?bool
    {
        if ($datetime === null) {
            $datetime = new \DateTime();
        }

        // Check if datetime is between begin and end
        return $this->startDate->diff($datetime)->invert === 0
            && $this->endDate->diff($datetime)->invert === 1;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    const MONTHES = [
        'Janvier',
        'Février',
        'Mars',
        'Avril',
        'Mai',
        'Juin',
        'Juillet',
        'Août',
        'Septembre',
        'Octrobre',
        'Novembre',
        'Décembre',
    ];

    const DAYS_SHORT = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];

    public function getMonths(): Array
    {
        if (isset($this->months)) {
            return $this->months;
        }

        $months = [];

        [$startDay, $startMonth, $startYear] = explode('-', $this->startDate->format('Y-n-j'));
        $dayInWeek = (int) $this->startDate->format('N');

        [$endDay, $endMonth, $endYear] = explode('-', $this->endDate->format('Y-n-j'));

        $dayInMonth = $startDay;
        $month = $startMonth;
        $year = $startYear;

        $nbDayInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        while ($year !== $endYear || $month !== $endMonth) {
            $days = [];

            while ($dayInMonth <= $nbDayInMonth) {
                $days[$dayInMonth] = self::DAYS_SHORT[$dayInWeek];

                $dayInMonth += 1;
                $dayInWeek = ($dayInWeek + 1) % 7;
            }

            $months[] = [
                'name' => self::MONTHES[$month - 1],
                'days' => $days,
            ];

            $month += 1;
            if ($month > 12) {
                $month = 1;
                $year += 1;
            }

            $dayInMonth = 1;
            $nbDayInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }

        // Compute days for last month
        $days = [];

        while ($dayInMonth <= $endDay) {
            $days[$dayInMonth] = self::DAYS_SHORT[$dayInWeek];

            $dayInMonth += 1;
            $dayInWeek = ($dayInWeek + 1) % 7;
        }

        $months[] = [
            'name' => self::MONTHES[$month - 1],
            'days' => $days,
        ];

        $this->months = $months;
        return $months;
    }

}
