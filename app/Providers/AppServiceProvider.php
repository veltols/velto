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

            $defaultShippingRate = \App\Models\ShippingRate::where('is_active', true)->where('is_default', true)->first()
                ?? \App\Models\ShippingRate::where('is_active', true)->first();

            // Dynamic free shipping text calculation
            $freeShippingThreshold = null;
            if ($defaultShippingRate && $defaultShippingRate->min_order_amount && (float)$defaultShippingRate->min_order_amount > 0) {
                $freeShippingThreshold = (float)$defaultShippingRate->min_order_amount;
            } else {
                $rateWithMin = \App\Models\ShippingRate::where('is_active', true)
                    ->whereNotNull('min_order_amount')
                    ->where('min_order_amount', '>', 0)
                    ->orderBy('min_order_amount')
                    ->first();
                if ($rateWithMin) {
                    $freeShippingThreshold = (float)$rateWithMin->min_order_amount;
                }
            }

            $freeShippingText = null;
            if ($defaultShippingRate && (float)$defaultShippingRate->rate <= 0) {
                $freeShippingText = 'FREE SHIPPING ON ALL ORDERS';
            } elseif ($freeShippingThreshold !== null && $freeShippingThreshold > 0) {
                $formattedAmount = ($freeShippingThreshold >= 1000 && ((int)$freeShippingThreshold % 1000 === 0))
                    ? ((int)($freeShippingThreshold / 1000) . 'k PKR')
                    : (number_format($freeShippingThreshold) . ' PKR');
                $freeShippingText = 'free shipping above ' . $formattedAmount;
            }

            $view->with('defaultShippingRate', $defaultShippingRate);
            $view->with('freeShippingText', $freeShippingText);
        });
    }
}
