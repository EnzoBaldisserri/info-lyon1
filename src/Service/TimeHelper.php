<?php

namespace App\Service;

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

    public function getPeriodMonths(Period $period, bool $withHash = false): Array
    {
        $monthsTrans = explode(',', $this->translator->trans('global.time.months'));
        $daysShortTrans = explode(',', $this->translator->trans('global.time.days_short'));

        $months = [];

        // Get current state from start date
        [$year, $month, $dayInMonth, $dayInWeek] = array_map(
            'intval',
            explode(' ', $period->getStart()->format('Y n j N'))
        );

        // Get end state from end date
        [$endYear, $endMonth, $endDay] = array_map(
            'intval',
            explode(' ', $period->getEnd()->format('Y n j'))
        );

        // Start processing months and days
        while ($year !== $endYear || $month !== $endMonth) {
            $days = [];
            $nbDayInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            while ($dayInMonth <= $nbDayInMonth) {
                $newDay = $withHash ?
                    [
                        'name' => $daysShortTrans[$dayInWeek],
                        'hash' => "$year-$month-$dayInMonth",
                    ]
                    : $daysShortTrans[$dayInWeek];

                $days[$dayInMonth] = $newDay;

                $dayInMonth += 1;
                $dayInWeek = ($dayInWeek + 1) % 7;
            }

            $newMonth = [
                'name' => $monthsTrans[$month - 1],
                'days' => $days,
            ];

            if ($withHash) {
                $newMonth['hash'] = "$year-$month";
            }

            $months[] = $newMonth;

            $month += 1;
            if ($month > 12) {
                $month = 1;
                $year += 1;
            }

            $dayInMonth = 1;
        }

        // Compute days for last month
        $days = [];

        while ($dayInMonth <= $endDay) {
            $newDay = $withHash ?
                [
                    'name' => $daysShortTrans[$dayInWeek],
                    'hash' => "$year-$month-$dayInMonth",
                ]
                : $daysShortTrans[$dayInWeek];

            $days[$dayInMonth] = $newDay;

            $dayInMonth += 1;
            $dayInWeek = ($dayInWeek + 1) % 7;
        }

        $newMonth = [
            'name' => $monthsTrans[$month - 1],
            'days' => $days,
        ];

        if ($withHash) {
            $newMonth['hash'] = "$year-$month";
        }

        $months[] = $newMonth;

        return $months;
    }
}
