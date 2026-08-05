<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with([
                    'unreadCount'  => Auth::user()->unreadNotifications()->count(),
                    'recentNotifs' => Auth::user()->unreadNotifications()->latest()->take(5)->get(),
                ]);
            } else {
                $view->with(['unreadCount' => 0, 'recentNotifs' => collect()]);
            }
        });
    }
}