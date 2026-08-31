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

            $initialCartItems = [];

            if ($sessionId || $userId) {
                $cartItems = \App\Models\Cart::with(['product', 'product.primaryImage', 'variant'])
                    ->where(function($q) use ($sessionId, $userId) {
                        if ($sessionId) $q->where('session_id', $sessionId);
                        if ($userId) $q->orWhere('user_id', $userId);
                    })->get();

                $cartCount = $cartItems->sum('quantity');
                $cartTotal = $cartItems->sum(function($item) {
                    $price = $item->variant ? $item->variant->final_price : $item->product->price;
                    return $price * $item->quantity;
                });

                $initialCartItems = $cartItems->map(function ($item) {
                    $imagePath = $item->product->primaryImage ? $item->product->primaryImage->image_path : null;
                    $imageUrl = $imagePath
                        ? (\Illuminate\Support\Str::startsWith($imagePath, 'http') ? $imagePath : asset('storage/' . $imagePath))
                        : 'https://placehold.co/100x100?text=No+Img';
                    $price = $item->variant ? $item->variant->final_price : $item->product->price;

                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'variant_id' => $item->product_variant_id,
                        'name' => $item->product->name,
                        'slug' => $item->product->slug,
                        'url' => route('product.show', $item->product->slug),
                        'image' => $imageUrl,
                        'variant' => $item->variant ? trim(($item->variant->size ? 'Size: ' . $item->variant->size : '') . ($item->variant->color ? ' / Color: ' . $item->variant->color : '')) : null,
                        'quantity' => (int) $item->quantity,
                        'stock' => $item->variant ? (int)$item->variant->stock_quantity : 99,
                        'price' => (float) $price,
                        'price_formatted' => 'PKR ' . number_format($price),
                        'total' => (float) ($price * $item->quantity),
                        'total_formatted' => 'PKR ' . number_format($price * $item->quantity),
                    ];
                })->values()->all();
            }
            
            $view->with('cartCount', $cartCount);
            $view->with('cartTotal', $cartTotal);
            $view->with('initialCartItems', $initialCartItems);
        });
    }
}
