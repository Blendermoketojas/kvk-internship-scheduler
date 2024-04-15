<?php

namespace Database\Factories;

use App\Models\Internship;
use App\Models\Company; // Ensure you have a Company model and its factory
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class InternshipFactory extends Factory
{
    protected $model = Internship::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'company_id' => Company::factory(), 
            'date_from' => '2021-01-01',
            'date_to' => '2021-06-01',
            'is_active' => 1,
            'created_by' => User::factory(),
           
        ];
    }
}
