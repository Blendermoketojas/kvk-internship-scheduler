<?php

namespace App\Helpers\Time;

use App\Models\Comment;
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

        // Calculate total hours including days
        $hours = $interval->days * 24 + $interval->h + ($interval->i / 60) + ($interval->s / 3600);

        return $hours;
    }

    static function doesTimeOverlap($internshipId, $newStartTime, $newEndTime)
    {
        $newStartTime = $newStartTime instanceof DateTime ? $newStartTime : new DateTime($newStartTime);
        $newEndTime = $newEndTime instanceof DateTime ? $newEndTime : new DateTime($newEndTime);

        $doesOverlap = Comment::where('internship_id', $internshipId)
            ->where(function ($query) use ($newStartTime, $newEndTime) {
                $query->where('date_from', '<', $newEndTime)
                    ->where('date_to', '>', $newStartTime);
            })->exists();

        return $doesOverlap;
    }
}
