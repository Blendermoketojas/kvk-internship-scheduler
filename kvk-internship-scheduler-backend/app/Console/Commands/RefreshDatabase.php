<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the database and seed the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Drop all tables and re-run all migrations
        Artisan::call('migrate:refresh');
        $this->info('Database has been refreshed.');

        // Seed the database
        Artisan::call('db:seed --class=MainSeeder');
        $this->info('Database has been seeded.');

        return 0;
    }
}
