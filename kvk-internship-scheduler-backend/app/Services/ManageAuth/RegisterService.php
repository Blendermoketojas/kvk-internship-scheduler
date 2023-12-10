<?php

namespace App\Services\ManageAuth;

use App\Helpers\Cookie\CookieHelper;
use App\Helpers\TokenHelper\TokenHelper;
use App\Models\Company;
use App\Models\User;
use App\Services\BaseService;
use App\Services\NoAuthBaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegisterService extends BaseService
{
    protected bool $authentication = false;

    public function rules(): array
    {
        return ['first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'company_id' => 'required|integer|exists:company,id'];
    }

    public function data(): array
    {
        return ['first_name' => $this->request['firstName'],
            'last_name' => $this->request['lastName'],
            'email' => $this->request['email'],
            'password' => $this->request['password'],
            'company_id' => $this->request['companyId']];
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

        Log::info($this->data()['company_id']);


        $company = Company::find($this->data()['company_id']);

        $user = User::create([
            'email' => $this->data()['email'],
            'password' => Hash::make($this->data()['password']),
        ]);

        // attempting login

        $token = auth()->login($user);
        $cookie = (new CookieHelper())->getConfiguredCookie($token);

        $userProfile = $user->userProfile()->create([
            'company_id' => $this->data()['company_id'],
            'first_name' => $this->data()['first_name'],
            'last_name' => $this->data()['last_name'],
            'fullname' => $this->data()['first_name'] . ' ' . $this->data()['last_name'],
            'email' => $this->data()['email'],
        ]);

        $userProfile->company()->associate($company);
        $userProfile->save();

        // response
        return (new TokenHelper())->respondWithToken($token, $user->userProfile)->withCookie($cookie);
    }
}
