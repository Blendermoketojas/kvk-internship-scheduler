<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ["I17-3" => "Informatika", "I19-2" => "Informatika",
            "INI-11" => "Informatikos inžinerija", "DT 12-1" => "Dietologija",
            "ATI 51-1" => "Automobilių inžinerija"];

        foreach ($roles as $key => $value) {
            DB::table('student_groups')->insert([
                'group_identifier' => $key,
                'field_of_study' => $value,
            ]);
        }
    }
}
