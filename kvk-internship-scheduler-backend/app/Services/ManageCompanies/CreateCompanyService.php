<?php

namespace App\Services\ManageCompanies;

use App\Contracts\Roles\Role;
use App\Models\Company;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateCompanyService extends BaseService
{
    public function rules(): array
    {
        return [
            'companyName' => 'required|string|max:255|unique:companies,company_name'
        ];
    }

    public function data(): array
    {
        return [
            'companyName' => $this->request['companyName']
        ];
    }

    public function permissions(): array
    {
        return [
            Role::PRODEKANAS, Role::KATEDROS_VEDEJAS
        ];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $company = Company::create([
           'company_name' => $this->data()['companyName']
        ]);

        // response
        return response()->json(['company' => $company, 'success' => true]);
    }
}
