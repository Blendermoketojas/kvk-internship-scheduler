<?php

namespace App\Services\ManageFiles;

use App\Contracts\Files\FileType;
use App\Helpers\File\FileHelper;
use App\Models\Document;
use App\Models\LearningMaterial;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CreateFilesService extends BaseService
{
    public function rules(): array
    {
        return [
            'activityName' => 'required|string|in:Internships,LearningMaterials',
            'activityId' => 'required|integer',
            'files' => 'required|array|max:' . env('FILE_MAX_COUNT', 5),
            'files.*' => 'required|file|max:' . env('FILE_MAX_SIZE', 25600),
        ];
    }
    public function data(): array
    {
        return [
            'activityName' => $this->request['activityName'],
            // activityId = documentId or learningMaterialId
            'activityId' => $this->request['activityId'],
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
    function execute(): JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $fileable = null;
        switch ($this->data()['activityName']) {
            case FileType::Internships->value:
                $fileable = Document::find($this->data()['activityId']);
                if (Gate::denies('fileCreateDocuments', $fileable)) {
                    return response()
                        ->json("Document must belong to the user to perform this action", 401);
                }
                break;
            case FileType::LearningMaterials->value:
                $fileable = LearningMaterial::find($this->data()['activityId']);
                if (Gate::denies('fileCreateLearningMaterials', $fileable)) return response()
                    ->json("Learning material must belong
                    to the user to perform this action", 401);
                break;
        }

        $uploadedFiles = $this->request->file('files');
        $fileRecords = [];
        $createdFiles = [];

        foreach ($uploadedFiles as $uploadedFile) {
            $fileName = $uploadedFile->getClientOriginalName();
            $filePathToBeStored = $this->getPath($fileable->id) . '/' . $fileName;

            if (Storage::exists($filePathToBeStored)) {
                return response()->json(['files_created_before_error' => $createdFiles,
                    'error' => "File '$fileName' already exists",
                    'success' => false], 400);
            }

            $filePath = $uploadedFile->storeAs($this->getPath($fileable->id), $fileName);

            $createdFiles[] = $fileName;

            $fileRecords[] = [
                'file_name' => $fileName,
                'file_path' => $filePath,
                'fileable_id' => $fileable->id,
                'fileable_type' => get_class($fileable),
            ];
        }

        // bulk insert the file records
        $fileable->files()->createMany($fileRecords);

        // response
        return response()->json(['files' => $fileable->files]);
    }

    private function getPath($id)
    {
        return strtolower($this->data()['activityName']) . "/" . $id;
    }
}
