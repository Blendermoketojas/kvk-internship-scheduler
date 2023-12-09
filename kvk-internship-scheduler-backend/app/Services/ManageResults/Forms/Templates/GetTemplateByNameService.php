<?php

namespace App\Services\ManageResults\Forms\Templates;

use App\Contracts\Roles\RolePermissions;
use App\Models\FormTemplate;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetTemplateByNameService extends BaseService
{
    public function rules(): array
    {
        return [
            'name' => 'required|string'
        ];
    }

    public function data(): array
    {
        return [
            'name' => $this->request['name']
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
        $query = FormTemplate::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->data()['name']) . '%'])
            ->get();
        // response
        return response()->json($query);
    }
}
