<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageFiles\InternshipDocumentServices\DeleteInternshipDocumentService;
use App\Services\ManageFiles\InternshipDocumentServices\DownloadInternshipDocumentService;
use App\Services\ManageFiles\InternshipDocumentServices\GetAllUserInternshipDocumentsService;
use App\Services\ManageFiles\InternshipDocumentServices\GetDocumentsByInternshipIdService;
use App\Services\ManageFiles\InternshipDocumentServices\GetInternshipDocumentsService;
use App\Services\ManageFiles\InternshipDocumentServices\GetInternshipDocumentsWithFilesService;
use App\Services\ManageFiles\InternshipDocumentServices\HandleInternshipDocumentUploadService;
use App\Services\ManageFiles\InternshipDocumentServices\UpdateInternshipDocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    /**
     * @throws ValidationException
     */
    public function getAllUserInternshipDocuments(Request $request): JsonResponse
    {
        return (new GetAllUserInternshipDocumentsService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getInternshipDocumentsWithFiles(Request $request): JsonResponse
    {
        return (new GetInternshipDocumentsWithFilesService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function downloadInternshipDocument(Request $request): StreamedResponse
    {
        return (new DownloadInternshipDocumentService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getDocumentsByInternshipId(Request $request): JsonResponse
    {
        return (new GetDocumentsByInternshipIdService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getInternshipDocuments(Request $request): JsonResponse
    {
        return (new GetInternshipDocumentsService($request))->execute();
    }
}
