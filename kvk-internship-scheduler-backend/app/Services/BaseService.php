<?php

namespace App\Services;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

abstract class BaseService
{
    /**
     * The user who calls the service.
     */
    protected UserProfile $user;

    protected Request $request;

    /**
     * If resource does not require user to be authenticated.
     * Default - true (needs to be authenticated)
     */
    protected bool $authentication = true;

    function __construct(Request $request)
    {
        if ($this->authentication) $this->user = auth()->user()->userProfile;
        $this->request = $request;
    }

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [];
    }

    public function data(): array
    {
        return [];
    }

    /**
     * Get the permissions that users need to execute the service.
     */
    public function permissions(): array
    {
        return [];
    }

    public function validateRules()
    {
       Validator::make($this->data(), $this->rules())->validate();

        // If the permissions array is empty, allow all
        if (empty($this->permissions())) {
            return true;
        }

        // Check if the user's role matches any of the specified permissions
        foreach ($this->permissions() as $permission) {
            if ($permission->equalsValue($this->user->role_id)) {
                return true;
            }
        }

        // If no match is found, deny access
        return response()->json('Unauthorized', 401);
    }


    abstract function execute();
}
