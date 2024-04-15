<?php

namespace Database\Factories;

use App\Models\StudentGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group_identifier' => $this->faker->unique()->regexify('[A-Z]{4}[0-9]{3}'), 
            'field_of_study' => $this->faker->randomElement(['Informatics', 'Biology', 'Chemistry', 'Physics', 'Mathematics'])
        ];
    }
}
