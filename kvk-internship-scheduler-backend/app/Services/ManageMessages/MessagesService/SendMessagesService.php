<?php

namespace App\Services\ManageMessages\MessagesService;

use App\Contracts\Roles\RolePermissions;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Models\Message;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;


class SendMessagesService extends BaseService
{
    public function rules(): array
    {
        return [
            'conversation_id' => 'required|integer|exists:messages,id',
            'message' => 'required|string',
        ];
    }

    public function data(): array
    {
        return [
            'conversation_id' => $this->request['conversation_id'],
            'message' => $this->request['message']
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
        $validator = Validator::make($this->request->all(), [
            'conversation_id' => 'required|integer|exists:conversations,id',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $validated = $validator->validated();

        try {
            // Retrieve the currently authenticated user via JWT
            $user = JWTAuth::parseToken()->authenticate();

            $message = new Message([
                'conversation_id' => $validated['conversation_id'],
                'user_id' => $user->id, 
                'message' => $validated['message'],
                'created_by' => $user->id,
            ]);

            $message->save();

            return response()->json([
                'message' => 'Message sent successfully',
                'data' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send message', 'exception' => $e->getMessage()], 500);
        }
}
}