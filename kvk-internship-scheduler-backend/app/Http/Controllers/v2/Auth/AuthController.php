<?php

namespace App\Http\Controllers\v2\Auth;

use App\Exceptions\ModelNotProvidedInServiceException;
use App\Http\Controllers\Controller;
use App\Services\ManageAuth\LoginService;
use App\Services\ManageAuth\RegisterExternalUserService;
use App\Services\ManageAuth\RegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        return (new LoginService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function register(Request $request) : JsonResponse
    {
        return (new RegisterService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function registerExternalUser(Request $request) : JsonResponse
    {
        return (new RegisterExternalUserService($request))->execute();
    }

    /**
     * @throws ValidationException
     * @throws ModelNotProvidedInServiceException
     */
    public function logout(Request $request): JsonResponse
    {
        return (new LoginService($request))->execute();
    }
}
