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
                $response = $this->getMentor();
                break;
            case Role::PRAKTIKOS_VADOVAS->value:
                $response = $this->getVadovas();
                break;
        }

        // response
        return response()->json($response);
    }

    private function getStudent() {
        $grades = Internship::find($this->data())[0]->grades()->get();
        $users = [];
        foreach ($grades as $grade) {
            if (sizeof($users) > 0) {
                if ($users[0] != $grade['created_by']) {
                    $users[1] = $grade['created_by'];
                    break;
                }
            } else {
                $users[0] = $grade['created_by'];
            }
        }

        $userRoles = UserProfile::whereIn('user_id', $users)->get();

        if (sizeof($userRoles) == 0) {
            return response()->json(null);
        }

        if (sizeof($userRoles) == 1) {
            $response[$userRoles[0]->role_id] = array();
        }

        if (sizeof($userRoles) == 2) {
            $response[$userRoles[0]->role_id] = array();
            $response[$userRoles[1]->role_id] = array();
        }

        foreach ($grades as $grade) {
            if ($userRoles[0]->user_id == $grade->created_by) {
                array_push($response[$userRoles[0]->role_id], $grade);
            } else if (sizeof($userRoles) == 2) {
                array_push($response[$userRoles[1]->role_id], $grade);
            }
        }
        return $response;
    }

    private function getMentor() {
        return null;
    }

    private function getVadov() {
        return null;
    }
}
