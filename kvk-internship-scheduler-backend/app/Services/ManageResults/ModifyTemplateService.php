<?php

namespace App\Services;

use App\Contracts\Roles\RolePermissions;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ModifyTemplateService extends BaseService
{
    public function rules(): array
    {
        return ['template_name' => 'required|string',
            'questions' => 'required|array',
            'answers' => 'required|array'];
    }

    public function data(): array
    {
        return ['template_id' => $this->request['template_id'],
            'template_name' => $this->request['template_name'],
            'questions' => $this->request['questions'],
            'answers' => $this->request['answers']
            ];
    }

    public function permissions(): array
    {
        return []; //Ka cia deti as nzn, kas turetu tureti galimybe kurti/modifikuoti formas?
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution
        return $this->operate();
        // response
        return response()->json('Not implemented');
    }

    private function operate(): JsonResponse {
        return response()->json($this->data()['questions']);
    }
}
