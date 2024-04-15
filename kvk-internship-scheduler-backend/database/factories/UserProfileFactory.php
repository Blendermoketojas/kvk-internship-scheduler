<?php

namespace Database\Factories;

use App\Models\UserProfile; // Ensure this is the correct namespace for your UserProfile model
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition()
    {
        return [
            'user_id'=> User::factory(),
            'role_id' => 1, // Here is where role_id should be set
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'fullname' => $this->faker->name,
            // Other fields as needed
        ];
    }
}

