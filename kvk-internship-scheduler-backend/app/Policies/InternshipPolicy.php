<?php

namespace App\Policies;

use App\Contracts\Roles\Role;
use App\Models\Internship;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class InternshipPolicy
{
    use HandlesAuthorization;

    public function internshipGet(User $user, Internship $internship)
    {
        $user_id = $user->id;
        return $internship->userProfiles()->where('internship_user.user_id', $user_id)->exists();
    }

    public function internshipUpdate(User $user, Internship $internship)
    {
        $userProfile = $user->userProfile;
        return $internship->created_by == $user->id || $userProfile->role_id === Role::PRODEKANAS->value;
    }
}
