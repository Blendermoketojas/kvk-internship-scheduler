<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentGroup extends Model
{
    use HasFactory;
    use AutoCreatedBy;

    protected $fillable = [
        'group_identifier',
        'field_of_study'
    ];

    public function userProfiles() : HasMany {
        return $this->hasMany(UserProfile::class);
    }

    public function accessibleLearningMaterials()
    {
        return $this->morphToMany(LearningMaterial::class, 'accessable', 'learning_materials_access');
    }
}
