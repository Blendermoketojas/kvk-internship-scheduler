<?php

namespace App\Traits;

use App\Models\Internship;

trait HandleInternshipHours
{
    public static function bootHandleInternshipHours()
    {
        static::updating(function ($model) {
            if ($model->logged_hours >= $model->duration_in_hours) {
                $model->is_active = false;
                $model->save();
            }
        });
    }
}
