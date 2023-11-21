<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Models\StudentGroup;
use App\Services\ManageInternships\CreateInternshipService;
use Illuminate\Http\Request;

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

    public function getStudentGroupInternships(Request $request)
    {
        $studentGroupInternshipData = $request->validate([
            'studentGroupId' => 'required|integer',
        ]);

        $studentGroupId = $studentGroupInternshipData['studentGroupId'];

        $studentGroup = StudentGroup::find($studentGroupId);

        $studentIds = $studentGroup->userProfiles()->pluck('id');

        $internships = Internship::whereIn('user_id', $studentIds)->get();

        return response()->json($internships);
    }

    public function getStudentGroupActiveInternships(Request $request)
    {
        $studentGroupInternshipData = $request->validate([
            'studentGroupId' => 'required|integer',
        ]);

        $studentGroupId = $studentGroupInternshipData['studentGroupId'];

        $studentGroup = StudentGroup::find($studentGroupId);

        $studentIds = $studentGroup->userProfiles()->pluck('id');

        $internships = Internship::whereIn('user_id', $studentIds)->where('is_active', true)->get();

        return response()->json($internships);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $service = new CreateInternshipService($request);
        return $service->execute();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($i)
    {
    }

    public function getActiveInternship(Request $request) {

        $userProfile = auth()->user()->userProfile;

        $active_internship = Internship::where('user_id', $userProfile->id)
            ->where('is_active', true)
            ->get();

        if ($active_internship == null) {
            response()->json('User does not currently have an active internship', 404);
        }

        return response()->json($active_internship);
    }

    public function getInternship(Request $request) {
        $internshipData = $request->validate([
            'internshipId' => 'required|integer',
        ]);

        $internshipId = $internshipData['internshipId'];

        $internship = Internship::findOr($internshipId, function () {
            return response()->json(["error" => "Internship by given id not found."]);
        });

        return response()->json($internship);
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
