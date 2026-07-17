<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Category;

class SitemapHelper
{
    public static function generate()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Home
        $xml .= '<url>';
        $xml .= '<loc>' . url('/') . '</loc>';
        $xml .= '</url>';

        // Shop
        $xml .= '<url>';
        $xml .= '<loc>' . url('/shop') . '</loc>';
        $xml .= '</url>';

        // Categories
        foreach (Category::all() as $category) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('/shop/' . $category->slug) . '</loc>';
            $xml .= '</url>';
        }

        // Products
        foreach (Product::where('is_active', 1)->get() as $product) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('/product/' . $product->slug) . '</loc>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        file_put_contents(public_path('sitemap.xml'), $xml);
    }
}