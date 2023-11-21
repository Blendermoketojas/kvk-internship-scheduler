<?php

namespace App\Services\ManageInternships;

use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateInternshipService extends BaseService
{
    public function rules(): array
    {
    return ['company_id' => 'required|integer',
        'user_id' => 'required|integer',
        'date_from' => 'required|date',
        'date_to' => 'required|date',
        'is_active' => 'required|integer'];
    }
    public function data(): array
    {
        return ['company_id' => $this->request['companyId'],
            'user_id' => $this->request['userId'],
            'date_from' => $this->request['dateFrom'],
            'date_to' => $this->request['dateTo'],
            'is_active' => 1];
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
        $validation = $this->validateRules();
        if (!is_bool($validation)) {
            return $validation;
        }

        // create the record
        $internship = Internship::create($this->data());

        // respond
        return response()->json($internship);
    }
}
