<?php

namespace App\Services\ManageStudents;

use App\Contracts\Roles\RolePermissions;
use App\Models\UserProfile;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SearchStudentsService extends BaseService
{
    public function rules(): array
    {
        return [
            'fullName' => 'required|string'
        ];
    }

    public function data(): array
    {
        return [
            'fullName' => $this->request['fullName']
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

        $validation = $this->validateRules();
        if (!is_bool($validation)) {
            return $validation;
        }

        // logic execution

        $query = UserProfile::where('role_id', RolePermissions::STUDENTAS->value)
            ->whereRaw('LOWER(fullname) LIKE ?', ['%' . strtolower($this->data()['fullName']) . '%'])
            ->get();

        // response
        return response()->json($query);
    }
}
