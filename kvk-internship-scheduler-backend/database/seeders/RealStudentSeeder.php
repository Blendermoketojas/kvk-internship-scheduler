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
        $index = 1;
        $groupIndex = 1;
        foreach (getGroups() as $group) {
            Log::info($group);
            foreach (getStudentsFromGroup($group) as $student) {
                $firstName = explode(" ", $student)[0];
                $lastName = explode(" ", $student)[1];

                DB::table('users')->insert([
                    'email' => strtolower($firstName) . '.' . strtolower($lastName) . rand(1, 9999) . '@' . fake()->freeEmailDomain,
                    'email_verified_at' => Carbon::today(),
                    'password' => Hash::make('password'),
                    'created_at' => Carbon::today(),
                    'updated_at' => Carbon::today()
                ]);

                DB::table('userprofiles')->insert([
                    'user_id' => $index,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'fullname' => $student,
                    'role_id' => rand(1, 5),
                    'company_id' => rand(1, 30),
                    'address' => fake()->address,
                    'student_group_id' => $groupIndex,
                    'country' => 'Lietuva',
                    'description' => 'Studentas/ė Klaipėdos valstybinėje kolegijoje',
                    'image_path' => '\storage\app\public\profile_pictures\placeholder\profile_pic_placeholder.png',
                    'created_at' => Carbon::today(),
                    'updated_at' => Carbon::today()
                ]);
                $index++;
            }
            $groupIndex++;
        }
    }
}
