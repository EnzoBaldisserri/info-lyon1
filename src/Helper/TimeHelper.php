<?php

namespace App\Helper;

use Symfony\Component\Translation\TranslatorInterface;
use App\Entity\Period;

class TimeHelper
{
    const JSON_TIME_FORMAT = 'Y-m-d\TH:i:s';

    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getPeriodMonths(Period $period, bool $withRepr = false): Array
    {
        $monthsTrans = explode(',', $this->translator->trans('global.time.months'));
        $daysShortTrans = explode(',', $this->translator->trans('global.time.days_short'));

        $months = [];

        $date = clone $period->getStart();

        while ($date <= $period->getEnd()) {
            [$dayInWeek, $dayInMonth, $month] = explode(' ', $date->format('w j n'));

            if (!isset($months[$month])) {
                $months[$month] = [
                    'name' => $monthsTrans[$month - 1],
                    'repr' => $date->format('Y-m'),
                    'days' => [],
                ];
            }

            $months[$month]['days'][$dayInMonth] = $withRepr ?
                [
                    'name' => $daysShortTrans[$dayInWeek],
                    'repr' => $date->format('Y-m-d'),
                ]
                : $daysShortTrans[$dayInWeek];

            $date->modify('+1 day');
        }

        return array_values($months);
    }
}
