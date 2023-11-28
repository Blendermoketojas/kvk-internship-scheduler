<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
