<?php

namespace App\Services\ManageStudents;

use App\Contracts\Roles\Role;
use App\Models\UserProfile;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetLinkedStudentsService extends BaseService
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
        return [Role::PRAKTIKOS_VADOVAS, Role::MENTORIUS];
    }

    /**
     * @throws ValidationException
     */
    function execute(): JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $requestorId = $this->user->id;

        $linkedUserProfiles = UserProfile::where('role_id', 5)
            ->whereHas('internships', function ($query) use ($requestorId) {
                $query->whereHas('userProfiles', function ($subQuery) use ($requestorId) {
                    $subQuery->where('internship_user.user_id', $requestorId);
                });
            })
            ->get();

        // response
        return response()->json($linkedUserProfiles);
    }
}
