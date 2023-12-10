<?php

namespace App\Services\ManageUserProfile;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\UserProfile;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetUserProfileByIdService extends BaseService
{
    public function rules(): array
    {
        return [
            'userId' => 'required|integer|exists:userprofiles,id'
        ];
    }

    public function data(): array
    {
        return [
            'userId' => $this->request['userId']
        ];
    }

    public function permissions(): array
    {
        return [Role::PRODEKANAS, Role::PRAKTIKOS_VADOVAS, Role::MENTORIUS, Role::KATEDROS_VEDEJAS];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $userProfile = UserProfile::with('user')->find($this->data()['userId']);

        // response
        return response()->json($userProfile);
    }
}
