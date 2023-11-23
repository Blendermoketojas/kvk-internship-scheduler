<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdateInternshipService extends BaseService
{
    public function rules(): array
    {
        return [
            'internship_id' => 'required|integer',
            'users' => 'required|array',
            'company_id' => 'required|integer',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'is_active' => 'required|boolean'];
    }

    public function data(): array
    {
        return [
            'internship_id' => $this->request['internshipId'],
            'users' => $this->request['users'],
            'company_id' => $this->request['companyId'],
            'date_from' => $this->request['dateFrom'],
            'date_to' => $this->request['dateTo'],
            'is_active' => $this->request['isActive']];
    }

    public function permissions(): array
    {
        return [RolePermissions::PRODEKANAS];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $internship = Internship::find($this->data()['internship_id']);

        $internship->update(array_diff_key($this->data(),
            ['users' => '', 'internship_id' => '']));

        $internship->userProfiles()->sync($this->data()['users']);

        // response
        return response()->json($internship);
    }
}
