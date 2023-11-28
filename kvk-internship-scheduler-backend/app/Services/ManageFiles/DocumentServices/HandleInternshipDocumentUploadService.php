<?php

namespace App\Services\ManageFiles\DocumentServices;

use App\Contracts\Roles\RolePermissions;
use App\Models\Document;
use App\Models\File;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class HandleInternshipDocumentUploadService extends BaseService
{

    public function rules(): array
    {
        return [
            'internshipId' => 'required|integer|exists:internships,id',
            'files' => 'required|array|max:'.env('FILE_MAX_COUNT', 5),
            'files.*' => 'required|file|max:'.env('FILE_MAX_SIZE', 25600),
            'title' => 'string|max:100',
            'description' => 'string|max:2560',
            'visible' => 'boolean'
        ];
    }

    public function data(): array
    {
        return [
            'internshipId' => $this->request['internshipId'],
            'files' => $this->request['files'],
            'title' => $this->request['title'],
            'description' => $this->request['description'],
            'visible' => $this->request['visible']
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

        // retrieve model

        $internship = Internship::find($this->data()['internshipId']);

        // check policy

        if($this->user->role_id !== RolePermissions::PRODEKANAS->value ||
            Gate::denies('restrictGet', $internship)) {
            return response()->json('User must belong to the internship or be PRODEKANAS to perform this task',
                401);
        }

        // logic execution

        // creating document record

        $documentData = array_diff_key($this->data(), ['files' => '', 'internshipId' => '']);
        $document = Document::create($documentData);

        $uploadedFiles = $this->request->file('files');
        $fileRecords = [];

        foreach ($uploadedFiles as $uploadedFile) {
            $fileName = $uploadedFile->getClientOriginalName();
            $filePath = $uploadedFile->storeAs($this->getPath($internship->id), $fileName);

            $fileRecords[] = [
                'file_name' => $fileName,
                'file_path' => $filePath,
                'fileable_id' => $document->id,
                'fileable_type' => get_class($document),
            ];
        }

        // Bulk insert the file records
        $document->files()->createMany($fileRecords);

        // response
        return response()->json($document->files);
    }

    private function getPath($internshipId)
    {
        return "internship/$internshipId/files";
    }

    private function handleCreation() {
        $document = Document::create([

        ]);

        $file = File::create([

        ]);

        return ['document' => $document,
            'file' => $file];
    }
}
