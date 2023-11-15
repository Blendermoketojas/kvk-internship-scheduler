<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table ='user_profile';

    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'image_path',
        'description'
    ];
    public function internships(): HasMany
    {
        return $this->hasMany(Internship::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
