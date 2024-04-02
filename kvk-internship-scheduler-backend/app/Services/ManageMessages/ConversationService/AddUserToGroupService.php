<?php

namespace App\Services\ManageMessages\ConversationService;

use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AddUserToGroupService extends BaseService
{
    /**
     * Validation rules for the incoming request.
     */
    public function rules(): array
    {
        return [
            'conversation_id' => 'required|exists:conversations,id',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:userprofiles,user_id'
        ];
    }

    public function data(): array
    {
        return [
            'conversation_id' => $this->request['conversation_id'],
            'user_id' => $this->request['user_id'],

        ];
    }

    /**
     * Executes the service to add a user to a group conversation.
     */
    public function execute(): JsonResponse
    {
        $conversationId = $this->request->input('conversation_id');
    $userIds = $this->request->input('user_id'); // Now an array

    try {
        $insertData = [];
        foreach ($userIds as $userId) {
            // Check if each user is already part of the conversation
            $exists = DB::table('conversation_user')
                        ->where('conversation_id', $conversationId)
                        ->where('user_id', $userId)
                        ->exists();

            if (!$exists) {
                $insertData[] = [
                    'conversation_id' => $conversationId,
                    'user_id' => $userId
                ];
            }
        }

        // Perform bulk insert if there are new users to add
        if (!empty($insertData)) {
            DB::table('conversation_user')->insert($insertData);
        }

        return response()->json(['success' => true, 'message' => 'Users added to group conversation successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to add users to group conversation', 'exception' => $e->getMessage()], 500);
    }
}
}
