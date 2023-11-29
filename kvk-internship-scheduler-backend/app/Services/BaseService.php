<?php

namespace App\Services;

use App\Contracts\Roles\Role;
use App\Exceptions\ModelNotProvidedInServiceException;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;

abstract class BaseService
{
    /**
     * The user who calls the service.
     */
    protected UserProfile $user;

    protected Request $request;

    protected string $modelClass;
    protected ?Model $modelInstance = null;

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

    public function custom_permission(): bool
    {
        return false;
    }

    /**
     * @throws ModelNotProvidedInServiceException
     * @throws ValidationException
     */
    public function validateRules()
    {
       Validator::make($this->data(), $this->rules())->validate();

        // If the permissions array is empty, allow all
        if (empty($this->permissions())) {
            return true;
        }

        $allowAction = false;

        // Check if the user's role matches any of the specified permissions
        foreach ($this->permissions() as $permission) {
            if ($permission->equalsValue($this->user->role_id)) {
                $allowAction = true;
            }
        }

        if (in_array(Role::SELF, $this->permissions()))
        {
            $allowAction = $this->checkResourceOwnership();
        }

        // return if access is granted
        return $allowAction;
    }

    /**
     * Get the permissions that users need to execute the service.
     * @throws ModelNotProvidedInServiceException
     */
    private function checkResourceOwnership()
    {
        if (empty($this->modelClass)) {
            throw new ModelNotProvidedInServiceException(get_class());
        }

        // Retrieve the model instance if it hasn't been set already
        if (!$this->modelInstance) {
            $this->modelInstance = $this->retrieveModelInstance();
        }

        Log::info($this->modelInstance->created_by);

        if ($this->user->id != $this->modelInstance->created_by) {
            return false;
        }

        return true;
    }

    /**
     * Used for user's record ownership validation
     * Retrieves model instance. Then can be used with $modelInstance property
     */
    protected function retrieveModelInstance(): ?Model {
        return null;
    }

    abstract function execute();
}
