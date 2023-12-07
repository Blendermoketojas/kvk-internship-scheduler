<?php

namespace App\Services\ManageFiles\InternshipDocumentServices;

use App\Contracts\Roles\Role;
use App\Models\Document;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class HandleInternshipDocumentUploadService extends BaseService
{

    public function rules(): array
    {
        return [
            'internshipId' => 'required|integer|exists:internships,id',
            'files.*' => 'required|file|max:' . env('FILE_MAX_SIZE', 25600),
            'title' => 'required|string|max:100',
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
            'visible' => $this->request['visible'] ?? true
        ];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    function execute(): JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // retrieve model

        $internship = Internship::find($this->data()['internshipId']);

        // check policy

        if ($this->user->role_id !== Role::PRODEKANAS->value) {
            if (Gate::denies('internshipGet', $internship)) {
                return response()->json('User must belong to the internship or be PRODEKANAS to perform this task',
                    401);
            }
        }

        // logic execution

        // creating document record

        $documentData = array_diff_key($this->data(), ['files' => '', 'internshipId' => '']);
        $document = $internship->documents()->create($documentData);

        $uploadedFiles = $this->request->file('files');
        $fileRecords = [];
        $createdFiles = [];

        foreach ($uploadedFiles as $uploadedFile) {
            $fileName = $uploadedFile->getClientOriginalName();
            $filePathToBeStored = $this->getPath($internship->id) . '/' . $fileName;

            if (Storage::exists($filePathToBeStored)) {
                return response()->json(['files_created_before_error' => $createdFiles,
                    'error' => "File '$fileName' already exists",
                    'success' => false], 400);
            }

            $filePath = $uploadedFile->storeAs($this->getPath($internship->id), $fileName);

            $createdFiles[] = $fileName;

            $fileRecords[] = [
                'file_name' => $fileName,
                'file_path' => $filePath,
                'fileable_id' => $document->id,
                'fileable_type' => get_class($document),
            ];
        }

        // bulk insert the file records
        $document->files()->createMany($fileRecords);

        // response
        return response()->json(['document' => $document, 'files' => $document->files]);
    }

    private function getPath($internshipId)
    {
        return "internships/$internshipId";
    }
}
