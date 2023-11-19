<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\StudentGroup;
use App\Models\UserProfile;
use http\Env\Request;

class StudentController extends Controller
{
    public function searchStudentGroups(Request $request) {
        return StudentGroup::where('group_identifier', $request->input('groupIdentifier'));
    }

    public function searchStudents() {
        return UserProfile::where('role_id', 5)->where('');
    }
}
