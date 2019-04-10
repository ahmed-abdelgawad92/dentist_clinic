<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\WorkingTime' => 'App\Policies\WorkingTimePolicy',  
        'App\User' => 'App\Policies\UserPolicy',  
        'App\User' => 'App\Policies\UserPolicy',  
        'App\UserLog' => 'App\Policies\UserLogPolicy',  
        'App\UserLog' => 'App\Policies\UserLogPolicy',  
        'App\RecycleBin' => 'App\Policies\RecycleBinPolicy',  
        'App\RecycleBin' => 'App\Policies\RecycleBinPolicy',  
        'App\Patient' => 'App\Policies\RecycleBinPolicy',  
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
