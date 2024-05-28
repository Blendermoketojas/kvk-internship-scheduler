<?php

namespace App\Services\ManageResults\Grades;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Models\UserProfile;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetStudentGradesService extends BaseService
{
    public function rules(): array
    {
        return [
            'internship_id' => 'required|integer'
        ];
    }

    public function data(): array
    {
        return [
            'internship_id' => $this->request['internship_id']
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
        switch ($this->user->role_id) {
            case Role::STUDENTAS->value:
                $response = $this->getStudent();
                break;
            case Role::MENTORIUS->value:
                $response = $this->getOther();
                break;
            case Role::PRAKTIKOS_VADOVAS->value:
                $response = $this->getOther();
                break;
        }

        // response
        return $response->withHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
    private function getStudent() : JsonResponse
    {
        $internship = Internship::find($this->data()['internship_id']);
        if (!$internship) {
            return response()->json("Internship not found", 404);
        }

        $grades = $internship->grades()->get();
        $users = $grades->pluck('created_by')->unique()->values()->all();
        $userProfiles = UserProfile::whereIn('user_id', $users)->get()->keyBy('user_id');
        $response = [];

        foreach ($grades as $grade) {
            $userId = $grade->created_by;
            $roleId = $userProfiles[$userId]->role_id ?? null;
            if ($roleId) {
                if (!isset($response[$roleId])) {
                    $response[$roleId] = [];
                }
                array_push($response[$roleId], $grade->toArray());
            }
        }

        return response()->json($response);
    }

    private function getOther() : JsonResponse
    {
        $internship = Internship::find($this->data()['internship_id']);
        if (!$internship) {
            return response()->json("Internship not found", 404);
        }

        $grades = $internship->grades()->where('created_by', $this->user->user_id)->get();
        return response()->json($grades);
    }
}
