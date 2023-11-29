<?php

namespace App\Services\ManageResults;

use App\Contracts\Roles\RolePermissions;
use App\Models\FormQuestion;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SearchQuestionItemsService extends BaseService
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
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        $query = FormQuestion::whereRaw('LOWER(question) LIKE ?', ['%' . strtolower($this->data()['fullName']) . '%'])
            ->get();

        // response
        return response()->json($query);
    }
}
