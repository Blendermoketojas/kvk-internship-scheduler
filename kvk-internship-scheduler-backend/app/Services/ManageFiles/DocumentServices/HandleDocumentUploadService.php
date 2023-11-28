<?php

namespace App\Services\ManageFiles\DocumentServices;

use App\Models\Document;
use App\Models\File;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class HandleDocumentUploadService extends BaseService
{

    public function rules(): array
    {
        return [
            'files' => 'required|array|max:'.env('FILE_MAX_COUNT', 5),
            'files.*' => 'required|file|max:'.env('FILE_MAX_SIZE', 25600),
            'action' => 'required|string|in:learning,internship',
        ];
    }

    public function data(): array
    {
        return [
            'file' => $this->request['file'],
            'action' => $this->request['action']
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

        // creating document record

        if (count($this->data()['files']) == 1) {
            $document = Document::create([

            ]);

            $file = File::create([

            ]);
        }

        // $path = $request->file('avatar')->store('avatars');

        // response
        return response()->json('Not implemented');
    }
}
