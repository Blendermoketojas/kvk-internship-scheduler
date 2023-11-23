<?php

namespace App\Console\Commands;

use App\Models\Internship;
use Illuminate\Console\Command;

class DeactivateInternships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'internships:deactivate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate internships past their end date.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Deactivating internships past their date...');

        Internship::where('date_to', '<', now())
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $this->info('Done!');
    }
}
