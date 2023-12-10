<?php

namespace App\Services\ManageResults\Forms\Templates;

use App\Contracts\Roles\RolePermissions;
use App\Models\FormTemplate;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetTemplateService extends BaseService
{
    public function rules(): array
    {
        return ['id' => 'required|integer'];
    }

    public function data(): array
    {
        return ['id' => $this->request['id']];
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

        $formTemplate = FormTemplate::find($this->request['id']);
        $answers = $formTemplate->templateLikerts()->get();
        $questions = $formTemplate->templateQuestions()->get();

        $response['id'] = $formTemplate['id'];
        $response['name'] = $formTemplate['name'];
        $response['questions'] = $this->fixObjects($questions);
        $response['answers'] = $this->fixObjects($answers);

        return response()->json($response);

        // response
        return response()->json('Not implemented');
    }

    private function fixObjects($array) {
        return collect($array)->transform(function ($item) {
            unset($item['created_by']);
            unset($item['updated_at']);
            unset($item['created_at']);
            unset($item['pivot']);
            return $item;
        })->all();
    }
}
