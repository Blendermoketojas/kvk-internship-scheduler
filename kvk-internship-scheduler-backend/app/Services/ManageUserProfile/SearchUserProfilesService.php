<?php

namespace App\Services\ManageUserProfile;

use App\Http\Resources\Response\UserProfileResource;
use App\Models\UserProfile;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class
SearchUserProfilesService extends BaseService
{
    public function rules(): array
    {
        return ['fullName' => 'required|string'];
    }

    public function data(): array
    {
        return [
            'fullName' => $this->request['fullName'],
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

        $query = UserProfile::whereRaw('LOWER(fullname) LIKE ?', ['%' . strtolower($this->data()['fullName']) . '%'])
            ->with('role')
            ->get();

        // response
        return response()->json($query);
    }
}
