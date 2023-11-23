<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class AnswerItem extends Model
{
    use AutoCreatedBy;

    protected $table = 'answer_item';
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
