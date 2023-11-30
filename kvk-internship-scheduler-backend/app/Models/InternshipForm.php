<?php

namespace App\Models;
use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternshipForm extends Model
{
    use AutoCreatedBy;

    protected $table = 'internship_form';

    protected $fillable = [
        'template_id',
        'internship_id',
        'sequence'
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
