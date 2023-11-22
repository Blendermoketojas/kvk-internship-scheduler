<?php

namespace App\Services\ManageAuth\Base;

use App\Services\BaseService;
use Illuminate\Http\Request;

class AuthService extends BaseService
{

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    function execute() {}
}
