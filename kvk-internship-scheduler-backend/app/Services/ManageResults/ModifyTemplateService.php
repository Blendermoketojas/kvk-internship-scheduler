<?php

namespace App\Services\ManageResults;

use App\Contracts\Roles\Role;
use App\Models\FormLikert;
use App\Models\FormQuestion;
use App\Models\FormTemplate;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ModifyTemplateService extends BaseService
{
    public function rules(): array
    {
        return ['template_name' => 'required|string',
            'questions' => 'required|array',
            'answers' => 'required|array'];
    }

    public function data(): array
    {
        return ['template_id' => $this->request['template_id'],
            'template_name' => $this->request['template_name'],
            'questions' => $this->request['questions'],
            'answers' => $this->request['answers']
            ];
    }

    public function permissions(): array
    {
        return []; //Ka cia deti as nzn, kas turetu tureti galimybe kurti/modifikuoti formas?
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution
        return $this->operate();
        // response
        return response()->json('Not implemented');
    }

    private function operate(): JsonResponse {
        $formId = $this->data()['template_id'];

        if ($formId == null) {
            return $this->createNew();
        }
        return $this->modifyExisting($formId);
    }

    private function createNew(): JsonResponse {
        $formName = $this->data()['template_name'];
        $questions = $this->data()['questions'];
        $answers = $this->data()['answers'];

        $formTemplate = FormTemplate::create(['name' => $formName]);

        $responseAnswers = $this->resolveAnswers($answers, $formTemplate);
        //$responseQuestions = $this->resolveQuestions($questions, $formTemplate);
        return response()->json($this->resolveQuestions($questions, $formTemplate));
        $formTemplate->templateQuestions()->sync($this->pivotDict($responseQuestions));
        $formTemplate->templateLikerts()->sync($this->pivotDict($responseAnswers));

        $response['template_id'] = $formTemplate['id'];
        $response['name'] = $formTemplate['name'];
        $response['questions'] = $responseQuestions;
        $response['answers'] = $responseAnswers;

        return response()->json($response);
    }

    private function modifyExisting($formId): JsonResponse {
        $formTemplate = FormTemplate::find($formId);

        if ($formTemplate == null) {
            return response()->json('Template with id: ' . $formId . " was not found!", 404);
        }

        $formName = $this->data()['template_name'];
        $questions = $this->data()['questions'];
        $answers = $this->data()['answers'];

        if ($formTemplate['name'] != $formName) {
            $formTemplate::update('name', $formName);
        }

        $responseAnswers = $this->resolveAnswers($answers, $formTemplate);
        $responseQuestions = $this->resolveQuestions($questions, $formTemplate);

        $formTemplate->templateQuestions()->sync($responseQuestions['id']);
        $formTemplate->templateLikert()->sync($responseAnswers['id']);

        $response['id'] = $formTemplate['id'];
        $response['name'] = $formTemplate['name'];
        $response['questions'] = $responseQuestions;
        $response['answers'] = $responseAnswers;

        return response()->json(json_encode($response));
    }

    private function resolveQuestions($questions, $formTemplate) {
        $noIdQuestions = array();
        $existingQuestions = array();
        $newQuestions = array();
        foreach ($questions as $question) {
            if (!array_key_exists('id', $question)) {
                array_push($noIdQuestions, $question);
            } else {
                array_push($existingQuestions, $question);
            }
        }

        $savedQuestions = array();
        if (sizeof($noIdQuestions) > 0) {
            $temp = FormQuestion::whereIn('question', collect($noIdQuestions)->pluck('question')->toArray())->get();
            foreach ($temp as $item) {
                $found = false;
                foreach ($noIdQuestions as $noIdQuestion) {
                    if ($item['question'] == $noIdQuestion['question']) {
                        $found = true;
                    }
                }
                if (!$found) {
                    array_push($newQuestions, $item);
                }
            }
            $savedQuestions = array_merge($savedQuestions, $temp->toArray());
            $savedQuestions = array_merge($savedQuestions, $formTemplate->templateQuestions()->createMany($newQuestions));
            $savedQuestions = $this->mapValues($savedQuestions, $noIdQuestions);
        }
        if (sizeof($existingQuestions) > 0) {
            $temp = FormQuestion::whereIn('id', collect($existingQuestions)->pluck('id')->toArray())->get();
            $savedQuestions = array_merge($savedQuestions, $temp->toArray());
            if (sizeof($noIdQuestions) > 0) {
                $savedQuestions = array_merge($savedQuestions, $this->mapValues($savedQuestions, $existingQuestions));
            } else {
                $savedQuestions = $this->mapValues($savedQuestions, $existingQuestions);
            }
        }

        return $savedQuestions;
    }

    private function resolveAnswers($answers, $formTemplate) {
        $newAnswers = array();
        $existingAnswers = array();
        foreach ($answers as $answer) {
            if (!array_key_exists('id', $answer)) {
                array_push($newAnswers, $answer);
            } else {
                array_push($existingAnswers, $answer);
            }
        }

        //return response()->json($newAnswers);

        $savedAnswers = array();
        if (sizeof($newAnswers) > 0) {
            $savedAnswers = array_merge($savedAnswers, $formTemplate->templateLikerts()->createMany($newAnswers));
            $savedAnswers = $this->mapValues($savedAnswers, $newAnswers);
        }
        if (sizeof($existingAnswers) > 0) {
            $savedAnswers = array_merge($savedAnswers, FormLikert::findMany($existingAnswers['id']));
            $savedAnswers = $this->mapValues($savedAnswers, $existingAnswers);
        }
        return $savedAnswers;
    }

    private function mapValues($dbEntities, $requestEntities) {
        $newDbEntities = array();
        foreach ($dbEntities as $dbEntity) {
            foreach ($requestEntities as $requestEntity) {
                if (array_key_exists('id', $requestEntity)) {
                    if ($dbEntity['id'] == $requestEntity['id']) {
                        $dbEntity['sequence'] = $requestEntity['sequence'];
                        break;
                    }
                } else {
                    if (array_key_exists('answer', $requestEntity)) {
                        if ($dbEntity['answer'] == $requestEntity['answer']) {
                            $dbEntity['sequence'] = $requestEntity['sequence'];
                            break;
                        }
                    } else {
                        if ($dbEntity['question'] == $requestEntity['question']) {
                            $dbEntity['sequence'] = $requestEntity['sequence'];
                            break;
                        }
                    }
                }
            }
            unset($dbEntity['created_by']);
            unset($dbEntity['created_at']);
            unset($dbEntity['updated_at']);
            array_push($newDbEntities, $dbEntity);
        }

        return $newDbEntities;
    }

    private function pivotDict($dbDict) {
        $pivotDict = array();
        foreach($dbDict as $dbItem) {
            $temp['sequence'] = $dbItem['sequence'];
            if (isset($dbItem['question'])) {
                $temp['question_id'] = $dbItem['id'];
            } else {
                $temp['likert_id'] = $dbItem['id'];
            }
            array_push($pivotDict, $temp);
        }
        return $pivotDict;
    }
}
