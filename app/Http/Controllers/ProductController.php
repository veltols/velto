<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'images', 'variants' => function ($query) {
                $query->where('is_available', true)->where('stock_quantity', '>', 0);
            }])
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['primaryImage', 'images', 'variants'])
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function quickView($id)
    {
        $product = Product::with(['category', 'images', 'primaryImage', 'variants' => function ($query) {
            $query->where('is_available', true);
        }])
        ->where('is_active', true)
        ->findOrFail($id);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'base_price' => (float)$product->base_price,
                'sale_price' => $product->sale_price ? (float)$product->sale_price : null,
                'is_on_sale' => $product->isOnSale(),
                'discount_percent' => $product->discountPercentage(),
                'show_url' => route('product.show', $product->slug),
                'images' => $product->images->map(function ($img) {
                    return [
                        'id' => $img->id,
                        'url' => str_starts_with($img->image_path, 'http') ? $img->image_path : asset('storage/' . $img->image_path),
                        'is_primary' => (bool)$img->is_primary,
                    ];
                }),
                'variants' => $product->variants->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'size' => $v->size,
                        'color' => $v->color,
                        'stock_quantity' => (int)$v->stock_quantity,
                        'price' => (float)$v->price,
                        'sale_price' => $v->sale_price ? (float)$v->sale_price : null,
                    ];
                }),
            ]
        ]);
    }
}
