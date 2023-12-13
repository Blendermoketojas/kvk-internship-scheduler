<?php

namespace App\Services\ManageStudentGroups;

use App\Models\StudentGroup;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetStudentGroupService extends BaseService
{
    public function rules(): array
    {
        return [
            'studentGroupId' => 'required|integer|exists:student_groups,id',
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

        $studentGroup = StudentGroup::find($this->data()['studentGroupId']);

        // response
        return response()->json($studentGroup);
    }
}
