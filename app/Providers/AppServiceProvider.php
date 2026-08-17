<?php

namespace App\Providers;

use App\Models\Order;
use App\Support\Storefront;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
        Paginator::useBootstrapFive();

        View::composer('layouts.app', function ($view) {
            $cart = Storefront::cartData();
            $view->with('navCategories', Storefront::navCategories())
                ->with('cartItems', $cart['cartItems'])
                ->with('cartTotal', $cart['cartTotal']);
        });

        View::composer('admin.partials.header', function ($view) {
            if (auth()->check()) {
                $adminRecentOrders = Order::latest()->take(5)->get();
                $view->with('adminRecentOrders', $adminRecentOrders);
            } else {
                $view->with('adminRecentOrders', collect());
            }
        });
    }
}
