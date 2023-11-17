<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    protected $table = 'company';

    protected $fillable = [
        'company_name',
    ];

    public function internships() : HasMany
    {
        return $this->hasMany(Internship::class);
    }

    public function userProfile() : HasMany {
        return $this->hasMany(UserProfile::class);
    }
}
