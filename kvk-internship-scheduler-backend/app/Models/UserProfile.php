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
        'fullname',
        'role',
        'image_path',
        'description',
        'country',
        'address'
    ];

    protected $hidden = [
        'role_id',
        'student_group_id'
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

    public function studentGroup() : BelongsTo
    {
        return $this->belongsTo(StudentGroup::class);
    }

    public function comments() : HasMany {
        return $this->hasMany(Comment::class);
    }
}
