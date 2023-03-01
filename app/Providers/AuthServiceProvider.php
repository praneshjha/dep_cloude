<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\User;
use App\Policies\UserPolicy;
use App\Role;
use App\Policies\RolePolicy;
use App\Policies\CompanyProfilePolicy;
use App\Policies\DeparturePolicy;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        CompanyProfile::class => CompanyProfilePolicy::class,
        Departure::class => DeparturePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('user_view', 'App\Policies\UserPolicy@user_view');
        Gate::define('user_create', 'App\Policies\UserPolicy@user_create');
        Gate::define('user_edit', 'App\Policies\UserPolicy@user_edit');
        Gate::define('user_activate_inactivate', 'App\Policies\UserPolicy@user_activate_inactivate');

        Gate::define('role_view', 'App\Policies\RolePolicy@role_view');
        Gate::define('role_create', 'App\Policies\RolePolicy@role_create');
        Gate::define('role_edit', 'App\Policies\RolePolicy@role_edit');

        Gate::define('company_profile_view', 'App\Policies\CompanyProfilePolicy@company_profile_view');
        Gate::define('company_profile_edit', 'App\Policies\CompanyProfilePolicy@company_profile_edit');

        Gate::define('departure_view', 'App\Policies\DeparturePolicy@departure_view');
        Gate::define('departure_hold', 'App\Policies\DeparturePolicy@departure_hold');
        Gate::define('departure_booking_history', 'App\Policies\DeparturePolicy@departure_booking_history');
        Gate::define('departure_create', 'App\Policies\DeparturePolicy@departure_create');
        Gate::define('departure_edit', 'App\Policies\DeparturePolicy@departure_edit');

        //
        Passport::routes();
    }
}
