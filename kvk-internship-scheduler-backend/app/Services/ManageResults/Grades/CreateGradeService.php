<?php

namespace App\Services\ManageResults\Grades;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\GradeItem;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateGradeService extends BaseService
{
    public function rules(): array
    {
        return [
            'grade' => 'required|numeric',
            'comment' => 'required|string',
            'date' => 'required|string',
            'internship_id' => 'required|integer'
        ];
    }

    public function data(): array
    {
        return [
            'grade' => $this->request['grade'],
            'comment' => $this->request['comment'],
            'date' => $this->request['date'],
            'internship_id' => $this->request['internship_id'],
            'is_final' => $this->request['is_final']
        ];
    }

    public function permissions(): array
    {
        return [Role::MENTORIUS, Role::PRAKTIKOS_VADOVAS];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution
        $finalExists = Internship::find($this->data()['internship_id'])->grades()->where(['is_final' => true])->get();

        foreach ($finalExists as $finalGrade) {
            if ($finalGrade['created_by'] == $this->user->id) {
                return response()->json('Final grade was recorded already, not allowed to add new grades!', 405);
            }
        }

        if ($this->data()['is_final'] == null) {
            $gradeItem = GradeItem::create(array_diff_key($this->data(), ['is_final' => '']));
        } else {
            $gradeItem = GradeItem::create($this->data());
        }
        // response
        return response()->json($gradeItem);
    }
}