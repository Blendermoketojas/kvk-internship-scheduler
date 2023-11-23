<?php

namespace App\Models;
use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TemplateQuestion extends Model
{
    use AutoCreatedBy;

    protected $table = 'template_question';

    protected $fillable = [
        'template_id',
        'question_id',
        'sequence'
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(FormQuestion::class);
    }
}
