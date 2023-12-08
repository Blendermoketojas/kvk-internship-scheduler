<?php

namespace App\Services\ManageComments\Services;

use App\Helpers\Time\DateHelper;
use App\Helpers\Time\TimeHelper;
use App\Models\Comment;
use App\Models\Internship;
use App\Services\BaseService;
use App\Services\ManageComments\Response\CommentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateCommentService extends BaseService
{
    public function rules(): array
    {
        return [
            'internship_id' => 'required|integer|exists:internships,id',
            'comment' => 'required|string|min:1|max:512',
            'date_from' => 'required|date',
            'date_to' => 'required|date'
        ];
    }

    public function data(): array
    {
        return [
            'internship_id' => $this->request['internshipId'],
            'comment' => $this->request['comment'],
            'date_from' => $this->request['dateFrom'],
            'date_to' => $this->request['dateTo'],
            'user_id' => $this->user->id
        ];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    function execute(): JsonResponse
    {
        // input validation

        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $internship = Internship::find($this->data()['internship_id']);

        if (!$internship->is_active) {
            return response()->json(['error' => 'Cannot perform action. Internship is not active anymore'], 405);
        }

        // validate dates

        DateHelper::ensureDatesAreBetween([$this->data()['date_from'], $this->data()['date_to']],
            $internship->date_from, $internship->date_to);

        $comment = new Comment([
            'user_id' => $this->data()['user_id'],
            'comment' => $this->data()['comment'],
            'date_from' => $this->data()['date_from'],
            'date_to' => $this->data()['date_to']
        ]);

        $internship->comments()->create($comment);

        $hours_logged = TimeHelper::getHoursInFractionFromDate($this->data()['date_from'], $this->data()['date_to']);

        $internship->logged_hours = +$hours_logged;

        // response
        return response()->json(['comment' => $comment, 'hours_logged' => $internship->logged_hours]);
    }
}
