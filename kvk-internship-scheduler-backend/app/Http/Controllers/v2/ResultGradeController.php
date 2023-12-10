<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageResults\Grades\CreateGradeService;
use App\Services\ManageResults\Grades\DeleteGradeService;
use App\Services\ManageResults\Grades\GetStudentGradesService;
use App\Services\ManageResults\Grades\ModifyGradeService;
use Cassandra\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultGradeController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function createGrade(Request $request): JsonResponse {
        return (new CreateGradeService($request))->execute();
    }
    /**
     * @throws ValidationException
     */
    public function modifyGrade(Request $request): JsonResponse {
        return (new ModifyGradeService($request))->execute();
    }
    /**
     * @throws ValidationException
     */
    public function deleteGrade(Request $request): JsonResponse {
        return (new DeleteGradeService($request))->execute();
    }
    /**
     * @throws ValidationException
     */
    public function getStudentGrades(Request $request): JsonResponse {
        return (new GetStudentGradesService($request))->execute();
    }
}
