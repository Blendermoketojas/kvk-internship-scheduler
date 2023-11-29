<?php

namespace App\Services\ManageFiles\InternshipDocumentServices;

use App\Models\Document;
use App\Models\File;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DeleteInternshipDocumentService extends BaseService
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

        $document = Document::find($this->data()['documentId']);

        if (Gate::denies('documentDelete', $document)) {
            return response()->json(['error' => 'User must own this document group',
                'success' => false], 401);
        }

        // wipe every file in document group
        $files = $document->files;

        foreach ($files as $file)
        {
            Storage::delete($file->file_path);
        }

        // wipe the files
        $fileIds = $document->files->pluck('id')->toArray();
        File::whereIn('id', $fileIds)->delete();

        // delete the document itself
        $document->delete();

        // response
        return response()->json(['message' => 'deletion successful'], 200);
    }
}
