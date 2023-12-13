<?php

namespace App\Services\ManageAuth;

use App\Contracts\Roles\Role;
use App\Models\Company;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterExternalUserService extends BaseService
{
    public function rules(): array
    {
        return ['firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'companyId' => 'nullable|integer|exists:companies,id',
            'roleId' => 'required|integer|exists:roles,id',
            'studentGroupId' => 'nullable|integer|exists:student_groups,id'
        ];
    }

    public function data(): array
    {
        return ['firstName' => $this->request['firstName'],
            'lastName' => $this->request['lastName'],
            'email' => $this->request['email'],
            'companyId' => $this->request['companyId'],
            'roleId' => $this->request['roleId'],
            'studentGroupId' => $this->request['studentGroupId']
        ];
    }

    public function permissions(): array
    {
        return [Role::PRODEKANAS];
    }

    /**
     * @throws ValidationException
     */
    function execute() : JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $company = Company::find($this->data()['companyId']);
        $password = fake()->password;

        $user = User::create([
            'email' => $this->data()['email'],
            'password' => Hash::make($password),
        ]);

        $userProfile = $user->userProfile()->create([
            'first_name' => $this->data()['firstName'],
            'last_name' => $this->data()['lastName'],
            'fullname' => $this->data()['firstName'] . ' ' . $this->data()['lastName'],
            'email' => $this->data()['email'],
            'image_path' => '/storage/profile_pictures/placeholder/profile_pic_placeholder.png'
        ]);

        if ($this->data()['companyId'] != null && $this->data()['roleId'] === Role::MENTORIUS->value)
            $userProfile->company_id = $this->data()['companyId'];
        else if ($this->data()['studentGroupId'] != null && $this->data()['roleId'] === Role::STUDENTAS->value)
            $userProfile->student_group_id = $this->data()['studentGroupId'];

        $userProfile->company()->associate($company);
        $userProfile->save();

        // response
        return response()->json(['success' => true, 'message' => 'External user registration is successful',
            'temporary_password' => $password]);
    }
}
