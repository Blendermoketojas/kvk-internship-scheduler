<?php

namespace App\Services\ManageComments\Services;

use App\Contracts\Roles\RolePermissions;
use App\Exceptions\ModelNotProvidedInServiceException;
use App\Models\Comment;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DeleteCommentService extends BaseService
{
    protected string $modelClass = Comment::class;

    public function rules(): array
    {
        return [
            'comment_id' => 'required|integer'
        ];
    }

    public function data(): array
    {
        return [
            'comment_id' => $this->request['commentId']
        ];
    }

    public function permissions(): array
    {
        return [RolePermissions::SELF];
    }

    /**
     * @throws ValidationException
     * @throws ModelNotProvidedInServiceException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);


        // logic execution

        $this->modelInstance->delete();

        // response
        return response()->json(['success' => true]);
    }

    /**
     * Used for user's record ownership validation
     * Retrieves model instance. Then can be used with $modelInstance property
     */
    protected function retrieveModelInstance(): ?Model {
        $commentId = $this->request['commentId'];
        return Comment::findOrFail($commentId);
    }
}
