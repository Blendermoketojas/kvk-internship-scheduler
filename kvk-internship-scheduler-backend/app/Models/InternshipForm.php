<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class InternshipForm extends Model
{
    protected $table ='internship_form';

    protected $fillable = [
        'template_id',
        'answer_id',
        'internship_id'
    ];
    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
    public function template(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class);
    }
    public function answers(): HasMany
    {
        return $this->hasMany(AnswerItem::class);
    }
}
