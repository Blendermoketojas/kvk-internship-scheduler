<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\Role;
use App\Models\Internship;
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
        return [
            'title' => $this->request['title'],
            'company_id' => $this->request['companyId'],
            'users' => $this->request['users'],
            'date_from' => $this->request['dateFrom'],
            'date_to' => $this->request['dateTo'],
            'is_active' => 1,
            'forms' => $this->request['forms'],
        ];
    }

    public function permissions(): array
    {
        return [Role::PRODEKANAS];
    }

    /**
     * @throws ValidationException
     */
    function execute(): JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        $userIds = $this->data()['users'];

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

        if (sizeof($activeInternshipExists) > 0)
        {
            $activeInternshipToArray = $activeInternshipExists->toArray();

            $userProfiles = array_map(function ($item) {
                return $item['user_profiles'];
            }, $activeInternshipToArray);

            return response()->json(['error' => 'Internship could not be created because the following '.
                'students are already in an active internship', 'students' => $userProfiles], 405);
        }

        // create the record
        $internship = Internship::create(array_diff_key($this->data(), ['users' => '']));

        // save entries to pivot table
        $internship->userProfiles()->attach($this->data()['users']);

        if ($this->data()['forms'] != null) {
            $forms = $this->data()['forms'];
            $ids = array_map(function ($form) {
                return $form['id'];
            }, $forms);
            $internship->templates()->attach($ids);
        }
        
        // respond
        return response()->json($internship);
    }
}
