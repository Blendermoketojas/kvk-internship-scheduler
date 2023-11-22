<?php

namespace App\Services\ManageUserProfile;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdateUserProfileService extends BaseService
{
    public function rules(): array
    {
        return ['first_name' => 'string',
            'last_name' => 'string',
            'fullname' => 'string',
            'description' => 'string',
            'company_id' => 'integer',
            'country' => 'string',
            'address' => 'string',
            'email' => 'email',
        ];
    }

    public function data(): array
    {
        return ['first_name' => $this->request['first_name'],
            'last_name' => $this->request['last_name'],
            'fullname' => $this->request['first_name'] . ' ' . $this->request['last_name'],
            'description' => $this->request['description'],
            'company_id' => $this->request['company_id'],
            'country' => $this->request['country'],
            'address' => $this->request['address'],
            'email' => $this->request['email'],
            'password' => $this->request['password']];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    function execute(): JsonResponse
    {
        // input validation

        $validation = $this->validateRules();
        if (!is_bool($validation)) {
            return $validation;
        }

        // logic execution

        // update user profile with values
        $isUpdated = $this->user->update($this->data());

        // user credentials update
        $userCredentials = User::find($this->user->id);

        // check if email has been changed
        if (strcmp($userCredentials->email, $this->data()['email'])) {
            Validator::make([$this->data()['email']], ['email' => 'required|string|email|max:255|unique:users,email']);
            $userCredentials->update([
                'email' => $this->data()['email']
            ]);
        }

        // check if password has been changed
        if ($isUpdated) {
            if (!Hash::check($this->data()['password'], auth()->user()->getAuthPassword())) {
                Validator::make([$this->data()['password']], ['password' => 'required|string|min:6']);
                $userCredentials->update([
                    'password' => Hash::make($this->data()['password']),
                ]);
            }
        }

        // respond with email
        $this->user['user'] = $userCredentials;

        // response
        return response()->json($this->user);
    }
}
