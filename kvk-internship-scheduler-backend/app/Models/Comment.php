<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
    protected $table = "comments";
    use HasFactory;

    public function internship() : HasOne
    {
        return $this->hasOne(Internship::class);
    }
}
