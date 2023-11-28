<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageFiles\DocumentServices\HandleInternshipDocumentUploadService;
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
}
