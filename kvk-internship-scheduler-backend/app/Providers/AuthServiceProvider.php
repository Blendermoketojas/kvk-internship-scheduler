<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
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
