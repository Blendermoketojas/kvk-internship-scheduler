<?php

namespace App\Helpers\TokenHelper;

use Illuminate\Http\JsonResponse;

class TokenHelper
{
    public function respondWithToken($token, $user): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'success' => true,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }}
