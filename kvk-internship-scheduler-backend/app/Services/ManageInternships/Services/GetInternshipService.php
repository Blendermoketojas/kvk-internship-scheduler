<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\RolePermissions;
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
        return [RolePermissions::PRODEKANAS];
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
            $internship->load('company');
        }

        // response
        return response()->json($internship);
    }
}
