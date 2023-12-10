<?php

namespace App\Services\ManageFiles\InternshipDocumentServices;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\Document;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetDocumentsByInternshipIdService extends BaseService
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
        return [Role::PRODEKANAS];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        // TODO: NEED TO VERIFY POLICY FOR LEGITIMATE DOCUMENT RETRIEVAL

//        $internships = $this->user->internships()->pluck('id');

//        if (Gate::denies('internshipGet', $internship)) {
//            return response()->json('User must belong to the internship or be PRODEKANAS to perform this task',
//                401);
//        }

        $internship = Internship::find($this->data()['internshipId']);
        $internshipsDocuments = $internship->documents;

        // response
        return response()->json($internshipsDocuments);
    }
}
