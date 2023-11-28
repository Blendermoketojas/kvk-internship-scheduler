<?php

namespace App\Policies;

use App\Models\Internship;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class InternshipPolicy
{
    use HandlesAuthorization;

    public function restrictGet(User $user, Internship $internship)
    {
        $user_id = $user->id;
        return Internship::whereHas('users', function ($query) use ($user_id) {
            $query->where('id', $user_id);
        })->where('id', $internship->id)->exists();
    }
}
