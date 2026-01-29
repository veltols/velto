<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Categories
        $categories = [
            [
                'name' => 'Formal Shoes',
                'image' => 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?q=80&w=500&auto=format&fit=crop',
                'description' => 'Elegant footwear for the modern gentleman.',
            ],
            [
                'name' => 'Casual Loafers',
                'image' => 'https://images.unsplash.com/photo-1533867617858-e7b97e060509?q=80&w=500&auto=format&fit=crop',
                'description' => 'Comfort meets style for everyday wear.',
            ],
            [
                'name' => 'Leather Boots',
                'image' => 'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?q=80&w=500&auto=format&fit=crop',
                'description' => 'Rugged durability with a refined touch.',
            ],
            [
                'name' => 'Accessories',
                'image' => 'https://images.unsplash.com/photo-1559563458-52c69522144a?q=80&w=500&auto=format&fit=crop',
                'description' => 'Belts, wallets, and care kits.',
            ],
        ];

        foreach ($categories as $catData) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($catData['name'])],
                [
                    'name' => $catData['name'],
                    'description' => $catData['description'],
                    'image' => $catData['image'], 
                    'is_active' => true,
                ]
            );

            // 2. Create Products for each Category
            $this->createProductsForCategory($category, $catData['image']);
        }
    }

    private function createProductsForCategory($category, $catImage)
    {
        $prefixes = ['Classic', 'Modern', 'Royal', 'Signature', 'Urban'];
        $types = explode(' ', $category->name)[1] ?? 'Shoe'; 

        for ($i = 1; $i <= 5; $i++) {
            $name = $prefixes[array_rand($prefixes)] . ' ' . $types . ' ' . Str::random(3);
            
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'Handcrafted from premium full-grain leather. A perfect blend of style and comfort for ' . $category->name . '.',
                'long_description' => 'Experience the epitome of luxury with our ' . $name . '. Meticulously crafted by skilled artisans, this piece features:
                - 100% Genuine Leather upper
                - Soft breathable lining
                - Durable non-slip sole
                - High-density memory foam insole
                
                Ideal for both formal occasions and casual outings.',
                'base_price' => rand(5000, 15000),
                'sku' => strtoupper(Str::slug($category->name) . '-' . Str::random(6)),
                'is_featured' => rand(0, 1) == 1,
                'is_active' => true,
            ]);

            // 3. Add Images
            // Using a few static luxury shoe URLs for variety
            $images = [
                'https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1478146059778-26028b07395a?q=80&w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=800&auto=format&fit=crop',
            ];

            foreach ($images as $index => $url) {
                // We will store the full URL. We need to Ensure views render this correctly.
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $url, // Storing external URL directly
                    'is_primary' => $index === 0,
                    'display_order' => $index,
                ]);
            }

            // 4. Add Variants
            $sizes = [40, 41, 42, 43, 44, 45];
            $colors = ['Black', 'Brown', 'Tan', 'Oxblood'];

            foreach (array_rand($colors, 2) as $colorKey) {
                $color = $colors[$colorKey];
                foreach (array_rand($sizes, 3) as $sizeKey) {
                    $size = $sizes[$sizeKey];
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $color,
                        'sku' => $product->sku . '-' . $size . '-' . substr($color, 0, 2),
                        'price' => null, // Use base price
                        'stock_quantity' => rand(0, 20),
                        'is_available' => true,
                    ]);
                }
            }
        }
    }
}
