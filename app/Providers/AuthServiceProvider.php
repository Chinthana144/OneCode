<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\PageAccess;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //admin access
        Gate::define('access-admin', function($user){
            return $user->role_id === 1;
        });

        //home access
        Gate::define('access-home', function ($user) {
        $camp_id = Session::get('active_camp_id');
        $page_id = 1; // page_id for home page = 1

        return PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('view', 1)
            ->exists();
        });

        //invoice access
        Gate::define('access-invoice', function ($user) {
        $camp_id = Session::get('active_camp_id');
        $page_id = 2; // page_id for invoice page = 2

        return PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('view', 1)
            ->exists();
        });

        //reports access
        Gate::define('access-reports', function ($user) {
        $camp_id = Session::get('active_camp_id');
        $page_id = 6; // page_id for report page = 6

        return PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('view', 1)
            ->exists();
        });

        //controls access
        Gate::define('access-control', function ($user) {
        $camp_id = Session::get('active_camp_id');
        $page_id = 7; // page_id for control page = 7

        return PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('view', 1)
            ->exists();
        });

        //settings access
         Gate::define('access-setting', function ($user) {
        $camp_id = Session::get('active_camp_id');
        $page_id = 8; // page_id for setting page = 8

        return PageAccess::where('camp_id', $camp_id)
            ->where('user_id', $user->id)
            ->where('page_id', $page_id)
            ->where('view', 1)
            ->exists();
        });
    }
}
