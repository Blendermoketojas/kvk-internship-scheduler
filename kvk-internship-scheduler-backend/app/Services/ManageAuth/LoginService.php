<?php

namespace App\Services\ManageAuth;

use App\Exceptions\ModelNotProvidedInServiceException;
use App\Helpers\Cookie\CookieHelper;
use App\Helpers\TokenHelper\TokenHelper;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginService extends BaseService
{
    protected bool $authentication = false;

    public function rules(): array
    {
        return ['email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'];
    }

    public function data(): array
    {
        return ['email' => $this->request['email'],
            'password' => $this->request['password']];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     * @throws ModelNotProvidedInServiceException
     */
    function execute(): JsonResponse
    {
        // input validation

        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        if (!$token = auth()->attempt($this->data())) {
            return response()->json(['error' => 'Wrong credentials', 'success' => false], 401);
        }

        $user = User::with('userProfile')->find(Auth::id());
        $cookie = (new CookieHelper())->getConfiguredCookie($token);

        // response
        return (new TokenHelper())->respondWithToken($token, $user->userProfile)->withCookie($cookie);
    }
}
