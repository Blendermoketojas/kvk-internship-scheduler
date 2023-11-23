<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs necessary commands for application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating jwt secret, storage link.');

        Artisan::call('jwt:secret');
        Artisan::call('storage:link');

        $this->info('Done!');

        return 0;
    }
}
