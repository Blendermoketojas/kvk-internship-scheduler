<?php

namespace App\Services\ManageResults\Forms\Results;

use App\Contracts\Roles\RolePermissions;
use App\Models\AnswerItem;
use App\Models\FormAnswerItem;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetTemplateResultService extends BaseService
{
    public function rules(): array
    {
        return [
            'internship_id' => 'required|integer',
            'template_id' => 'required|integer'
        ];
    }

    public function data(): array
    {
        return [
            'internship_id' => $this->request['internship_id'],
            'template_id' => $this->request['template_id']
        ];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution
        $pivot = Internship::find($this->data()['internship_id'])->templates()->wherePivot('template_id', $this->data()['template_id'])->get();

        $pivotId = $pivot->first()->pivot->id;

        $items = AnswerItem::where('internship_form_id', $pivotId)->get();

        $itemIds = collect($items)->transform(function ($item) {
           $item = $item['item_id'];
           return $item;
        })->all();

        $answers = FormAnswerItem::whereIn('id', $itemIds)->get();

        // response
        return response()->json($answers);
    }
}
