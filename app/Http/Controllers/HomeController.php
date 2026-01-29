<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::with('primaryImage', 'images', 'category')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->take(8)
            ->get();

        $newArrivals = Product::with('primaryImage', 'images', 'category')
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        $categories = Category::withCount('products')->where('is_active', true)->orderBy('display_order')->take(6)->get();

        return view('home', compact('featured', 'newArrivals', 'categories'));
    }
}
