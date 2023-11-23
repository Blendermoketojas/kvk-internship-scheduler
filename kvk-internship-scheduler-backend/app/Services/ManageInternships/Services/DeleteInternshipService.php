<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DeleteInternshipService extends BaseService
{
    public function rules(): array
    {
        return [
            'internship_id' => 'required|integer'
        ];
    }

    public function data(): array
    {
        return [
            'internship_id' => $this->request['internshipId']
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

        $internship = Internship::findOrFail($this->data()['internship_id']);
        $didDelete = $internship->delete();

        // response
        return response()->json(['success' => $didDelete]);
    }
}
