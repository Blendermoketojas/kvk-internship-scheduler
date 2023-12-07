<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RealSeeder extends Seeder
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
            RealStudentSeeder::class,
        ]);
    }
}
