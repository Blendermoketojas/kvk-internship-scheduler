<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'company_name' => $this->faker->company,
            'created_by' => User::factory(), // Assuming you want to associate each company with a user
            'created_at' => now(), // 'created_at' and 'updated_at' will be automatically handled by Laravel if you're using Eloquent
            'updated_at' => now()
        ];
    }
}
