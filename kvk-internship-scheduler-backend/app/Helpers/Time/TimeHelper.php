<?php

namespace App\Helpers\Time;

use DateTime;

class TimeHelper
{
    /**
     * @throws \Exception
     */
    static function getHoursInFractionFromDate($date1, $date2)
    {
        $dateFrom = new DateTime($date1);
        $dateTo = new DateTime($date2);

        $interval = $dateFrom->diff($dateTo);

        return $interval->h + ($interval->i / 60) + ($interval->s / 3600);
    }
}
