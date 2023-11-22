<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AutoCreatedBy
{
    public static function bootAutoCreatedBy()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });
    }
}
