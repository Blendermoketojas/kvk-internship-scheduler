<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;
    use AutoCreatedBy;

    public $timestamps = true;

    protected $table = 'internships';

    protected $fillable = [
        'user_id',
        'company_id',
        'date_from',
        'date_to',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function userProfiles(): BelongsToMany
    {
        return $this->BelongsToMany(UserProfile::class, 'internship_user',
            'internship_id', 'user_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function company() : BelongsTo {
        return $this->belongsTo(Company::class);
    }

    public function templates() : BelongsToMany {
        return $this->belongsToMany(FormTemplate::class, 'internship_form',
        'internship_id', 'template_id');
    }
}
