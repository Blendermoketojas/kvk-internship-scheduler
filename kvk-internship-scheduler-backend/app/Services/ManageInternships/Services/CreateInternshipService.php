<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\Role;
use App\Models\Internship;
use App\Models\UserProfile;
use App\Services\BaseService;
use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateInternshipService extends BaseService
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:80',
            'company_id' => 'required|integer',
            'users' => 'required|array',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'is_active' => 'required|integer'];
    }

    public function data(): array
    {
        $forms = isset($this->request['forms']) ? array_map(function($form) {
            return is_array($form) ? $form['id'] : $form;
        }, $this->request['forms']) : [];
    
        return [
            'title' => $this->request['title'],
            'company_id' => $this->request['companyId'],
            'users' => $this->request['users'],
            'date_from' => $this->request['dateFrom'],
            'date_to' => $this->request['dateTo'],
            'is_active' => 1,
            'forms' => $forms,
        ];
    }

    public function permissions(): array
    {
        return [Role::PRODEKANAS, Role::PRAKTIKOS_VADOVAS];
    }

    /**
     * @throws ValidationException
     */
    function execute(): JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        $userIds = $this->data()['users'];

        if ($this->user->role_id != Role::PRODEKANAS->value) {
            if (!in_array($this->user->id, $this->data()['users'])) {
                return response()->json(['success' => false, 'message' => 'Head of internships user'.
                 ' does not match or is not present']);
            }
        }

        $usersInRequest = UserProfile::findMany($this->data()['users']);
        $students = [];
        $studentIds = [];

        // Check if necessary roles to create an Internship are present: Student, Mentorius, Praktikos vadovas
        $mentorExists = $studentExists = $headOfInternshipExists = false;

        // Filter the students, also check if there is a MENTORIUS and a PRAKTIKOS VADOVAS
        foreach ($usersInRequest as $user) {
            switch ($user->role_id) {
                case Role::STUDENTAS->value:
                    $students[] = $user;
                    $studentIds[] = $user->id;
                    $studentExists = true;
                    break;
                case Role::MENTORIUS->value:
                    $mentorExists = true;
                    break;
                case Role::PRAKTIKOS_VADOVAS->value:
                    $headOfInternshipExists = true;
                    break;
            }
        }

        if (!($mentorExists && $studentExists && $headOfInternshipExists)) {
            return response()->json(['success' => false, 'message' => 'All necessary roles must be present'.
             ' to create an internship']);
        }

        // Check if student is in an active internship
        $activeInternshipExists = Internship::where('is_active', true)
            ->whereHas('userProfiles', function ($query) use ($userIds) {
                $query->whereIn('userProfiles.id', $userIds)
                    ->where('userProfiles.role_id', Role::STUDENTAS->value);
            })
            ->with(['userProfiles' => function ($query) use ($userIds) {
                $query->whereIn('userProfiles.id', $userIds)
                    ->where('userProfiles.role_id', Role::STUDENTAS->value);
            }])
            ->get();

        if (sizeof($activeInternshipExists) > 0) {
            $activeInternshipToArray = $activeInternshipExists->toArray();

            $userProfiles = array_map(function ($item) {
                return $item['user_profiles'];
            }, $activeInternshipToArray);

            return response()->json(['error' => 'Internship could not be created because the following ' .
                'students are already in an active internship', 'students' => $userProfiles], 405);
        }

        foreach ($students as $student) {
            // create the record
            $internship = Internship::create(array_diff_key($this->data(), ['users' => '']));

            $otherRemovedStudents = array_diff($studentIds, [$student->id]);

            // save entries to pivot table
            $internship->userProfiles()->attach(array_diff($this->data()['users'], $otherRemovedStudents));

            if ($this->data()['forms'] != null) {
                $forms = $this->data()['forms'];
                $ids = array_map(function ($form) {
                    return $form['id'];
                }, $forms);
                $internship->templates()->attach($ids);
            }
        }

        // respond
        return response()->json($internship);
    }
}
