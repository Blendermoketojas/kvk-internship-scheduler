<?php

namespace App\Services\ManageUserProfile;

use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdateUserProfileService extends BaseService
{
    public function rules(): array
    {
        return ['first_name' => 'string',
            'last_name' => 'string',
            'fullname' => 'string',
            'description' => 'string',
            'company_id' => 'integer',
            'country' => 'string',
            'address' => 'string',
            'image_path' => $request->input('img_path')];
    }

    public function data(): array
    {
        return [];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    function execute(): JsonResponse
    {
        // input validation

        $validation = $this->validateRules();
        if (!is_bool($validation)) {
            return $validation;
        }

        // logic execution

        // response
        return response()->json('Not implemented');
    }
}
