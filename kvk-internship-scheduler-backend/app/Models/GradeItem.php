<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeItem extends Model
{
    use HasFactory;
    use AutoCreatedBy;

    protected $table = 'grade_item';

    protected $fillable = [
        'grade',
        'comment',
        'date',
        'internship_id',
        'is_final'
    ];

    public function internship(): BelongsTo {
        return $this->belongsTo(Internship::class);
    }
}
