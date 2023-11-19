<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_identifier',
        'field_of_study'
    ];

    public function userProfiles() : HasMany {
        return $this->hasMany(UserProfile::class);
    }

}
