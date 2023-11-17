<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [1 => "PRODEKANAS", 2 => "KADEDROS_VEDEJAS",
            3 => "PRAKTIKOS_VADOVAS", 4 => "MENTORIUS",
            5 => "STUDENTAS"];

        foreach ($roles as $key => $value) {
            DB::table('roles')->insert([
                'authority_level' => $key,
                'role_title' => $value,
            ]);
        }
    }
}
