<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CompanySeeder::class,
            RoleSeeder::class,
            StudentGroupsSeeder::class,
            UserSeeder::class,
        ]);
    }
}
