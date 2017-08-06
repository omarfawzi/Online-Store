<?php

namespace App\Providers;

use App\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view)
        {
            $myNotifications =[];
            if(Auth::check()) {
                $myNotifications = Notification::where('userID', auth()->user()->id)->get();
            }
            $view->with('myNotifications', $myNotifications);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}