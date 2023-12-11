<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class FilterNotEvaluatedInternshipsService extends BaseService
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

        $userInternships = $this->user->internships;
        $userRole = $this->user->role_id;
        // 3 vadovas, 4 mentorius

        $evaluationFilter = '';
        switch ($userRole) {
            case 3:
                $evaluationFilter = 'is_head_of_internship_evaluated';
                break;
            case 4:
                $evaluationFilter = 'is_mentor_evaluated';
                break;
            case 5:
                $evaluationFilter = 'is_self_evaluated';
                break;
            default: {
                return response()->json(['error' => 'Must be of type Mentorius, Praktikos vadvoas or Studentas']);
            }
        }

        $sharedInternships = Internship::where('is_active', false)
            ->where($evaluationFilter, false)
            ->whereHas('userProfiles', function($query) use ($userInternships) {
                $query->whereIn('internship_id', $userInternships->pluck('id'))
                    ->where('role_id', 5);
            })
            ->with(['userProfiles' => function($query) {
                $query->where('role_id', 5);
            }])
            ->with('company')
            ->get();

        // response
        return response()->json($sharedInternships);
    }
}
