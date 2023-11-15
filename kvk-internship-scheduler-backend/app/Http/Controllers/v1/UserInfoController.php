<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function getUserInfo(Request $request)
    {
        $userId = $request->input('userId');

        $profile = UserProfile::where('user_id', $userId)->first();

        if ($profile) {
            return response()->json($profile);
        } else {
            return response()->json(['error' => 'Profile not found'], 404);
        }
    }

    public function updateUserInfo(Request $request)
    {
        $profileId = $request->input('profileId');
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $description = $request->input('description');

        $profile = UserProfile::where('user_id',$profileId)->first();

        if($profile){
            $profile->first_name = $firstName;
            $profile->last_name = $lastName;
            $profile->description = $description;
            $profile->save();
            return response()->json($profile);
        } else {
            return response()->json(['error' => 'Profile not found'], 404);
        }
    }
}
