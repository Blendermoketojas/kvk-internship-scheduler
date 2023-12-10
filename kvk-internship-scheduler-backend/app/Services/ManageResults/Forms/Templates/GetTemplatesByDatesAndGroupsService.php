<?php

namespace App\Services\ManageResults\Forms\Templates;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\FormTemplate;
use App\Models\Internship;
use App\Models\InternshipForm;
use App\Models\StudentGroup;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetTemplatesByDatesAndGroupsService extends BaseService
{
    public function rules(): array
    {
        return [
            'date_from' => 'required|string',
            'date_to' => 'required|string',
            'student_group' => 'required|integer'
        ];
    }

    public function data(): array
    {
        return [
            'date_from' => $this->request['date_from'],
            'date_to' => $this->request['date_to'],
            'student_group' => $this->request['student_group']
        ];
    }

    public function permissions(): array
    {
        return [Role::PRODEKANAS, Role::KATEDROS_VEDEJAS];
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

        $filledForms = (InternshipForm::whereIn('internship_id', $internshipIds))->has('answers')->get()->pluck('template_id');

        $response = array();
        $index = 0;

        foreach ($filledForms as $form) {
            if (sizeof($response) > 0) {
                $found = false;
                for ($i = 0; $i < sizeof($response); $i++) {
                    if ($response[$i]['template']['id'] == $form) {
                        $response[$i]['filledAmount'] += 1;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $response[$index] = array();
                    $response[$index]['template'] = FormTemplate::where('id', $form)->get()[0];
                    $response[$index]['filledAmount'] = 1;
                    $index += 1;
                }
            }

            if (sizeof($response) < 1) {
                $response[$index]['template'] = FormTemplate::where('id', $form)->get()[0];
                $response[$index]['filledAmount'] = 1;
                $index += 1;
            }
        }

        // response
        return response()->json($response);
    }
}
