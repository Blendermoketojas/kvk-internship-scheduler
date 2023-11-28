<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    use HasFactory;

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function userProfiles()
    {
        return $this->morphedByMany(UserProfile::class, 'accessable', 'learning_materials_access');
    }

    public function studentGroups()
    {
        return $this->morphedByMany(StudentGroup::class, 'accessable', 'learning_materials_access');
    }

    public function roles()
    {
        return $this->morphedByMany(Role::class, 'accessable', 'learning_materials_access');
    }
}
