<?php

namespace App\Services;

use App\Contracts\Roles\RolePermissions;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use function Webmozart\Assert\Tests\StaticAnalysis\email;

abstract class BaseService
{
    /**
     * The user who calls the service.
     */
    public UserProfile $user;

    protected Request $request;

    function __construct(Request $request)
    {
        $this->user = auth()->user()->userProfile;
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
            Log::info('Tuscias array permissionu');
            return true;
        }

        // Check if the user's role matches any of the specified permissions
        foreach ($this->permissions() as $permission) {
            if ($permission->equalsValue($this->user->role_id)) {
                Log::info('taip as cia foreache');
                return true;
            }
        }

        // If no match is found, deny access
        return response()->json("Unauthorized", 401);
    }


    abstract function execute();
}
