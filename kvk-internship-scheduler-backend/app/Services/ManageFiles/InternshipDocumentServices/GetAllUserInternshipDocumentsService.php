<?php

namespace App\Services\ManageFiles\InternshipDocumentServices;

use App\Contracts\Roles\RolePermissions;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class GetAllUserInternshipDocumentsService extends BaseService
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

        // logic execution

//        $internships = $this->user->internships()->pluck('id');

//        if (Gate::denies('internshipGet', $internship)) {
//            return response()->json('User must belong to the internship or be PRODEKANAS to perform this task',
//                401);
//        }

        $internshipsWithDocuments = $this->user->internships()
            ->with('documents.files')->get();

        // response
        return response()->json($internshipsWithDocuments);
    }
}
