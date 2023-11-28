<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FormAnswerItem extends Model
{
    use AutoCreatedBy;
    use HasFactory;

    protected $table = 'form_answer_item';

    protected $fillable = [
        'question_id',
        'answer_id'
    ];

    public function formAnswers(): BelongsToMany
    {
        return $this->belongsToMany(InternshipForm::class, 'answer_item',
        'item_id', 'internship_form_id');
    }
}
