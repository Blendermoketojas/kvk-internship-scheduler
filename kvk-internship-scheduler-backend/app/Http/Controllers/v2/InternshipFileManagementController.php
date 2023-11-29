<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageFiles\InternshipDocumentServices\DeleteInternshipDocumentService;
use App\Services\ManageFiles\InternshipDocumentServices\HandleInternshipDocumentUploadService;
use App\Services\ManageFiles\InternshipDocumentServices\UpdateInternshipDocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InternshipFileManagementController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function uploadDocumentWithFiles(Request $request): JsonResponse
    {
        return (new HandleInternshipDocumentUploadService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function deleteDocumentWithFiles(Request $request): JsonResponse
    {
        return (new DeleteInternshipDocumentService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function updateDocument(Request $request): JsonResponse
    {
        return (new UpdateInternshipDocumentService($request))->execute();
    }
}
