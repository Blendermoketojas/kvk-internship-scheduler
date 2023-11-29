<?php

namespace App\Services\ManageFiles;

use App\Contracts\Files\FileType;
use App\Models\Document;
use App\Models\LearningMaterial;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CreateFilesService extends BaseService
{
    public function rules(): array
    {
        return [
            'activityName' => 'required|string|matches:Internship,LearningMaterials',
            'activityId' => 'required|integer',
            'files' => 'required|array|max:' . env('FILE_MAX_COUNT', 5),
            'files.*' => 'required|file|max:' . env('FILE_MAX_SIZE', 25600),
        ];
    }

    public function data(): array
    {
        return [
            'activityName' =>  $this->request['activityName'],
            'activityId' =>  $this->request['activityId'],
            'files' => $this->request['files'],
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

        $fileable = null;
        switch ($this->request['activityName']) {
            case FileType::Internship:
                $fileable = Document::find($this->request['activityId']);
                break;
            case FileType::LearningMaterials:
                $fileable = LearningMaterial::find($this->request['activityId']);
        }

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

        // Bulk insert the file records
        $document->files()->createMany($fileRecords);

        // response
        return response()->json(['document' => $document, 'files' => $document->files]);

        // response
        return response()->json('Not implemented');
    }
}
