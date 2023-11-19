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
        for ($i = 0; $i < 10; $i++) {
            $firstName = fake()->firstName;
            $lastName = fake()->lastName;

            DB::table('users')->insert([
                'name' => $firstName . ' ' . $lastName,
                'email' => strtolower($firstName) . '.' . strtolower($lastName) . '@' . fake()->freeEmailDomain,
                'password' => Hash::make('password'),
                'role_id' => rand(1, 4),
                'company_id' => rand(1, 6),
                'phone_number' => fake()->phoneNumber,
                'photo_path' => fake()->imageUrl
            ]);
        }
    }
}
