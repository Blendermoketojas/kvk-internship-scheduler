<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageInternships\Services\CreateInternshipService;
use App\Services\ManageInternships\Services\DeleteInternshipService;
use App\Services\ManageInternships\Services\GetActiveInternshipService;
use App\Services\ManageInternships\Services\GetInternshipService;
use App\Services\ManageInternships\Services\GetStudentGroupActiveInternships;
use App\Services\ManageInternships\Services\GetStudentGroupInternships;
use App\Services\ManageInternships\Services\GetUserInternshipsService;
use App\Services\ManageInternships\Services\UpdateInternshipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InternshipController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function getStudentGroupInternships(Request $request): JsonResponse
    {
        return (new GetStudentGroupInternships($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getStudentGroupActiveInternships(Request $request): JsonResponse
    {
        return (new GetStudentGroupActiveInternships($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function createInternship(Request $request): JsonResponse
    {
        return (new CreateInternshipService($request))->execute();
    }

    public function getActiveInternship(Request $request): JsonResponse
    {
        return (new GetActiveInternshipService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getInternship(Request $request): JsonResponse
    {
        return (new GetInternshipService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getUserInternships(Request $request): JsonResponse
    {
        return (new GetUserInternshipsService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function deleteInternship(Request $request): JsonResponse
    {
        return (new DeleteInternshipService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function updateInternship(Request $request): JsonResponse
    {
        return (new UpdateInternshipService($request))->execute();
    }
}
