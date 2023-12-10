<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\Company;
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
    public function index()
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
}
