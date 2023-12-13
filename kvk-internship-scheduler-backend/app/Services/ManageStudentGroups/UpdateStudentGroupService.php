<?php

namespace App\Services\ManageStudentGroups;

use App\Contracts\Roles\RolePermissions;
use App\Models\StudentGroup;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdateStudentGroupService extends BaseService
{
    public function rules(): array
    {
        return [
            'studentGroupId' => 'required|integer|exists:student_groups,id',
            'studentGroupIdentifier' => 'nullable|string',
            'fieldOfStudy' => 'nullable|string'
        ];
    }

    public function data(): array
    {
        return [
            'studentGroupId' => $this->request['studentGroupId'],
            'studentGroupIdentifier' => $this->request['studentGroupIdentifier'],
            'fieldOfStudy' => $this->request['fieldOfStudy']
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
        $didSomethingChange = false;

        $studentGroup = StudentGroup::find($this->data()['studentGroupId']);

        if ($this->data()['studentGroupIdentifier'] != null)
        {
            $studentGroup->group_identifier = $this->data()['studentGroupIdentifier'];
            $didSomethingChange = true;
        }

        if ($this->data()['fieldOfStudy'] != null)
        {
            $studentGroup->field_of_study = $this->data()['fieldOfStudy'];
            $didSomethingChange = true;
        }

        if ($didSomethingChange) {
            $studentGroup->save();
        }

        // response
        return response()->json(['studentGroup' => $studentGroup, 'didRecordChange' => $didSomethingChange]);
    }
}
