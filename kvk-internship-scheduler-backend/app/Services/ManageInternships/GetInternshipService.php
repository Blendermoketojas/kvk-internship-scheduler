<?php

namespace App\Services\ManageInternships;

use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
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

        $validation = $this->validateRules();
        if (!is_bool($validation)) {
            return $validation;
        }

        // logic execution

        if($internship = Internship::find($this->data())) {
            $internship->load('company');
        }

        // response
        return response()->json($internship);
    }
}
