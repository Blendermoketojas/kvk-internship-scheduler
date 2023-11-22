<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
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

    public function templates(): HasMany
    {
        return $this->hasMany(TemplateQuestion::class);
    }

    public function formAnswerItems(): HasMany
    {
        return $this->hasMany(FormAnswerItem::class);
    }
}
