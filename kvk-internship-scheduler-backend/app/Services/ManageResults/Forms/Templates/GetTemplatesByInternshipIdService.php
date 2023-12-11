<?php

namespace App\Services\ManageResults\Forms\Templates;

use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetTemplatesByInternshipIdService extends BaseService
{
    public function rules(): array
    {
        return [
            'internshipId' => 'required|integer|exists:internships,id'
        ];
    }

    public function data(): array
    {
        return [
            'internshipId' => $this->request['internshipId']
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

        $internship = Internship::where('is_active', false)->find($this->data()['internshipId'])->first();
        $templates = $internship->templates;

        // response
        return response()->json($templates);
    }
}
