<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageInternships\Services\CreateInternshipService;
use App\Services\ManageInternships\Services\DeleteInternshipService;
use App\Services\ManageInternships\Services\FilterNotEvaluatedInternshipsService;
use App\Services\ManageInternships\Services\GetActiveInternshipService;
use App\Services\ManageInternships\Services\GetInternshipService;
use App\Services\ManageInternships\Services\GetLinkedStudentsInactiveInternshipsService;
use App\Services\ManageInternships\Services\GetStudentGroupActiveInternshipsService;
use App\Services\ManageInternships\Services\GetStudentGroupInternshipsService;
use App\Services\ManageInternships\Services\GetCurrentUserInternshipsService;
use App\Services\ManageInternships\Services\GetUserInternshipsService;
use App\Services\ManageInternships\Services\SearchInternshipTitlesService;
use App\Services\ManageInternships\Services\UpdateInternshipService;
use App\Services\ManageStudents\GetLinkedStudentsActiveInternshipsService;
use App\Services\ManageStudents\GetLinkedStudentsService;
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
        return (new GetStudentGroupInternshipsService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getStudentGroupActiveInternships(Request $request): JsonResponse
    {
        return (new GetStudentGroupActiveInternshipsService($request))->execute();
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
    public function getCurrentUserInternships(Request $request): JsonResponse
    {
        return (new GetCurrentUserInternshipsService($request))->execute();
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

    /**
     * @throws ValidationException
     */
    public function searchInternshipTitles(Request $request): JsonResponse
    {
        return (new SearchInternshipTitlesService($request))->execute();
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
    public function getLinkedStudentsInactiveInternshipsService(Request $request): JsonResponse
    {
        return (new GetLinkedStudentsinActiveInternshipsService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function filterNotEvaluatedInternships(Request $request): JsonResponse
    {
        return (new FilterNotEvaluatedInternshipsService($request))->execute();
    }
}
