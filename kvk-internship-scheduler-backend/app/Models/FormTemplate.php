<?php

namespace App\Models;
use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model
{
    use HasFactory;
    use AutoCreatedBy;

    protected $table = 'form_template';

    protected $fillable = [

    ];

    public function templateQuestions(): HasMany
    {
        return $this->hasMany(TemplateQuestion::class);
    }

    public function templateLikerts(): HasMany
    {
        return $this->hasMany(TemplateLikert::class);
    }

    public function internshipForms(): HasMany
    {
        return $this->hasMany(TemplateLikert::class);
    }
}
