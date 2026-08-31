<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Paginator::useTailwind();

        view()->composer('layouts.app', function ($view) {
            $view->with('categories', \App\Models\Category::where('is_active', true)->orderBy('display_order')->get());

            // Cart Data
            $sessionId = \Illuminate\Support\Facades\Session::get('cart_session_id');
            $userId = auth()->id();
            $cartCount = 0;
            $cartTotal = 0;

            if ($sessionId || $userId) {
                $cartItems = \App\Models\Cart::where(function($q) use ($sessionId, $userId) {
                    if ($sessionId) $q->where('session_id', $sessionId);
                    if ($userId) $q->orWhere('user_id', $userId);
                })->get();

                $cartCount = $cartItems->sum('quantity');
                $cartTotal = $cartItems->sum(function($item) {
                    $price = $item->variant ? $item->variant->final_price : $item->product->price;
                    return $price * $item->quantity;
                });
            }
            
            $view->with('cartCount', $cartCount);
            $view->with('cartTotal', $cartTotal);
        });
    }
}
