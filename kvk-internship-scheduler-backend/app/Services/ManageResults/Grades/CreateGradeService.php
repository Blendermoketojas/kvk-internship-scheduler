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
        if (sizeof((Internship::where('id', $this->data()['internship_id'])->where('is_active', true))->get()) < 1 && $this->data()['is_final'] == null) {
            return response()->json('Internship has ended, youre not allowed to create grades anymore');
        }

        $finalExists = Internship::find($this->data()['internship_id'])->grades()->where(['is_final' => true])->get();

        foreach ($finalExists as $finalGrade) {
            if ($finalGrade['created_by'] == $this->user->id) {
                return response()->json('Final grade was recorded already, not allowed to add new grades!', 405);
            }
        }

        if ($this->data()['is_final'] == null) {
            $gradeItem = GradeItem::create(array_diff_key($this->data(), ['is_final' => '']));
        } else {
            if (sizeof((Internship::where('id', $this->data()['internship_id'])->where('is_active', true))->get()) < 1) {
                $gradeItem = GradeItem::create($this->data());
                $internship = Internship::find($this->data()['internship_id'])->get()[0];
                if ($this->user->role_id == Role::MENTORIUS) {
                    $internship->is_mentor_evaluated = true;
                    $internship->save();
                } else {
                    $internship->is_head_of_internship_evaluated = true;
                    $internship->save();
                }
            } else {
                return response()->json('Not allowed to create final grade during active internship!');
            }
        }
        // response
        return response()->json($gradeItem);
    }
}
