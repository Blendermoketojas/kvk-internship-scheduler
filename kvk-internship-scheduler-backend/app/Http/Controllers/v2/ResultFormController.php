<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageResults\Forms\Results\CreateTemplateResultService;
use App\Services\ManageResults\Forms\Results\GetTemplateResultService;
use App\Services\ManageResults\Forms\Templates\AttachTemplateToInternshipService;
use App\Services\ManageResults\Forms\Templates\DetachTemplateFromInternshipService;
use App\Services\ManageResults\Forms\Templates\GetTemplateByNameService;
use App\Services\ManageResults\Forms\Templates\GetTemplateService;
use App\Services\ManageResults\Forms\Templates\ModifyTemplateService;
use App\Services\ManageResults\Forms\Templates\SearchLikertItemsService;
use App\Services\ManageResults\Forms\Templates\SearchQuestionItemsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ResultFormController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function modifyTemplate(Request $request): JsonResponse {
        return (new ModifyTemplateService($request))->execute();
    }
    /**
     * @throws ValidationException
     */
    public function getTemplate(Request $request): JsonResponse {
        return (new GetTemplateService($request))->execute();
    }
    /**
     * @throws ValidationException
     */
    public function searchQuestion(Request $request): JsonResponse {
        return (new SearchQuestionItemsService($request))->execute();
    }
    /**
     * @throws ValidationException
     */
    public function searchLikert(Request $request): JsonResponse {
        return (new SearchLikertItemsService($request))->execute();
    }
    /**
     * @throws ValidationException
     */
    public function attachTemplate(Request $request): JsonResponse {
        return (new AttachTemplateToInternshipService($request))->execute();
    }
    /**
     * @throws ValidationException
     */
    public function searchTemplate(Request $request): JsonResponse {
        return (new GetTemplateByNameService($request))->execute();
    }
    /**
     * @throws ValidationException
     */
    public function createResult(Request $request): JsonResponse {
        return (new CreateTemplateResultService($request))->execute();
    }
    /**
     * @throws ValidationException
     */
    public function getResult(Request $request): JsonResponse {
        return (new GetTemplateResultService($request))->execute();
    }
}
