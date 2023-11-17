<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company_names = ['Klaipėdos valstybinė kolegija', 'BSS IT',
            'Inverta', 'Vlantana', 'Creative Partner',
            'Cherry Team', 'Oracle', 'Lakštinio superiniai puslapėliai uab'];

        foreach ($company_names as $value) {
            DB::table('company')->insert([
                'company_name' => $value,
            ]);
        }
    }
}
