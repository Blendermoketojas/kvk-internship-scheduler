<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'internship';
    protected $fillable = [
        'user_id',
        'company_id',
        'date_from',
        'date_to',
        'is_active'
    ];

    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
