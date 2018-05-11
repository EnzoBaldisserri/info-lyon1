<?php

namespace App\Service;

use Symfony\Component\Translation\TranslatorInterface;
use App\Entity\Period;

class TimeHelper
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getPeriodMonths(Period $period, bool $withHash = false): Array
    {
        $MONTHS = explode(',', $this->translator->trans('global.time.months'));
        $DAYS_SHORT = explode(',', $this->translator->trans('global.time.days_short'));

        $months = [];
        $startDate = $period->getStart();
        $endDate = $period->getEnd();

        [$startYear, $startMonth, $startDay] = array_map(
            'intval',
            explode('-', $startDate->format('Y-n-j'))
        );

        $dayInWeek = (int) $startDate->format('N');

        [$endYear, $endMonth, $endDay] = array_map(
            'intval',
            explode('-', $endDate->format('Y-n-j'))
        );

        $dayInMonth = $startDay;
        $month = $startMonth;
        $year = $startYear;

        $nbDayInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        while ($year !== $endYear || $month !== $endMonth) {
            $days = [];

            while ($dayInMonth <= $nbDayInMonth) {
                $newDay = $withHash ?
                    [
                        'name' => $DAYS_SHORT[$dayInWeek],
                        'hash' => md5(serialize([$year, $month, $dayInWeek])),
                    ]
                    : $DAYS_SHORT[$dayInWeek];

                $days[$dayInMonth] = $newDay;

                $dayInMonth += 1;
                $dayInWeek = ($dayInWeek + 1) % 7;
            }

            $newMonth = [
                'name' => $MONTHS[$month - 1],
                'days' => $days,
            ];

            if ($withHash) {
                $newMonth['hash'] = md5(serialize([$year, $month]));
            }

            $months[] = $newMonth;

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
            $newDay = $withHash ?
                [
                    'name' => $DAYS_SHORT[$dayInWeek],
                    'hash' => md5(serialize([$year, $month, $dayInWeek])),
                ]
                : $DAYS_SHORT[$dayInWeek];

            $days[$dayInMonth] = $newDay;

            $dayInMonth += 1;
            $dayInWeek = ($dayInWeek + 1) % 7;
        }

        $newMonth = [
            'name' => $MONTHS[$month - 1],
            'days' => $days,
        ];

        if ($withHash) {
            $newMonth['hash'] = md5(serialize([$year, $month]));
        }

        $months[] = $newMonth;

        return $months;
    }
}
