<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageStudentGroups\CreateStudentGroupService;
use App\Services\ManageStudentGroups\DeleteStudentGroupService;
use App\Services\ManageStudentGroups\GetStudentGroupService;
use App\Services\ManageStudentGroups\UpdateStudentGroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentGroupController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function getStudentGroup(Request $request) : JsonResponse
    {
        return (new GetStudentGroupService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function updateStudentGroup(Request $request) : JsonResponse
    {
        return (new UpdateStudentGroupService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function deleteStudentGroup(Request $request) : JsonResponse
    {
        return (new DeleteStudentGroupService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function createStudentGroup(Request $request) : JsonResponse
    {
        return (new CreateStudentGroupService($request))->execute();
    }
}
