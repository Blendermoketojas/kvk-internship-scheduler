<?php

namespace App\Models;
use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TemplateLikert extends Model
{
    use AutoCreatedBy;

    protected $table = 'template_likert';

    protected $fillable = [
        'template_id',
        'likert_id',
        'sequence'
    ];
    public function template(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class);
    }
    public function likert(): BelongsTo
    {
        return $this->belongsTo(FormLikert::class);
    }
}
