<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table ='user_profile';

    protected $fillable = [
        'user_id',
        'company_id',
        'first_name',
        'last_name',
        'role',
        'image_path',
        'description',
        'country',
        'address'
    ];
    public function internships(): HasMany
    {
        return $this->hasMany(Internship::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
