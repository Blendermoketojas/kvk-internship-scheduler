<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Services\ManageUserProfile\GetUserProfileByIdService;
use App\Services\ManageUserProfile\SearchUserProfilesByRoleService;
use App\Services\ManageUserProfile\SearchUserProfilesService;
use App\Services\ManageUserProfile\UpdateUserProfilePictureService;
use App\Services\ManageUserProfile\UpdateUserProfileService;
use App\Services\ManageUserProfile\GetGroupUsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    public function getProfile(): JsonResponse
    {
        $userProfile = auth()->user()->userProfile()->with('user')->first();
        return response()->json($userProfile);
    }

    /**
     * @throws ValidationException
     */
    public function searchProfilesByRole(Request $request): JsonResponse
    {
        return (new SearchUserProfilesByRoleService($request))->execute();
    }

    /**
     * @throws ValidationException
     */
    public function searchUserProfiles(Request $request): JsonResponse
    {
        return (new SearchUserProfilesService($request))->execute();
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

    /**
     * @throws ValidationException
     */
    public function getUserProfileById(Request $request): JsonResponse
    {
        return (new GetUserProfileByIdService($request))->execute();
    } 
      /**
     * @throws ValidationException
     */
    public function getGroupUsersProfile(Request $request): JsonResponse
    {
        return (new GetGroupUsersService($request))->execute();
    }
}
