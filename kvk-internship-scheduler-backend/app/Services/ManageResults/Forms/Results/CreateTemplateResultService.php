<?php

namespace App\Services\ManageResults\Forms\Results;

use App\Contracts\Roles\RolePermissions;
use App\Models\AnswerItem;
use App\Models\FormAnswerItem;
use App\Models\FormTemplate;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateTemplateResultService extends BaseService
{
    public function rules(): array
    {
        return [
            'template_id' => 'required|integer',
            'internship_id' => 'required|integer',
            'answers' => 'required|array'
        ];
    }

    public function data(): array
    {
        return [
            'template_id' => $this->request['template_id'],
            'internship_id' => $this->request['internship_id'],
            'answers' => $this->request['answers']
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

        $internship = Internship::find($this->data()['internship_id']);

        if (!$internship) {
            return response()->json("Internship not found", 404);
        }

        $pivotId = $pivot->first()->pivot->id;

        if (sizeof(AnswerItem::find(['item_id' => $pivotId])) > 0) {
            return response()->json("Already answered!");
        }

        // Need to find better implementation
        foreach ($this->data()['answers'] as $answerItem) {
            $answer = FormAnswerItem::create($answerItem);
            $answer->formAnswers()->sync($pivotId);
        }
        
        $internship->update(['is_self_evaluated' => 1]);

        // response
        return response()->json("Answers succesfully saved!");
    }
}
