<?php

namespace App\Services\ManageComments\Services;

use App\Models\Internship;
use App\Services\BaseService;
use App\Services\ManageComments\Response\CommentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GetAllInternshipCommentsService extends BaseService
{
    public function rules(): array
    {
        return [
            'internship_id' => 'required|integer|exists:internships,id'
        ];
    }

    public function data(): array
    {
        return [
            'internship_id' => $this->request['internshipId']
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
        $comments = Internship::find($this->data()['internship_id'])
            ->comments()
            ->with('userProfile')
            ->get();

        // response
        return response()->json($comments);
    }
}
