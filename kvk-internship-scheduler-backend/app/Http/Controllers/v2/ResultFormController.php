<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageResults\GetTemplateService;
use App\Services\ManageResults\ModifyTemplateService;
use App\Services\ManageResults\SearchLikertItemsService;
use App\Services\ManageResults\SearchQuestionItemsService;
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
}
