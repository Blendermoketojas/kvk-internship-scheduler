<?php

namespace App\Services\ManageResults\Forms\Results;

use App\Contracts\Roles\RolePermissions;
use App\Models\FormTemplate;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateTemplateResultService extends BaseService
{
    public function rules(): array
    {
        return [
            'template_id' => 'required|number',
            'internship_id' => 'required|number',
            'answers' => 'required|array'
        ];
    }

    public function data(): array
    {
        return [
            'template_id' => $this->request['template_id'],
            'internship_id' => $this->request['internship_id'],
            'answers' => $this->request['answers']
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
        $pivot = Internship::find($this->data()['internship_id'])->templates()->wherePivot(['template_id' => $this->data()['template_id']]);





        // response
        return response()->json($pivot);
    }
}
