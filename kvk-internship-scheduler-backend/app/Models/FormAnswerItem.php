<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormAnswerItem extends Model
{
    use HasFactory;

    protected $table ='form_answer_item';

    protected $fillable = [
        'question_id',
        'answer_id'
    ];
    public function question(): BelongsTo
    {
        return $this->belongsTo(FormQuestion::class);
    }
    public function answer(): BelongsTo
    {
        return $this->belongsTo(FormLikert::class);
    }
}