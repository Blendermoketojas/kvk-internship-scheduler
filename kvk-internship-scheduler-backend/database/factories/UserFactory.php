<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function withProfile()
    {
        return $this->afterCreating(function (User $user) {
            UserProfile::factory()->create([
                'user_id' => $user->id,
                'role_id'=> 1,
            ]);
        });
    }
}
