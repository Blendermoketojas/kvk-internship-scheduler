<?php

namespace App\Http\Controllers\v2\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Services\ManageAuth\LoginService;
use App\Services\ManageAuth\RegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
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

    public function logout(): JsonResponse
    {
        try {
            // Get the token from the request
            $token = JWTAuth::getToken();

            // Invalidate the token
            JWTAuth::invalidate($token);

            return response()->json(['success' => true, 'message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            // Something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
        }
    }
}
