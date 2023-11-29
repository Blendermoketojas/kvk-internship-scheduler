<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    use AutoCreatedBy;

    protected $fillable = [
        'file_name',
        'file_path',
        'fileable_id',
        'fileable_type',
        'created_by'
    ];

    protected $hidden = [
      'fileable_id',
      'fileable_type',
      'file_path'
    ];

    public function fileable()
    {
        return $this->morphTo();
    }

}
