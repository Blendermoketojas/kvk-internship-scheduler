<?php

namespace App\Services\ManageStudents;

use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Models\UserProfile;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetLinkedStudentsActiveInternshipsService extends BaseService
{
    public function rules(): array
    {
        return [];
    }

    public function data(): array
    {
        return [];
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

        $teacherInternships = $this->user->internships;

        $sharedInternships = Internship::where('is_active', true)
            ->whereHas('userProfiles', function($query) use ($teacherInternships) {
                $query->whereIn('internship_id', $teacherInternships->pluck('id'))
                    ->where('role_id', 5);
            })
            ->with(['userProfiles' => function($query) {
                $query->where('role_id', 5);
            }])
            ->get();


        // response
        return response()->json($sharedInternships);
    }
}
