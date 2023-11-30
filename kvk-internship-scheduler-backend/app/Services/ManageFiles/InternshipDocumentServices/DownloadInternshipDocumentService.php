<?php

namespace App\Services\ManageFiles\InternshipDocumentServices;

use App\Contracts\Roles\RolePermissions;
use App\Models\File;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadInternshipDocumentService extends BaseService
{
    public function rules(): array
    {
        return [
            'fileId' => 'required|integer|exists:files,id'
        ];
    }

    public function data(): array
    {
        return [
            'fileId' => $this->request['fileId']
        ];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    function execute() : StreamedResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        // TODO: NEED TO VERIFY POLICY FOR LEGITIMATE FILE DOWNLOAD

        $file = File::find($this->data()['fileId']);

        // response
        return Storage::download($file->file_path);
    }
}
