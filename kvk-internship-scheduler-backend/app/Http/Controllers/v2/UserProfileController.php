<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function getProfile()
    {
        $userProfile = auth()->user()->userProfile()->with('user')->first();
        return response()->json($userProfile);
    }

    public function updateProfilePicture(Request $request) {
        $request->validate(['image' => 'required|image|max:2048']);

        $userProfile = auth()->user()->userProfile;
        $path = $request->file('image')->store('profile_pictures', 'public');

        $userProfile->image_path = $path;
        $userProfile->save();

        return response()->json(['message' => 'Profile picture updated successfully.']);
        $userProfile = auth()->user()->userProfile()->with('user')->get();
        return response()->json($userProfile[0]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user = auth()->user();
        $userProfile = $user->userProfile;

        if (!strcmp($userProfile->email, $request->input('email'))) {
            $request->validate([
                'email' => 'required|string|email|max:255|unique:users',
            ]);
        }

        if (!$userProfile) {
            return response()->json(['error' => 'User profile does not exist'], 404);
        }

        $isUpdated = $userProfile->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'fullname' => $request->input('first_name') . ' ' . $request->input('last_name'),
            'description' => $request->input('description'),
            'company_id' => $request->input('company_id'),
            'country' => $request->input('country'),
            'address' => $request->input('address'),
            'image_path' => $request->input('img_path'),
        ]);

        if ($isUpdated) {
            if (!Hash::check($request->input('password'), auth()->user()->getAuthPassword())) {
                $user->update([
                    'password' => Hash::make($request->input('password')),
                    'email' => $request->input('email')
                ]);
            }
        }

        $userProfile['user'] = ['email' => $user['email']];
        return response()->json($userProfile);
    }
}
