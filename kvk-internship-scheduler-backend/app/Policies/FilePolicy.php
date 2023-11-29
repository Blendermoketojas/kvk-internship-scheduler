<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\File;
use App\Models\LearningMaterial;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FilePolicy
{
    use HandlesAuthorization;

    public function fileDelete(User $user, File $file)
    {
        return $user->id === $file->created_by;
    }
}
