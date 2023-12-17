<?php

namespace App\Services\ManageUserProfile;

use App\Contracts\Roles\Role;
use App\Http\Resources\Response\UserProfileResource;
use App\Models\UserProfile;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SearchUserProfilesByRoleService extends BaseService
{
    public function rules(): array
    {
        return ['fullName' => 'required|string',
            'role_id' => 'required|integer'
        ];
    }

    public function data(): array
    {
        return [
            'fullName' => $this->request['fullName'],
            'role_id' => $this->request['roleId']
        ];
    }

    public function permissions(): array
    {
        return [Role::PRODEKANAS, Role::PRAKTIKOS_VADOVAS];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);


        // logic execution

        $query = UserProfile::where('role_id', $this->data()['role_id'])
            ->whereRaw('LOWER(fullname) LIKE ?', ['%' . strtolower($this->data()['fullName']) . '%'])
            ->get();

        // response
        return response()->json(UserProfileResource::collection($query));
    }
}
