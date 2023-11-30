<?php

namespace App\Services\ManageFiles\InternshipDocumentServices;

use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetInternshipDocumentsService extends BaseService
{
    public function rules(): array
    {
        return [
            'internshipId' => 'required|integer|exists:internships,id'
        ];
    }

    public function data(): array
    {
        return [
            'internshipId' => $this->request['internshipId']
        ];
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

        // logic execution

        $internship = Internship::find($this->data()['internshipId']);
        $documents = $internship->documents;

        // response
        return response()->json($documents);
    }
}
