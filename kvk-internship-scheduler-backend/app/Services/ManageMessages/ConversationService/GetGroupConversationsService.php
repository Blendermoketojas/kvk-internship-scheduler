<?php

namespace App\Services\ManageMessages\ConversationService;

use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;

class GetGroupConversationsService extends BaseService
{
    public function rules(): array
    {
        return [];
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
    public function execute(): JsonResponse
    {
        // Ensure that the user is set and valid
        if (!$this->user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        try {
            $userId = auth()->id(); 

            // Fetch group conversations including their names
            $groupConversations = DB::table('conversation_user')
                ->join('conversations', 'conversation_user.conversation_id', '=', 'conversations.id')
                ->where('conversations.type', 'group')
                ->where('conversation_user.user_id', $userId)
                ->select('conversation_id', 'conversations.name as group_name')
                ->distinct()
                ->get();

            $groupParticipants = collect();

            foreach ($groupConversations as $groupConversation) {
                $conversationId = $groupConversation->conversation_id;
                $groupName = $groupConversation->group_name;
                
                // Fetch participants' UserProfile information for each group
                $groupUserIds = DB::table('conversation_user')
                    ->where('conversation_id', $conversationId)
                    ->pluck('user_id');

                $participantsInfo = UserProfile::whereIn('user_id', $groupUserIds)
                    ->get(['fullname', 'image_path']);

                $participants = $participantsInfo->map(function($participant) use ($conversationId, $groupName) {
                    return [
                        'conversation_id' => $conversationId,
                        'group_name' => $groupName,
                        'fullname' => $participant->fullname,
                        'image_path' => $participant->image_path,
                    ];
                });

                // Merge participants of each group into the main collection
                $groupParticipants = $groupParticipants->merge($participants);
            }

            return response()->json([
                'success' => true,
                'groupParticipants' => $groupParticipants
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch group conversations', 'exception' => $e->getMessage()], 500);
        }
    }

    
}