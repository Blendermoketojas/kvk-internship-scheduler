<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormLikert extends Model
{
    use HasFactory;
    use AutoCreatedBy;

    protected $table = 'form_likert';

    protected $fillable = [
        'question'
    ];

    public function answerItems(): HasMany
    {
        return $this->hasMany(FormAnswerItem::class);
    }

    public function templates(): HasMany
    {
        return $this->hasMany(TemplateLikert::class);
    }
}
