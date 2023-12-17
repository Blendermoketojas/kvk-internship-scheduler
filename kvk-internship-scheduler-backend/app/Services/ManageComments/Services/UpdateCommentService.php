<?php

namespace App\Services\ManageComments\Services;

use App\Contracts\Roles\Role;
use App\Helpers\Time\TimeHelper;
use App\Models\Comment;
use App\Services\BaseService;
use App\Services\ManageComments\Response\CommentResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdateCommentService extends BaseService
{
    protected string $modelClass = Comment::class;

    public function rules(): array
    {
        return [
            'comment_id' => 'required|integer|exists:comments,id',
            'comment' => 'required|string|min:1|max:512',
            'date_from' => 'required|date',
            'date_to' => 'required|date'
        ];
    }

    public function data(): array
    {
        return [
            'comment_id' => $this->request['commentId'],
            'comment' => $this->request['comment'],
            'date_from' => $this->request['dateFrom'],
            'date_to' => $this->request['dateTo'],
        ];
    }

    public function permissions(): array
    {
        return [Role::SELF];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $hours_logged = TimeHelper::getHoursInFractionFromDate($this->data()['date_from'], $this->data()['date_to']);

        $this->modelInstance->logged_duration = $hours_logged;
        $this->modelInstance->update($this->data());

        // response
        return response()->json(new CommentResource($this->modelInstance));
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
