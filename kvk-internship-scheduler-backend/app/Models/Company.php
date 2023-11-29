<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use AutoCreatedBy;
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'company_name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'created_by'
    ];

    public function internships(): HasMany
    {
        return $this->hasMany(Internship::class);
    }

    public function userProfile(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }
}
