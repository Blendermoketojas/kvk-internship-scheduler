<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageStudents\GetLinkedStudentsActiveInternshipsService;
use App\Services\ManageStudents\GetLinkedStudentsService;
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

    /**
     * @throws ValidationException
     */
    public function getLinkedStudents(Request $request): JsonResponse
    {
        return (new GetLinkedStudentsService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getLinkedStudentsActiveInternships(Request $request): JsonResponse
    {
        return (new GetLinkedStudentsActiveInternshipsService($request))->execute();
    }
}
