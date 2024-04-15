<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'role_title' => $this->faker->unique()->randomElement([
                'PRODEKANAS',
                'KATEDROS_VEDEJAS',
                'PRAKTIKOS_VADOVAS',
                'MENTORIUS',
                'STUDENTAS',
            ]),
            // Remove the 'created_at' and 'updated_at' if your table does not have these columns
        ];
    }
}

