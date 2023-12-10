<?php

namespace App\Services\ManageResults\Grades;

use App\Contracts\Roles\RolePermissions;
use App\Models\GradeItem;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DeleteGradeService extends BaseService
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer'
        ];
    }

    public function data(): array
    {
        return [
            'id' => $this->request['id']
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
        $grade = GradeItem::find($this->data())[0];

        if ($grade['created_by'] == $this->user->id) {
            $grade->delete();
        } else {
            return response()->json('Cant delete grade that was created by another person');
        }
        // response
        return response()->json('Grade deleted succesfully!');
    }
}
