<?php

namespace App\Entity\Control;

use Doctrine\Common\Collections\ArrayCollection;

class MarkCollection extends ArrayCollection
{
    /**
     * @param int|string $key
     * @param Mark $value
     * @return bool
     */
    public function set($key, $value): bool
    {
        if ($value instanceof Mark) {
            parent::set($key, $value);
            return true;
        }

        return false;
    }

    /**
     * @param Mark $element
     * @return bool
     */
    public function add($element): bool
    {
        if ($element instanceof Mark) {
            return parent::add($element);
        }

        return false;
    }

    /**
     * @return float|bool false if collection is empty
     */
    public function getMedian(): float
    {
        if ($this->isEmpty()) {
            return false;
        }

        $marks = array_map(
            function($mark) { $mark->getValue(); },
            $this->toArray()
        );

        sort($marks);

        $length = count($marks);
        if ($length % 2 === 0) {
            return ($marks[$length / 2] + $marks[($length / 2) + 1]) / 2;
        } else {
            return $marks[($length + 1) / 2];
        }
    }

    private function getSum(): float
    {
        return array_reduce(
            $this->toArray(),
            function($total, $mark) {
                return $total + $mark->getValue();
            },
            0
        );
    }

    /**
     * @return float
     */
    public function getAverage(): float
    {
        return $this->getSum() / $this->count();
    }

    /**
     * @return float
     */
    public function getStandardDeviation(): float
    {
        $avg = $this->getAverage();
        $n = $this->count();

        $sumSquared = array_reduce(
            $this->toArray(),
            function($total, $mark) {
                $value = $mark->getValue();
                return $total + ($value * $value);
            },
            0
        );

        $variance = ($sumSquared / $n) - ($avg * $avg);
        return sqrt($variance);
    }
}
