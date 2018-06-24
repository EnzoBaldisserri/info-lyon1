<?php

namespace App\Entity;

use DateTime;

class Period
{
    /**
     * @var DateTime
     */
    private $start;

    /**
     * @var DateTime
     */
    private $end;

    /**
     * Creates a period between two dates.
     * Dates can be given in any order.
     * The earliest date will be given by #getStart
     * and the latest one by #getEnd.
     *
     * @param DateTime $firstDate
     * @param DateTime $secondDate
     */
    public function __construct(DateTime $firstDate, DateTime $secondDate)
    {
        if ($firstDate <= $secondDate) {
            $this->start = $firstDate;
            $this->end = $secondDate;
        } else {
            $this->start = $secondDate;
            $this->end = $firstDate;
        }
    }

    public function __clone()
    {
        $this->start = clone $this->start;
        $this->end = clone $this->end;
    }

    public function getStart(): DateTime
    {
        return $this->start;
    }

    public function getEnd(): DateTime
    {
        return $this->end;
    }
}
