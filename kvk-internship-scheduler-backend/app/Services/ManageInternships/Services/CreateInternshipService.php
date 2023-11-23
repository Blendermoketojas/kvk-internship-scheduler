<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateInternshipService extends BaseService
{
    public function rules(): array
    {
        return ['company_id' => 'required|integer',
            'users' => 'required|array',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'is_active' => 'required|integer'];
    }

    public function data(): array
    {
        return ['company_id' => $this->request['companyId'],
            'users' => $this->request['users'],
            'date_from' => $this->request['dateFrom'],
            'date_to' => $this->request['dateTo'],
            'is_active' => 1];
    }

    public function permissions(): array
    {
        return [RolePermissions::PRODEKANAS];
    }

    /**
     * @throws ValidationException
     */
    function execute(): JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);


        // create the record
        $internship = Internship::create(array_diff_key($this->data(), ['users' => '']));

        // save entries to pivot table
        $internship->userProfiles()->attach($this->data()['users']);

        // respond
        return response()->json($internship);
    }
}
