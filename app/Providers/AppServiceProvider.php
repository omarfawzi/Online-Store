<?php

namespace App\Providers;

use App\Cartproduct;
use App\Customer;
use App\Notification;
use App\Order;
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
            if (Auth::guard('web')->check()) {
//                $orders = null ;
//                if (Auth::guard('web')->user()->type == 'admin')
//                    $orders = Order::with(['orderdetails'])->get();
//                else
//                    $orders = Order::with(['orderdetails'=>function($query){
//                        $query->where('supplierID',Auth::guard('web')->user()->id);
//                    }]);
//                //dd($orders);
                $myNotifications = Notification::where('userID', Auth::guard('web')->user()->id)->orderBy('timestamp','desc')->paginate(10);
                $unseenCounter = count(Notification::where('userID', Auth::guard('web')->user()->id)->where('seen',0)->get());
                $view->with('myNotifications', $myNotifications)->with('unseenCounter',$unseenCounter);
            }
            if(Auth::guard('customer')->check()){
                $customer = Customer::find(Auth::guard('customer')->user()->customerID);
                $orders = Order::where('customerID',Auth::guard('customer')->user()->customerID)->get();
                $cartProducts = Cartproduct::where('customerID',Auth::guard('customer')->user()->customerID)
                    ->with(['product','color','color.sizes'])->get();
                $cartTotalPrice = 0.0;
                $itemsCount = count($cartProducts);
                foreach ($cartProducts as $cartProduct){
                    $cartTotalPrice += $cartProduct->product->price * $cartProduct->quantity;
                }
                $view->with(['cartTotalPrice'=>$cartTotalPrice,'cartItemsCount'=>$itemsCount,'customer'=>$customer,'myOrders'=>$orders]);

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
