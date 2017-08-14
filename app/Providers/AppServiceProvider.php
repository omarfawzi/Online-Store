<?php

namespace App\Providers;

use App\Cartproduct;
use App\Notification;
use Illuminate\Routing\Route;
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
    private $categories ;
    public function setCategories()
    {
        $this->categories['Men'] = ['Shirts','T-Shirts','Trousers','Coats','Jackets','Footwear'];
        $this->categories['Women'] = ['Dresses','Skirts','Shirts','T-Shirts','Trousers','Coats','Jackets','Footwear'];
        $this->categories['Girls'] = ['Shirts','T-Shirts','Trousers','Coats','Jackets','Footwear','Pyjamas'];
        $this->categories['Boys'] = ['Dresses','Skirts','Shirts','T-Shirts','Trousers','Coats','Jackets','Footwear','Pyjamas'];
    }
    public function boot()
    {
        $this->setCategories();
        Schema::defaultStringLength(191);
        \View::composer('*', function ($view) {
            if (Auth::check()) {
                $myNotifications = Notification::where('userID', auth()->user()->id)->orderBy('timestamp','desc')->paginate(10);
                $unseenCounter = count(Notification::where('userID', auth()->user()->id)->where('seen',0)->get());
                $view->with('myNotifications', $myNotifications)->with('unseenCounter',$unseenCounter);
            }
            if(Auth::guard('customer')->check()){
                $cartProducts = Cartproduct::where('customerID',Auth::guard('customer')->user()->customerID)
                    ->with(['product'])->get();
                $cartTotalPrice = 0.0;
                $itemsCount = count($cartProducts);
                foreach ($cartProducts as $cartProduct){
                    $cartTotalPrice += $cartProduct->product->price * $cartProduct->quantity;
                }
                $view->with('cartTotalPrice',$cartTotalPrice)->with('cartItemsCount',$itemsCount);
            }
            $view->with('categoriesWeb',$this->categories);
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
