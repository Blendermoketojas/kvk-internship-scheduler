<?php

namespace App\Services\ManageMessages\ConversationService;

use App\Contracts\Roles\RolePermissions;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Models\UserProfile;
use App\Models\Message;

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
        // Assuming 'user_id' in the messages table corresponds to 'user_id' in the user profile
        // and that your UserProfile model is correctly linked to the User model which is authenticated
        $userProfileId = $this->user->id;

        // Fetch conversations directly without needing to find the UserProfile again
        $conversations = Message::where('user_id', $userProfileId)
            ->with('conversation') // Assuming Message model has a 'conversation' relationship defined
            ->get()
            ->pluck('conversation')
            ->unique('id');

        // Return the conversations in the response
        return response()->json([
            'message' => 'Conversations fetched successfully',
            'conversations' => $conversations
        ]);
    } catch (\Exception $e) {
        // Log the error and return an error response
       
        return response()->json(['error' => 'Failed to fetch conversations'], 500);
    }
}
}
