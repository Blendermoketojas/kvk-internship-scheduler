<?php

namespace App\Services\ManageFiles\InternshipDocumentServices;

use App\Contracts\Roles\RolePermissions;
use App\Models\Document;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetInternshipDocumentsWithFilesService extends BaseService
{
    public function rules(): array
    {
        return [
            'documentId' => 'required|integer|exists:documents,id'
        ];
    }

    public function data(): array
    {
        return [
            'documentId' => $this->request['documentId']
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

        // TODO: IMPLEMENT POLICY VERIFICATION FOR DOCUMENT RETRIEVAL

        $document = Document::with('files')->find($this->data()['documentId']);

        // response
        return response()->json($document);
    }
}
