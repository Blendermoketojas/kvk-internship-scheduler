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
        'company',
        'user_id',
    ];

    public function internships() : HasMany
    {
        return $this->hasMany(Internship::class);
    }
}
