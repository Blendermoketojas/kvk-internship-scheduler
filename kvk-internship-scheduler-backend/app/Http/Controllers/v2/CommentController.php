<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageComments\CreateCommentService;
use App\Services\ManageComments\DeleteCommentService;
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
     */
    public function deleteComment(Request $request): JsonResponse
    {
        return (new DeleteCommentService($request))->execute();
    }
}
