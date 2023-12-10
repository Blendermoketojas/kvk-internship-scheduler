<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use App\Traits\HandleInternshipHours;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Internship extends Model
{
    use HasFactory;
    use AutoCreatedBy;
    use HandleInternshipHours;

    public $timestamps = true;

    protected $table = 'internships';

    protected $fillable = [
        'title',
        'user_id',
        'company_id',
        'date_from',
        'date_to',
        'is_active',
        'duration_in_hours',
        'logged_hours',
        'is_mentor_evaluated',
        'is_head_of_internship_evaluated',
        'is_self_evaluated'
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
        'internship_id', 'template_id')->withPivot('id');
    }

    public function grades(): HasMany {
        return $this->hasMany(GradeItem::class);
    }
}
