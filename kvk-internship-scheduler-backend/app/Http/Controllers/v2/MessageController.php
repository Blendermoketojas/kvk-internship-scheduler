<?php

namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Services\ManageMessages\MessagesService\SendMessagesService;
use App\Services\ManageMessages\MessagesService\DeleteMessageService;


class MessageController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function sendMessage(Request $request): JsonResponse
    {
        return (new SendMessagesService($request))->execute();
    }
    
    public function DeleteMessage(Request $request): JsonResponse
    {
        return (new DeleteMessageService($request))->execute();
    }


}
