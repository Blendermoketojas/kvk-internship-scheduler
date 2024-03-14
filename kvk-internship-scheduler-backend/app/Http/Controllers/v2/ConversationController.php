<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ManageMessages\ConversationService\CreateConversationService;
use App\Services\ManageMessages\ConversationService\GetUserConversationsService;
use App\Services\ManageMessages\ConversationService\GetConversationMessages;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
     /**
     * @throws ValidationException
     */
    public function createConversation(Request $request): JsonResponse
    {
        return (new CreateConversationService($request))->execute();
    }

        /**
     * @throws ValidationException
     */
    public function getUsersConversations(Request $request): JsonResponse
    {
        return (new GetUserConversationsService($request))->execute();
    }
    
           /**
     * @throws ValidationException
     */
    public function getConversationMessages(Request $request): JsonResponse
    {
        return (new GetConversationMessages($request))->execute();
    }
}
