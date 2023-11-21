<?php

namespace App\Services\ManageInternships;

use App\Models\Internship;
use App\Models\StudentGroup;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GetStudentGroupInternships extends BaseService
{
    public function rules(): array
    {
        return ['studentGroupId' => 'required|integer'];
    }

    public function data(): array
    {
        return ['studentGroupId' => $this->request['studentGroupId']];
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

        $validation = $this->validateRules();
        if (!is_bool($validation)) {
            return $validation;
        }

        // logic execution

        $studentGroup = StudentGroup::find($this->data()['studentGroupId']);

        $studentIds = $studentGroup->userProfiles()->pluck('id');

        $internships = Internship::whereIn('user_id', $studentIds)->get();

        // response

        return response()->json($internships);
    }
}
