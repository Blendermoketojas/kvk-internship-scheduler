<?php

namespace App\Services\ManageCompanies;

use App\Contracts\Roles\Role;
use App\Models\Company;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetCompanyService extends BaseService
{
    public function rules(): array
    {
        return [
            'companyId' => 'required|integer|exists:companies,id'
        ];
    }

    public function data(): array
    {
        return [
            'companyId' => $this->request['companyId']
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

        $company = Company::find($this->data()['companyId']);

        // response
        return response()->json($company);
    }
}
