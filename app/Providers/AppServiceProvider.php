<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Traits\ApiWebsite;
use App\Models\Cart;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    use ApiWebsite;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
     
        view()->composer('*', function ($view) 
        {
			$cart_products  = 0;
            $view->with('cart_products_count', 0); 
            if (!Auth::guest()){
				$user_id = Auth::user()->id;
				$cart = Cart::whereUserId($user_id)->whereOrderId('0')->with('cart_products')->first();
				if(isset($cart->cart_products)){
					$cart_products = count($cart->cart_products);
				}
                $view->with('cart_products_count', $cart_products);  
            } 
        });  
    }
}
