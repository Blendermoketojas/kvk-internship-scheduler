<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\Role;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetInternshipService extends BaseService
{
    public function rules(): array
    {
        return ['internshipId' => 'required|integer'];
    }

    public function data(): array
    {
        return [
            'internshipId' => $this->request['internshipId']
        ];
    }

    public function permissions(): array
    {
        // TODO: TEMPORARY SOLUTION (SECURITY RISK) CHECK IF MENTORIUS IR PRAKTIKOS_VADOVAS BELONGS TO THE INTERNSHIP
        return [Role::PRODEKANAS, Role::MENTORIUS, Role::PRAKTIKOS_VADOVAS];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);


        // logic execution

        if($internship = Internship::find($this->data())) {
            $internship->load('templates');
            $internship->load('company');
            $internship->load('userProfiles');
        }

        // response
        return response()->json($internship);
    }
}
