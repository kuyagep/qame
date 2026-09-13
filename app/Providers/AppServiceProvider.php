<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


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
        Paginator::useBootstrapFour();
        // Implicitly grant "Super Admin" role all permissions
        // This interceptor will return true before regular checks happen
        Gate::before(function ($user, $ability) {
            return $user->hasRole('SuperAdmin') ? true : null;
        });

        // Pass the unread notifications to the AdminLTE top navigation layout component
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $view->with('unreadNotifications', $user->unreadNotifications->take(5));
                $view->with('unreadNotificationsCount', $user->unreadNotifications->count());
            }
        });
    }
}
