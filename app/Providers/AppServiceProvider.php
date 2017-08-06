<?php

namespace App\Providers;

use App\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Way\Generators\GeneratorsServiceProvider;
use Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        \View::composer('*', function ($view) {
            if (Auth::check()) {
                $myNotifications = Notification::where('userID', auth()->user()->id)->orderBy('timestamp','desc')->paginate(10);
                $unseenCounter = count(Notification::where('userID', auth()->user()->id)->where('seen',0)->get());
                $view->with('myNotifications', $myNotifications)->with('unseenCounter',$unseenCounter);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(GeneratorsServiceProvider::class);
            $this->app->register(MigrationsGeneratorServiceProvider::class);
        }
    }
}
