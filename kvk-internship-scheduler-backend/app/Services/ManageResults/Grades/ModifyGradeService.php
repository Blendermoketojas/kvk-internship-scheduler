<?php

namespace App\Services\ManageResults\Grades;

use App\Contracts\Roles\Role;
use App\Contracts\Roles\RolePermissions;
use App\Models\GradeItem;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ModifyGradeService extends BaseService
{
    public function rules(): array
    {
        return [
            'id' => 'required|number',
            'grade' => 'required|numeric',
            'comment' => 'required|string',
            'date' => 'required|date'
        ];
    }

    public function data(): array
    {
        return [
            'id' => $this->request['id'],
            'grade' => $this->request['grade'],
            'comment' => $this->request['comment'],
            'date' => $this->request['date']
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

        if (sizeof((Internship::find($this->data()['internship_id'])->get()->where('is_active', true))) < 1) {
            return response()->json('Not allowed to modify grades of inactive internship!');
        }

        // logic execution
        $grade = GradeItem::find($this->data()['id'])[0];
        if ($grade['created_by'] == $this->user->id) {
            $grade = $grade->update(array_diff_key($this->data(), ['id' => '']));
        } else {
            return response()->json('Cant modify a grade that was not created by you!');
        }
        // response
        return response()->json($grade);
    }
}
