<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized', 'data' => $request->only(['email', 'password'])], 401);
        }

        $cookie = Cookie::make(
            'jwt',
            $token,
            60 * 24 * 7,
            '/',
            null,
            true,
            true,
            false,
            'None'
        );

        $user = User::find(Auth::id());
        return $this->respondWithToken($token, $user->userProfile)->withCookie($cookie);
    }

    public function register(Request $request) : JsonResponse
    {
        $credentials = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'company_id' => 'required',
        ]);

        $company = Company::find($request->company_id);

        if ($company == null) {
            return response()->json(['error' => 'User must be assigned to an organization']);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (!$user) {
            return response()->json(['error' => 'User creation was unsuccessful for unknown reasons']);
        }

        $token = auth()->login($user);
        $cookie = Cookie::make(
            'jwt',
            $token,
            60 * 24 * 7,
            '/',
            null,
            false,
            true,
            false,
            'Lax'
        );

// Create UserProfile and associate it with the User
        $userProfile = $user->userProfile()->create([
            'company_id' => $request->company_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        $userProfile->company()->associate($company);
        $userProfile->save();

        return $this->respondWithToken($token, $userProfile)->withCookie($cookie);
    }

    public function checkAuth(Request $request)
    {
        // Attempt to get the authenticated user using the jwt guard
        $user = auth('api')->user();

        // Check if a user was found
        $isAuthenticated = $user !== null;

        return response()->json([
            'isAuthenticated' => $isAuthenticated
        ]);
    }

    protected function respondWithToken($token, $user): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'status' => 'success',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }
}
