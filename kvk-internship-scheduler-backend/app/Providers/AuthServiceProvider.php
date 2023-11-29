<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Document;
use App\Models\File;
use App\Policies\DocumentPolicy;
use App\Policies\FilePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Internship;

use App\Policies\InternshipPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Internship::class => InternshipPolicy::class,
        File::class => FilePolicy::class,
        Document::class => DocumentPolicy::class
    ];

    /**
     * Register any authentication / authorization Services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
