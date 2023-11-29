<?php

namespace App\Policies;

use App\Models\LearningMaterial;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class LearningMaterialPolicy
{
    use HandlesAuthorization;

    public function fileCreateLearningMaterials(User $user, LearningMaterial $learningMaterial) {
        return $user->id === $learningMaterial->created_by;
    }
}
