<?php

namespace App\Services\ManageMessages\ConversationService;

use App\Contracts\Roles\RolePermissions;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Models\UserProfile;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class GetUserConversationsService extends BaseService
{
    public function rules(): array
    {
        return [        

        ];
    }

    public function data(): array
    {
        return [
            'user_id' => $this->user->id
        ];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
           // input validation
    if (!$this->validateRules()) {
        return response()->json("Action not allowed", 401);
    }

    // Ensure that the user is set and valid
    if (!$this->user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    try {
        $userId = auth()->id();

        // Get private conversations IDs for the logged-in user
        $privateConversationsIds = DB::table('conversation_user')
            ->join('conversations', 'conversation_user.conversation_id', '=', 'conversations.id')
            ->where('conversations.type', 'private')
            ->where('conversation_user.user_id', $userId)
            ->pluck('conversation_id');

        $otherParticipants = collect();

        foreach ($privateConversationsIds as $conversationId) {
            // For each conversation, find the other user's IDs excluding the logged-in user
            $otherUserIds = DB::table('conversation_user')
                ->where('conversation_id', $conversationId)
                ->where('user_id', '!=', $userId)
                ->pluck('user_id');

            // Fetch other participants' UserProfile information
            $otherParticipantsInfo = UserProfile::whereIn('user_id', $otherUserIds)
            ->get(['user_id', 'fullname', 'image_path']);

            foreach ($otherParticipantsInfo as $participant) {
                $otherParticipants->push([
                    'conversation_id' => $conversationId,
                    'user_id' => $participant->user_id, 
                    'fullname' => $participant->fullname,
                    'image_path' => $participant->image_path,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'otherParticipants' => $otherParticipants
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch private conversations', 'exception' => $e->getMessage()], 500);
    }
}


}
