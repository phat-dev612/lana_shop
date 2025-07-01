<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
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
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        // Share cart count to all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $cartCount = Cart::where('user_id', Auth::user()->id)->sum('quantity');
                $view->with('cartCount', $cartCount);
            } else {
                $view->with('cartCount', 0);
            }
        });
    }
}
