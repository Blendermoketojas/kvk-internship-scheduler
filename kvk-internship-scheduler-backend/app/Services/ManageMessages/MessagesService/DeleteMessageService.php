<?php

namespace App\Services\ManageMessages\MessagesService;

use App\Contracts\Roles\RolePermissions;
use App\Contracts\Roles\Role;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use App\Models\Message;
use Tymon\JWTAuth\Facades\JWTAuth; 
use Illuminate\Support\Facades\Validator;
use App\Exceptions\ModelNotProvidedInServiceException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class DeleteMessageService extends BaseService
{
    protected string $modelClass = Message::class;

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:messages,id',
        ];
    }

    public function data(): array
    {
        return [
            'id' => $this->request['id']
        ];
    }

    public function permissions(): array
    {
        return [Role::SELF];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution
        $this->modelInstance->delete();

        // response
        return response()->json(['success' => true]);
    }
    protected function retrieveModelInstance(): ?Model {
        $messageId = $this->data()['id']; 
        return Message::find($messageId);
    }


}
