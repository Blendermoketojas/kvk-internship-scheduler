<?php

namespace App\Services\ManageStudents;

use App\Models\StudentGroup;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SearchStudentGroupsService extends BaseService
{
    public function rules(): array
    {
        return [
            'groupIdentifier' => 'required|string'
        ];
    }

    public function data(): array
    {
        return ['groupIdentifier' => $this->request['groupIdentifier']];
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

        $groupIdentifier = $this->data()['groupIdentifier'];
        $studentGroups = StudentGroup::whereRaw('LOWER(group_identifier) LIKE ?', ['%' . $groupIdentifier . '%'])->get();

        // response
        return response()->json($studentGroups);
    }
}
