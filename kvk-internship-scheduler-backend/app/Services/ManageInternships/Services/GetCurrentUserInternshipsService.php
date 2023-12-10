<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\Role;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetCurrentUserInternshipsService extends BaseService
{
    public function rules(): array
    {
        return [];
    }

    public function data(): array
    {
        return [];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

//        // logic execution
//
//        if ($this->user->role_id === Role::PRODEKANAS->value)
//        {
//            // TODO: CHECK IF THIS IS USED ANYWHERE
//            return response()->json(Internship::all()->load('company'));
//        }

        $internships = $this->user->internships;
        $internships->load('company');

        // response
        return response()->json(['internships' => $internships, 'user' => $this->user]);
    }
}
