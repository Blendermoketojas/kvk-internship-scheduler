<?php

namespace App\Services\ManageResults;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\FormQuestion;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SearchQuestionItemsService extends BaseService
{
    public function rules(): array
    {
        return ['question' => 'required|string'];
    }

    public function data(): array
    {
        return ['question' => $this->request['question']];
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

        $query = FormQuestion::whereRaw('LOWER(question) LIKE ?', ['%' . strtolower($this->data()['question']) . '%'])
            ->get();

        // response
        return response()->json($this->fixObjects($query));
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
