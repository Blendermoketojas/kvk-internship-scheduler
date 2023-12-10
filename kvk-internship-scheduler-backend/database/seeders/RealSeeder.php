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
            RealGroupSeeder::class,
            RoleSeeder::class,
            RealStudentSeeder::class,
        ]);
    }
}
