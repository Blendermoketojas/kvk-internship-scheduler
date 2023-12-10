<?php

namespace App\Models;
use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model
{
    use HasFactory;
    use AutoCreatedBy;

    protected $table = 'form_template';

    protected $fillable = [
        'name'
    ];

    public function templateQuestions(): BelongsToMany
    {
        return $this->belongsToMany(FormQuestion::class, 'template_question',
        'template_id', 'question_id');
    }

    public function templateLikerts(): BelongsToMany
    {
        return $this->belongsToMany(FormLikert::class, 'template_likert',
        'template_id', 'likert_id');
    }

    public function internshipForms(): HasMany
    {
        return $this->hasMany(TemplateLikert::class);
    }
}
