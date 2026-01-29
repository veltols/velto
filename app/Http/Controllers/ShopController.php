<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('primaryImage', 'category', 'variants')
            ->where('is_active', true);

        $this->applyFilters($query, $request);

        $products = $query->paginate(12);
        
        $filterData = $this->getFilterData();

        return view('shop.index', array_merge(['products' => $products], $filterData));
    }

    public function category($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $query = Product::with('primaryImage', 'category', 'variants')
            ->where('category_id', $category->id)
            ->where('is_active', true);
            
        $this->applyFilters($query, $request);
            
        $products = $query->paginate(12);
            
        $filterData = $this->getFilterData();

        return view('shop.index', array_merge(['products' => $products, 'category' => $category], $filterData));
    }

    private function applyFilters($query, Request $request)
    {
        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->latest();
                    break;
                case 'price_low':
                    $query->orderBy('base_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('base_price', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        // Category Filter (for index method mostly)
        if ($request->has('category')) {
            $categorySlug = $request->category;
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search Filter
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Size Filter
        if ($request->has('sizes') && is_array($request->sizes)) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->whereIn('size', $request->sizes);
            });
        }
        
        // Color Filter
        if ($request->has('colors') && is_array($request->colors)) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->whereIn('color', $request->colors);
            });
        }
        
        // Price Filter
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('base_price', [$request->min_price, $request->max_price]);
        }

        // Sale Filter
        if ($request->has('on_sale')) {
            $query->whereNotNull('sale_price')->where('sale_price', '>', 0);
        }
    }
    
    private function getFilterData()
    {
        $categories = Category::where('is_active', true)->orderBy('display_order')->get();
        
        $sizes = \App\Models\ProductVariant::where('is_available', true)
            ->whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct()
            ->pluck('size')
            ->sort()
            ->values();
            
        $colors = \App\Models\ProductVariant::where('is_available', true)
            ->whereNotNull('color')
            ->where('color', '!=', '')
            ->distinct()
            ->pluck('color')
            ->sort()
            ->values();
            
        $minPrice = Product::where('is_active', true)->min('base_price') ?? 0;
        $maxPrice = Product::where('is_active', true)->max('base_price') ?? 10000;
        
        return compact('categories', 'sizes', 'colors', 'minPrice', 'maxPrice');
    }
}
