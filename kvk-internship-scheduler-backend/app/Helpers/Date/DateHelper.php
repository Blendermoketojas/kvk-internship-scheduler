<?php

namespace App\Helpers\Date;

use Carbon\Carbon;
use App\Exceptions\DateNotInRangeException;
use Exception;


class DateHelper
{
    /**
     * Check if a given set of dates are all between two specific dates.
     *
     * @param array $dates Array of dates to check.
     * @param string $startDate The start date.
     * @param string $endDate The end date.
     * @return void
     * @throws Exception If any date is not between the start and end dates.
     * @throws DateNotInRangeException
     */
    static function ensureDatesAreBetween(array $dates, $startDate, $endDate): void
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        foreach ($dates as $date) {
            $carbonDate = Carbon::parse($date);
            if (!$carbonDate->between($startDate, $endDate)) {
                throw new DateNotInRangeException($date, $startDate, $endDate);
            }
        }
    }
}
