<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_title',
    ];

    public function userProfile() : HasMany
    {
        return $this->hasMany(UserProfile::class);
    }

    public function accessibleLearningMaterials(): MorphToMany
    {
        return $this->morphToMany(LearningMaterial::class, 'accessable', 'learning_materials_access');
    }
}
