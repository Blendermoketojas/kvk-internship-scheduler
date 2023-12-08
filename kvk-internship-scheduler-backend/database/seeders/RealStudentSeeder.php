<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

require 'data/RealData.php';

class RealStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */



    public function run()
    {
        $rolesNames = ["Prodekanas", "Katedros vedėjas", "Praktikos vadovas", "Mentorius", "Studentas"];

        // create students from real data

        $index = 1;
        $groupIndex = 1;
        foreach (getGroups() as $group) {
            Log::info($group);
            foreach (getStudentsFromGroup($group) as $student) {
                $firstName = explode(" ", $student)[1];
                $lastName = explode(" ", $student)[0];

                $this->createUserCredentials($firstName, $lastName);
                $this->createUserProfile($index, $firstName, $lastName, $rolesNames[4], $groupIndex);

                $index++;
            }
            $groupIndex++;
        }

        // TODO: TEMPORARY SOLUTION: BEFORE THE REAL PRESENTATION POPULATE WITH REAL PEOPLE (IF NEEDED)

        for ($i = $index; $i < $index + 30; $i++) {
            $firstName = fake()->firstName;
            $lastName = fake()->lastName;
            $roleId = rand(1, 4);

            $this->createUserCredentials($firstName, $lastName);
            $this->createUserProfile($i, $firstName, $lastName, $rolesNames[$roleId], 0, $roleId);
        }
    }

    public function createUserProfile($index, $firstName, $lastName, $roleName, $groupIndex = 0, $roleId = 5)
    {
        DB::table('userprofiles')->insert([
            'user_id' => $index,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'fullname' => $firstName . ' ' .$lastName,
            'role_id' => $roleId,
            'company_id' => rand(1, 30),
            'address' => fake()->address,
            'student_group_id' => $groupIndex,
            'country' => 'Lietuva',
            'description' => "$roleName Klaipėdos valstybinėje kolegijoje",
            'image_path' => '\storage\app\public\profile_pictures\placeholder\profile_pic_placeholder.png',
            'created_at' => Carbon::today(),
            'updated_at' => Carbon::today()
        ]);
    }

    public function createUserCredentials($firstName, $lastName)
    {
        DB::table('users')->insert([
            'email' => strtolower($firstName) . '.' . strtolower($lastName) . rand(1, 9999) . '@' . fake()->freeEmailDomain,
            'email_verified_at' => Carbon::today(),
            'password' => Hash::make('password'),
            'created_at' => Carbon::today(),
            'updated_at' => Carbon::today()
        ]);
    }
}
