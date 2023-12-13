<?php

namespace App\Services\ManageStudentGroups;

use App\Contracts\Roles\Role;
use App\Models\StudentGroup;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateStudentGroupService extends BaseService
{
    public function rules(): array
    {
        return [
            'studentGroupIdentifier' => 'required|string|unique:student_groups,group_identifier',
            'fieldOfStudy' => 'required|string'
        ];
    }

    public function data(): array
    {
        return [
            'studentGroupIdentifier' => $this->request['studentGroupIdentifier'],
            'fieldOfStudy' => $this->request['fieldOfStudy']
        ];
    }

    public function permissions(): array
    {
        return [
            Role::PRODEKANAS, Role::KATEDROS_VEDEJAS
        ];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $studentGroup = StudentGroup::create([
            'group_identifier' => $this->data()['studentGroupIdentifier'],
            'field_of_study' => $this->data()['fieldOfStudy']
        ]);

        // response
        return response()->json(['studentGroup' => $studentGroup, 'success' => true]);
    }
}
