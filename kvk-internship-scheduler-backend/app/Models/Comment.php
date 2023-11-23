<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use AutoCreatedBy;
    use HasFactory;

    protected $table = "comments";

    protected $fillable = [
        'user_id',
        'comment',
        'internship_id',
        'date_from',
        'date_to'
    ];

    protected $hidden = [
        'created_by'
    ];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_id');
    }
}
