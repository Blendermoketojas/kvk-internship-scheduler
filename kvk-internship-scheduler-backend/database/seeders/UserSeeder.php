<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 100; $i++) {
            $firstName = fake()->firstName;
            $lastName = fake()->lastName;

            DB::table('users')->insert([
                'email' => strtolower($firstName) . '.' . strtolower($lastName) . rand(1, 9999) . '@' . fake()->freeEmailDomain,
                'email_verified_at' => fake()->dateTime,
                'password' => Hash::make('password'),
                'created_at' => fake()->dateTime,
                'updated_at' => fake()->dateTime
            ]);

            DB::table('user_profile')->insert([
                'user_id' => $i,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'fullname' => $firstName . ' ' . $lastName,
                'role_id' => rand(1, 5),
                'company_id' => rand(1,  30),
                'address' => fake()->address,
                'country' => fake()->country,
                'description' => fake()->text,
                'image_path' => fake()->imageUrl,
                'created_at' => fake()->dateTime,
                'updated_at' => fake()->dateTime
            ]);
        }
    }
}
