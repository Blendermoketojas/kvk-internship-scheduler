<?php

namespace App\Services\ManageUserProfile;

use App\Contracts\Roles\RolePermissions;
use App\Services\BaseService;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetGroupUsersService extends BaseService
{
    protected $groupId;

    public function rules(): array
    {
        return [
            'groupId' => 'required|exists:student_groups,id',
        ];
    }

    public function data(): array
    {
        return [
            'groupId' => $this->request['groupId']
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
        $users = UserProfile::where('student_group_id', $this->groupId)
        ->get(['id', 'first_name', 'last_name', 'fullname']);


        // response
        return response()->json($users);
    }
}
