<?php

namespace App\Services\ManageMessages\ConversationService;

use App\Contracts\Roles\RolePermissions;
use App\Services\BaseService;
use App\Models\Conversation;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; 
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Message;
use Illuminate\Support\Facades\Validator;


class CreateConversationService extends BaseService
{
 

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:private,group',
            'userProfileId' => 'required|array',
            'userProfileId.*' => 'exists:userprofiles,id',
            'message' => 'string|nullable',
        ];
    }

    public function data(): array
    {
        return [
            'name' => $this->request['name'],
            'type' => $this->request['type'],
            'userProfileId' => $this->request['userProfileId'],
            'user_id' => $this->user->id
        ];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    function execute() : JsonResponse
    {
        $validator = Validator::make($this->request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Extract validated data
        $validated = $validator->validated();

        // Create the conversation
        $conversation = new Conversation([
            'name' => $validated['name'],
            'type' => $validated['type']
        ]);
        $conversation->save();

        // Associate conversation with user profiles
        // Logic to include the initiator's user profile ID if not already included
        $userProfileIds = array_unique(array_merge($validated['userProfileId'], [$this->user->id]));
        $conversation->userProfiles()->attach($userProfileIds);

        // Create a message only if a message content is provided
        if (!empty($validated['message'])) {
            $message = new Message([
                'conversation_id' => $conversation->id,
                'user_id' => $this->user->id, // Initiating user's ID
                'message' => $validated['message'],
            ]);
            $message->save();
        }

        return response()->json([
            'message' => 'Conversation created successfully' . (!empty($validated['message']) ? ' with initial message' : ''),
            'conversation' => $conversation
        ], 201);
    }
}

