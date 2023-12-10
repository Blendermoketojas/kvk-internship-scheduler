<?php

namespace App\Services\ManageResults\Forms\Templates;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AttachTemplateToInternshipService extends BaseService
{
    public function rules(): array
    {
        return [
            'internship_id' => 'required|integer',
            'templates' => 'required|array',
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

        $noSequence = false;
        foreach ($this->data()['templates'] as $template) {
            if (!array_key_exists('sequence', $template)) {
                $noSequence = true;
                break;
            }
        }

        $response = null;

        if ($noSequence) {
            $response = $internship->templates()->sync(collect($this->data()['templates'])->pluck('id')->toArray())->get();
        } else {
            $response = $internship->templates()->sync($this->data()['templates'])->get();
        }

        // response
        return response()->json($response);
    }
}
