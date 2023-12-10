<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RealGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ["SL36" => "Medicina", "SL37" => "Medicina"];

        foreach ($roles as $key => $value) {
            DB::table('student_groups')->insert([
                'group_identifier' => $key,
                'field_of_study' => $value,
            ]);
        }
    }
}
