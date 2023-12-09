<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\UserProfile;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetLinkedStudentsInternshipsService extends BaseService
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
        return [Role::MENTORIUS, Role::PRAKTIKOS_VADOVAS];
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
            ->pluck('id')->get();

        // TODO: FINISH THIS TODAY

        // response
        return response()->json($linkedUserProfiles);
    }
}
