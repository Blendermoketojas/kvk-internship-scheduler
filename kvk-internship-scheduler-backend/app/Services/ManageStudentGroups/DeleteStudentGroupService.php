<?php

namespace App\Services\ManageStudentGroups;

use App\Contracts\Roles\Role;
use App\Models\StudentGroup;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DeleteStudentGroupService extends BaseService
{
    public function rules(): array
    {
        return [
            'studentGroupId' => 'required|integer|exists:student_groups,id'
        ];
    }

    public function data(): array
    {
        return [
            'studentGroupId' => $this->request['studentGroupId']
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

        $studentGroup = StudentGroup::find($this->data()['studentGroupId']);
        $studentGroup->delete();

        // response
        return response()->json(['success' => true, 'message' => 'Student group deletion successful']);
    }
}
