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
            ->with(['category', 'images', 'approvedReviews', 'variants' => function ($query) {
                $query->where('is_available', true);
            }])
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['primaryImage', 'images', 'variants'])
            ->take(4)
            ->get();

        $reviews = $product->approvedReviews;
        $averageRating = $product->averageRating();
        $ratingBreakdown = $product->ratingBreakdown();
        $reviewsCount = $reviews->count();

        return view('products.show', compact('product', 'relatedProducts', 'reviews', 'averageRating', 'ratingBreakdown', 'reviewsCount'));
    }

    public function storeReview(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating'          => 'required|integer|min:1|max:5',
            'customer_name'   => 'required|string|max:255',
            'customer_email'  => 'nullable|email|max:255',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string|max:3000',
        ]);

        $review = $product->reviews()->create([
            'rating'            => $validated['rating'],
            'customer_name'     => $validated['customer_name'],
            'customer_email'    => $validated['customer_email'] ?? null,
            'title'             => $validated['title'],
            'description'       => $validated['description'],
            'status'            => 'pending', // Pending admin approval before publishing
            'verified_purchase' => true,
            'is_featured'       => false,
        ]);

        $pendingMessage = 'Thank you! Your review has been submitted and is currently pending review. It will be published as soon as our team approves it.';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $pendingMessage,
                'review'  => $review,
            ]);
        }

        return back()->with('success', $pendingMessage);
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
