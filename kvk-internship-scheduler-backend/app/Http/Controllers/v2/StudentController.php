<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\StudentGroup;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function searchStudentGroups(Request $request)
    {
        $groupIdentifier = $request->input('groupIdentifier');
        $studentGroups = StudentGroup::whereRaw('LOWER(group_identifier) LIKE ?', ['%' . strtolower($groupIdentifier) . '%'])->get();

        return response()->json($studentGroups);
    }

    public function searchStudents(Request $request)
    {
        $fullName = $request->input('fullName');

        $query = UserProfile::where('role_id', 5)
                ->whereRaw('LOWER(fullname) LIKE ?', ['%' . strtolower($fullName) . '%'])
            ->get();
        return response()->json($query);
    }

}
