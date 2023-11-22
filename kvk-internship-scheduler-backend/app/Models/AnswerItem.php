<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class AnswerItem extends Model
{
    protected $table ='answer_item';

    protected $fillable = [
        'internship_form_id',
        'item_id'
    ];

    public function internshipForm(): BelongsTo
    {
        return $this->belongsTo(InternshipForm::class);
    }

    public function answerItem(): BelongsTo
    {
        return $this->belongsTo(FormAnswerItem::class);
    }
}
