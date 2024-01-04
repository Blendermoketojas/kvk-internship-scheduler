<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetLinkedStudentsInactiveInternshipsService extends BaseService
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
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $userInternships = $this->user->internships;

        $sharedInternships = Internship::where('is_active', false)
            ->whereHas('userProfiles', function($query) use ($userInternships) {
                $query->whereIn('internship_id', $userInternships->pluck('id'))
                    ->where('role_id', Role::STUDENTAS->value);
            })
            ->with(['userProfiles' => function($query) {
                $query->where('role_id', Role::STUDENTAS->value);
            }])->with('company')
            ->get();


        // response
        return response()->json($sharedInternships);
    }
}
