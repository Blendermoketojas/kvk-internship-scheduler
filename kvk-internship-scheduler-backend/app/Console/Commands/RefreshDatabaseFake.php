<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshDatabaseFake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh {--real}';

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
        $this->info('Refreshing database...');
        Artisan::call('migrate:refresh');
        $this->info('Database has been refreshed.');

        // Seed the database
        $this->info('Seeding database...');

        if ($this->option('real')) Artisan::call('db:seed --class=RealSeeder');
        else Artisan::call('db:seed --class=FakeSeeder');

        $this->info('Database has been seeded.');

        return 0;
    }
}
