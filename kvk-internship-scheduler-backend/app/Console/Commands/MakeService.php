<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates structural service class';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $stub = file_get_contents(resource_path('stubs/service-class.stub'));

        $stub = str_replace('{{className}}', $name, $stub);

        $path = app_path("Services/{$name}.php");

        if (!file_exists($path)) {
            file_put_contents($path, $stub);
            $this->info("Service class created successfully!");
        } else {
            $this->error("Class already exists!");
        }
    }
}
