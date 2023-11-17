<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function getProfile()
    {
        $userProfile = auth()->user()->userProfile;
        return response()->json($userProfile);
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = auth()->user();
        $userProfile = $user->userProfile;

        if (!$userProfile) {
            return response()->json(['error' => 'User profile does not exist'], 404);
        }

        $isUpdated = $userProfile->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'description' => $request->input('description'),
            'company_id' => $request->input('company_id'),
            'country' => $request->input('country'),
            'address' => $request->input('address'),
            'image_path' => $request->input('img_path')
        ]);

        if ($isUpdated) {
            if (!Hash::check($request->input('password'), auth()->user()->getAuthPassword())) {
                $user->update([
                    'password' => Hash::make($request->input('password')),
                    'email' => $request->input('email')
                ]);
            }
        }

        return response()->json($userProfile);
    }
}
