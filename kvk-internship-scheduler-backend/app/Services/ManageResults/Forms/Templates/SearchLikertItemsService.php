<?php

namespace App\Services\ManageResults\Forms\Templates;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\FormLikert;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SearchLikertItemsService extends BaseService
{
    public function rules(): array
    {
        return ['answer' => 'required|string'];
    }

    public function data(): array
    {
        return ['answer' => $this->request['answer'],];
    }

    public function permissions(): array
    {
        return [Role::PRODEKANAS];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        $query = FormLikert::whereRaw('LOWER(answer) LIKE ?', ['%' . strtolower($this->data()['answer']) . '%'])
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
