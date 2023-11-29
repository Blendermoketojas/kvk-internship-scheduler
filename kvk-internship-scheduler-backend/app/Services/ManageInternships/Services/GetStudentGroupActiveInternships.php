<?php

namespace App\Services\ManageInternships\Services;

use App\Models\Internship;
use App\Models\StudentGroup;
use App\Services\BaseService;
use App\Services\ManageInternships\ResponseResources\GetActiveInternshipResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetStudentGroupActiveInternships extends BaseService
{
    public function rules(): array
    {
        return ['studentGroupId' => 'required|integer|exists:student_groups,id'];
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
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $studentGroup = StudentGroup::find($this->data()['studentGroupId']);

        $studentIds = $studentGroup->userProfiles->pluck('id');

        $internships = Internship::whereHas('userProfiles', function($query) use ($studentIds) {
            $query->whereIn('userprofiles.id', $studentIds); // Specify the table alias 'userprofiles.id'
        })->where('internships.is_active', true) // Specify 'is_active' condition for the 'Internship' table
        ->with('userProfiles')->get();

        // response

        return response()->json($internships);
    }
}
