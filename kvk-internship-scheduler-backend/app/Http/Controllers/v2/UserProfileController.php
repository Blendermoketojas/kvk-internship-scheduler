<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageUserProfile\UpdateUserProfilePictureService;
use App\Services\ManageUserProfile\UpdateUserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    public function getProfile()
    {
        $userProfile = auth()->user()->userProfile()->with('user')->first();
        return response()->json($userProfile);
    }

    /**
     * @throws ValidationException
     */
    public function updateProfilePicture(Request $request) : JsonResponse
    {
        return (new UpdateUserProfilePictureService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request): JsonResponse
    {
        return (new UpdateUserProfileService($request))->execute();
    }
}
