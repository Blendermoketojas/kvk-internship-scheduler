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
            'userId' => 'required|integer|exists:users,id',
            'page' => 'nullable|integer'
        ];
    }

    public function data(): array
    {
        return [
            'userId' => $this->request['userId'],
            'page' => $this->request['page']
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

        if ($this->data()['page'] == null)
        {
            $internships = $user->internships()->with('company')->get();
        } else
        {
            $internships = $user->internships()->with('company')->paginate(5, ['*'], 'page', $this->data()['page']);
        }

        $response = [
            'internships' => $internships->items(),
            'userProfile' => [
                'id' => $user->id,
                'fullname' => $user->fullname
            ]
        ];

        return response()->json($response);
    }
}
