<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::with('primaryImage', 'images', 'category')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $newArrivals = Product::with('primaryImage', 'images', 'category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::with(['products.primaryImage'])->withCount('products')->where('is_active', true)->orderBy('display_order')->get();

        // Hero slider banners (multiple, ordered)
        $sliderBanners = Banner::where('is_active', true)->where('is_slider', true)->orderBy('sort_order')->get();

        // Mid-page single banner
        $banner = Banner::where('is_active', true)->where('is_slider', false)->first();

        // Approved reviews (featured first, then 5 stars)
        $reviews = Review::with('product.primaryImage')
            ->approved()
            ->orderByRaw('is_featured DESC, rating DESC, created_at DESC')
            ->take(6)
            ->get();

        return view('home', compact('featured', 'newArrivals', 'categories', 'banner', 'sliderBanners', 'reviews'));
    }
}
