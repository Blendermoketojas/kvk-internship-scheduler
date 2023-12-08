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

    static function doesTimeOverlap($newStartTime, $newEndTime)
    {
        $newStartTime = $newStartTime instanceof DateTime ? $newStartTime : new DateTime($newStartTime);
        $newEndTime = $newEndTime instanceof DateTime ? $newEndTime : new DateTime($newEndTime);

        $isOverlap = Comment::where(function ($query) use ($newStartTime, $newEndTime) {
            $query->where(function ($q) use ($newStartTime, $newEndTime) {
                $q->where('date_from', '<=', $newStartTime)
                    ->where('date_to', '>', $newStartTime);
            })
                ->orWhere(function ($q) use ($newStartTime, $newEndTime) {
                    $q->where('date_from', '<', $newEndTime)
                        ->where('date_to', '>=', $newEndTime);
                })
                ->orWhere(function ($q) use ($newStartTime, $newEndTime) {
                    $q->where('date_from', '>=', $newStartTime)
                        ->where('date_to', '<=', $newEndTime);
                });
        })->exists();

        return $isOverlap;
    }
}
