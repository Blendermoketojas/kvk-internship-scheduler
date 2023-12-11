<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use HasFactory;
    use AutoCreatedBy;

    protected $fillable = [
        'title',
        'description'
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function resourceLinks(): HasMany
    {
        return $this->hasMany(ResourceLink::class);
    }
}
