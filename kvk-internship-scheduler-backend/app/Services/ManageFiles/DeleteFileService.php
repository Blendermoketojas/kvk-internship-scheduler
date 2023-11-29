<?php

namespace App\Services\ManageFiles;

use App\Models\File;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DeleteFileService extends BaseService
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
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $file = File::find($this->data()['fileId']);

        // check policy

        if (Gate::denies('fileDelete', $file)) {
            return response()->json(['error' => 'User must be the owner of the file to perform this task'],
                401);
        }

        // delete from storage
        Storage::delete($file->file_path);

        // delete from database
        $file->delete();

        // response
        return response()->json(['message' => 'file deletion successful', 'success' => true]);
    }
}
