<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageInternships\Operations\CreateInternshipService;
use App\Services\ManageInternships\Operations\GetActiveInternshipService;
use App\Services\ManageInternships\Operations\GetInternshipService;
use App\Services\ManageInternships\Operations\GetStudentGroupActiveInternships;
use App\Services\ManageInternships\Operations\GetStudentGroupInternships;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InternshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * @throws ValidationException
     */
    public function getStudentGroupInternships(Request $request)
    {
        return (new GetStudentGroupInternships($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getStudentGroupActiveInternships(Request $request)
    {
        return (new GetStudentGroupActiveInternships($request))->execute();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        return (new CreateInternshipService($request))->execute();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($i)
    {
    }

    public function getActiveInternship(Request $request): JsonResponse
    {
        return (new GetActiveInternshipService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getInternship(Request $request) {
        return (new GetInternshipService($request))->execute();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
