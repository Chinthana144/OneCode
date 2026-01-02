<?php

namespace App\Providers;

use App\Models\Camps;
use App\Models\CampUsers;
use App\Models\Customers;
use App\Models\Packages;
use App\Models\PageAccess;
use App\Models\Subscriptions;
use App\Models\User;
use App\Policies\CampPolicy;
use App\Policies\CampUserPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\PackagePolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\UserAccessPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
            // Define your policies here
            Customers::class => CustomerPolicy::class,
            Packages::class => PackagePolicy::class,
            Camps::class => CampPolicy::class,
            CampUsers::class => CampUserPolicy::class,
            User::class => UserPolicy::class,
            Subscriptions::class => SubscriptionPolicy::class,
            PageAccess::class => UserAccessPolicy::class,
        ];
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Paginator::useBootstrapFive();

    }
}
