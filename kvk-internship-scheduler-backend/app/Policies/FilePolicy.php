<?php

namespace App\Policies;

use App\Models\File;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class FilePolicy
{
    use HandlesAuthorization;

    public function fileDelete(User $user, File $file)
    {
        return $user->id === $file->created_by;
    }
}
