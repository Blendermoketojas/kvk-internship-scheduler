<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageFiles\DeleteFileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FileController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function deleteFile(Request $request): JsonResponse
    {
        return (new DeleteFileService($request))->execute();
    }
}
