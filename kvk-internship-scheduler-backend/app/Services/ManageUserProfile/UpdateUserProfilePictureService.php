<?php

namespace App\Services\ManageUserProfile;

use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UpdateUserProfilePictureService extends BaseService
{
    public function rules(): array
    {
        return ['image' => 'required|image|max:2048'];
    }

    public function data(): array
    {
        return ['image' => $this->request['image']];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);


        // logic execution

        $path = $this->request->file('image')->store('profile_pictures', 'public');

        Log::info($path);

        $this->user->image_path = '/storage/' . $path;

        Log::info($this->user->image_path);

        $this->user->save();

        // response

        return response()->json(['message' => 'Profile picture updated successfully.', 'success' => true]);
    }
}
