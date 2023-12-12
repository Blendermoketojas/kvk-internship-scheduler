<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\ManageCompanies\CreateCompanyService;
use App\Services\ManageCompanies\DeleteCompanyService;
use App\Services\ManageCompanies\EditCompanyService;
use App\Services\ManageCompanies\GetCompanyService;
use App\Services\ManageCompanies\SearchCompaniesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCompanies()
    {
        return response()->json(Company::all());
    }

    /**
     * @throws ValidationException
     */
    public function searchCompanies(Request $request): JsonResponse
    {
        return (new SearchCompaniesService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getCompany(Request $request): JsonResponse
    {
        return (new GetCompanyService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function editCompany(Request $request): JsonResponse
    {
        return (new EditCompanyService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function deleteCompany(Request $request): JsonResponse
    {
        return (new DeleteCompanyService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function createCompany(Request $request): JsonResponse
    {
        return (new CreateCompanyService($request))->execute();
    }

}
