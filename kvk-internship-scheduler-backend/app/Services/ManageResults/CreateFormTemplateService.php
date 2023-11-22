<?php

namespace App\Services;

use App\Contracts\Roles\RolePermissions;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateFormTemplateService extends BaseService
{
    public function rules(): array
    {
        return [];
    }

    public function data(): array
    {
        return [];
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
        $validation = $this->validateRules();
        if (!is_bool($validation)) {
            return $validation;
        }

        // logic execution

        // response
        return response()->json('Not implemented');
    }
}