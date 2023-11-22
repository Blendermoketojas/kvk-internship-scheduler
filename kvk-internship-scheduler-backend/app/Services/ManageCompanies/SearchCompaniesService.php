<?php

namespace App\Services\ManageCompanies;

use App\Models\Company;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SearchCompaniesService extends BaseService
{
    protected bool $authentication = false;

    public function rules(): array
    {
        return [
            'companyName' => 'required|string'
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

        $companies = Company::whereRaw('LOWER(company_name) LIKE ?',
            ['%' . strtolower($this->data()['companyName']) . '%'])->get();

        // response
        return response()->json($companies);
    }
}
