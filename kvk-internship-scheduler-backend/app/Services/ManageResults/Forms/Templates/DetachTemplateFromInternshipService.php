<?php

namespace App\Services\ManageResults\Forms\Templates;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DetachTemplateFromInternshipService extends BaseService
{
    public function rules(): array
    {
        return [
            'internship_id' => 'required|integer',
            'templates' => 'required|array'
        ];
    }

    public function data(): array
    {
        return [
            'internship_id' => $this->request['internship_id'],
            'templates' => $this->request['templates']
        ];
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

        // logic execution

        $internship = Internship::find($this->data()['internship_id']);
        $internship->templates()->detach($this->data()['templates']);

        // response
        return response()->json('OK?');
    }
}
