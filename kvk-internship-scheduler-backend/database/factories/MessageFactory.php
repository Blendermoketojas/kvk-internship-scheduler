<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'conversation_id' => Conversation::factory(),  // Automatically create a conversation if not specified
            'user_id' => User::factory(),  // Automatically create a user if not specified
            'message' => $this->faker->sentence,  // Generate a random sentence
            'created_at' => now(),  // Use the current time
            'updated_at' => now()  // Use the current time
        ];
    }
}
