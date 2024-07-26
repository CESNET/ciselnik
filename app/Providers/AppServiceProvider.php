<?php

namespace App\Providers;

use App\Ldap\Organization;
use App\Ldap\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('organization', function ($value) {
            try {
                return Organization::findOrFail($value);
            } catch (\LdapRecord\Models\ModelNotFoundException) {
                abort(404);
            }
        });

        Route::bind('unit', function ($value) {
            try {
                return Unit::findOrFail($value);
            } catch (\LdapRecord\Models\ModelNotFoundException) {
                abort(404);
            }
        });

        if (! App::environment('production')) {
            Model::preventLazyLoading();
            Mail::alwaysTo('laravel@example.org');
        }

        Gate::define('do-everything', function (User $user) {
            return $user->admin;
        });
    }
}
