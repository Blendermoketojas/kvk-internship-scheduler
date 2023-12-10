<?php

namespace App\Services\ManageResults\Forms\Templates;

use App\Contracts\Roles\RolePermissions;
use App\Models\AnswerItem;
use App\Models\FormAnswerItem;
use App\Models\FormLikert;
use App\Models\FormTemplate;
use App\Models\Internship;
use App\Models\InternshipForm;
use App\Models\StudentGroup;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetFilledFormDataService extends BaseService
{
    public function rules(): array
    {
        return [
            'date_from' => 'required|string',
            'date_to' => 'required|string',
            'template_id' => 'required|integer',
            'student_group' => 'required|integer'
        ];
    }

    public function data(): array
    {
        return [
            'date_from' => $this->request['date_from'],
            'date_to' => $this->request['date_to'],
            'template_id' => $this->request['template_id'],
            'student_group' => $this->request['student_group']
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
        $studentGroup = StudentGroup::find($this->data()['student_group']);

        $studentIds = $studentGroup->userProfiles()->pluck('id');

        $internshipIds = Internship::whereHas('userProfiles', function($query) use ($studentIds) {
            $query->whereIn('userprofiles.id', $studentIds);
        })->with('userProfiles')->where('is_active', true)
            ->whereBetween('date_to', [$this->data()['date_from'], $this->data()['date_to']])
            ->get()->pluck('id');

        $filledForms = (InternshipForm::whereIn('internship_id', $internshipIds))->where('template_id', $this->data()['template_id'])->get()->pluck('id');

        $answerIds = AnswerItem::whereIn('internship_form_id', $filledForms)->get()->pluck('item_id');

        $answers = FormAnswerItem::whereIn('id', $answerIds)->get();

        $answerNames = (FormTemplate::where('id', $this->data()['template_id'])->get())[0]->templateLikerts()->get();
        $questionNames = (FormTemplate::where('id', $this->data()['template_id'])->get())[0]->templateQuestions()->get();

        $response = array();
        $index = 0;
        foreach ($questionNames as $question) {
            $response[$index]['id'] = $question['id'];
            $response[$index]['name'] = $question['question'];
            $response[$index]['answers'] = collect($answerNames)->map(function ($item) {
                $newItem = clone $item;
                $newItem['filledAmount'] = 0; // Initialize filledAmount for each answer
                return $newItem;
            })->all();

            foreach ($answers as $answerToSave) {
                // Check if the answer belongs to the current question
                if ($answerToSave['question_id'] == $response[$index]['id']) {
                    // Find the corresponding answer in the $response array
                    $key = array_search($answerToSave['answer_id'], array_column($response[$index]['answers'], 'id'));

                    // If found, increment the filledAmount
                    if ($key !== false) {
                        $response[$index]['answers'][$key]['filledAmount'] += 1;
                    }
                }
            }
            $index += 1;
        }
        // response
        return response()->json($response);
    }
}
