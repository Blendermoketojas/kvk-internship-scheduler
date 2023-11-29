<?php

namespace App\Services\ManageFiles\InternshipDocumentServices;

use App\Models\Document;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class UpdateInternshipDocumentService extends BaseService
{
    public function rules(): array
    {
        return [
            'documentId' => 'required|integer|exists:documents,id',
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:2560',
            'visible' => 'required|boolean'
        ];
    }

    public function data(): array
    {
        return [
            'documentId' => $this->request['documentId'],
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

        // get model

        $document = Document::find($this->data()['documentId']);

        // verify policies

        if (Gate::denies('documentUpdate', $document)) return response()->json("Action not allowed",
            401);

        // logic execution

        $document->update(array_diff_key($this->data(), ['documentId' => '']));

        // response
        return response()->json($document);
    }
}
