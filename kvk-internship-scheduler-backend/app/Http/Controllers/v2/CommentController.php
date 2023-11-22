<?php

namespace App\Http\Controllers\v2;

use App\Exceptions\ModelNotProvidedInServiceException;
use App\Http\Controllers\Controller;
use App\Services\ManageComments\Services\CreateCommentService;
use App\Services\ManageComments\Services\DeleteCommentService;
use App\Services\ManageComments\Services\GetAllInternshipCommentsService;
use App\Services\ManageComments\Services\UpdateCommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function createComment(Request $request): JsonResponse
    {
        return (new CreateCommentService($request))->execute();
    }

    /**
     * @throws ValidationException
     * @throws ModelNotProvidedInServiceException
     */
    public function deleteComment(Request $request): JsonResponse
    {
        return (new DeleteCommentService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function updateComment(Request $request): JsonResponse
    {
        return (new UpdateCommentService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function getAllInternshipComments(Request $request): JsonResponse
    {
        return (new GetAllInternshipCommentsService($request))->execute();
    }
}
