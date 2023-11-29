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
        return $internship->userProfiles()->where('internship_user.user_id', $user_id)->exists();
    }
}
