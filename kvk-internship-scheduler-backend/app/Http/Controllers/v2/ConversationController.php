<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ManageMessages\ConversationService\CreateConversationService;
use App\Services\ManageMessages\ConversationService\GetUserConversationsService;
use App\Services\ManageMessages\ConversationService\GetConversationMessages;
use App\Services\ManageMessages\ConversationService\GetGroupConversationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

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
    public function getConversationMessages(Request $request,  $conversationId): JsonResponse
    {
        $request->merge(['conversation_id' => $conversationId]);
        return (new GetConversationMessages($request))->execute();
    }      
         /**
     * @throws ValidationException
     */
    public function getGroupConversations(Request $request): JsonResponse
    {
        return (new GetGroupConversationsService($request))->execute();
    }
}
