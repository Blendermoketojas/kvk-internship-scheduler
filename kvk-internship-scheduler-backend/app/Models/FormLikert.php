<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormLikert extends Model
{
    use HasFactory;
    use AutoCreatedBy;

    protected $table = 'form_likert';

    protected $fillable = [
        'answer'
    ];

    public function answersQuestions(): BelongsToMany
    {
        return $this->belongsToMany(FormQuestion::class, 'form_question',
        'answer_id', 'question_id');
    }

    public function templatesLikerts(): BelongsToMany
    {
        return $this->belongsToMany(FormTemplate::class, 'template_likert',
        'likert_id', 'template_id');
    }
}
