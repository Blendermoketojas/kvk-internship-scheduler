<?php

namespace App\Services\ManageMessages\ConversationService;

use App\Contracts\Roles\RolePermissions;
use App\Services\BaseService;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class GetConversationMessages extends BaseService
{
    public function rules(): array
    {
        return [
            'conversation_id' => 'required|integer|exists:messages,id'

        ];
    }

    public function data(): array
    {
        return [
            'conversation_id' => $this->request['conversation_id']
        ];
    }

    public function permissions(): array
    {
        return [];
    }

   /**
     * 
     *
     * @throws ValidationException
     */
    public function execute(): JsonResponse
    {
        $validator = Validator::make($this->request->all(), [
            'conversation_id' => 'required|integer|exists:conversations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $validated = $validator->validated();
        $conversationId = $validated['conversation_id'];

        try {
            $conversation = Conversation::findOrFail($conversationId);
            $messages = $conversation->messages()->with('userProfile')->get(); 

            return response()->json([
                'message' => 'Messages fetched successfully',
                'data' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch messages for the conversation', 'exception' => $e->getMessage()], 500);
        }
    }
}
