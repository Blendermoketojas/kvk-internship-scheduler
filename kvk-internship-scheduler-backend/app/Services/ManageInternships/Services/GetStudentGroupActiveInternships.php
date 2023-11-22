<?php

namespace App\Services\ManageInternships\Services;

use App\Models\Internship;
use App\Models\StudentGroup;
use App\Services\BaseService;
use App\Services\ManageInternships\ResponseResources\GetActiveInternshipResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetStudentGroupActiveInternships extends BaseService
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

        $internships = Internship::whereIn('user_id', $studentIds)
            ->where('is_active', true)
            ->with('userProfile', 'company')
            ->get();

        // response

        return response()->json($internships);
    }
}
