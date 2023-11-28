<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function getRestrictsStudentGroup() {

    }

}
