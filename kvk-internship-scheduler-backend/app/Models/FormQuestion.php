<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormQuestion extends Model
{
    use HasFactory;
    use AutoCreatedBy;

    protected $table = 'form_question';

    protected $fillable = [
        'question'
    ];

    public function templatesQuestions(): BelongsToMany
    {
        return $this->belongsToMany(FormTemplate::class, 'template_question',
        'question_id', 'template_id');
    }

    public function questionsLikerts(): BelongsToMany
    {
        return $this->belongsToMany(FormQuestion::class, 'form_answer_item',
        'answer_id', 'question_id');
    }
}
