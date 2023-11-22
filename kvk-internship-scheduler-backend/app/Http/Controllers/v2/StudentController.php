<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\StudentGroup;
use App\Models\UserProfile;
use App\Services\ManageStudents\SearchStudentGroupsService;
use App\Services\ManageStudents\SearchStudentsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function searchStudentGroups(Request $request): JsonResponse
    {
        return (new SearchStudentGroupsService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function searchStudents(Request $request): JsonResponse
    {
        return (new SearchStudentsService($request))->execute();
    }
}
