<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\UserProfile;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetUserInternshipsService extends BaseService
{
    public function rules(): array
    {
        return [
            'userId' => 'required|integer|exists:users,id'
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
        return [Role::PRODEKANAS];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution
        $user = UserProfile::find($this->data()['userId']);
        $internships = $user->internships()->with('company')->get();

        $response = [
            'internships' => $internships,
            'userProfile' => [
                'id' => $user->id,
                'fullname' => $user->fullname
            ]
        ];

        return response()->json($response);
    }
}
