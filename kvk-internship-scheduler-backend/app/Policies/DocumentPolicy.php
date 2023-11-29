<?php

namespace App\Policies;

use App\Models\Document;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function documentDelete(User $user, Document $document) {
        return $user->id === $document->created_by;
    }
}
