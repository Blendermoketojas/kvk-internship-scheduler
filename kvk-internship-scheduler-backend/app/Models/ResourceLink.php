<?php

namespace App\Models;

use App\Traits\AutoCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceLink extends Model
{
    // TODO: IMPLEMENT CRUD OF THIS MODEL. BELONGS TO FILES WHERE NOT ONLY FILES BUT LINKS CAN BE UPLOADED

    use HasFactory;
    use AutoCreatedBy;

    protected $fillable = [
        'documentId',
        'link',
        'description'
    ];

    public function document() : BelongsTo {
        return $this->belongsTo(Document::class);
    }
}
