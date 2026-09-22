<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Empty the review table safely
        Schema::disableForeignKeyConstraints();
        Review::truncate();
        Schema::enableForeignKeyConstraints();

        $products = Product::where('is_active', true)->get();
        $count = $products->count();

        $reviewsData = [
            [
                'product_id' => $count > 0 ? $products[0]->id : null,
                'customer_name' => 'Syed Hassan Raza',
                'customer_email' => 'hassan.raza@example.com',
                'rating' => 5,
                'title' => 'Exquisite craftsmanship with unparalleled elegance',
                'description' => 'From the unboxing experience to the very first step, everything screams bespoke luxury. The leather aroma, precision welt stitching, and arch cushioning make these shoes an essential for every discerning gentleman. Highly recommended.',
                'status' => 'approved',
                'is_featured' => true,
                'verified_purchase' => true,
                'created_at' => now()->subDays(2),
            ],
            [
                'product_id' => $count > 1 ? $products[1]->id : ($count > 0 ? $products[0]->id : null),
                'customer_name' => 'Javed Iqbal',
                'customer_email' => 'javed.iqbal@example.com',
                'rating' => 5,
                'title' => 'Masterclass in comfort and timeless silhouette',
                'description' => 'Wore these for a full day of executive meetings in Islamabad. Extremely soft break-in with zero heel rub. The patina and luster on the leather are reminiscent of heritage British shoemakers.',
                'status' => 'approved',
                'is_featured' => true,
                'verified_purchase' => true,
                'created_at' => now()->subDays(4),
            ],
            [
                'product_id' => $count > 2 ? $products[2]->id : ($count > 0 ? $products[0]->id : null),
                'customer_name' => 'Ali Raza',
                'customer_email' => 'ali.raza@example.com',
                'rating' => 5,
                'title' => 'The finest full-grain leather shoes crafted in Pakistan',
                'description' => 'Velto has genuinely exceeded expectations. The hardware is subtle yet distinct, and the sole grip is reliable without compromising elegance. Delivered to Lahore within 3 days in pristine packaging.',
                'status' => 'approved',
                'is_featured' => true,
                'verified_purchase' => true,
                'created_at' => now()->subDays(6),
            ],
            [
                'product_id' => $count > 3 ? $products[3]->id : ($count > 0 ? $products[0]->id : null),
                'customer_name' => 'Muhammad Usman',
                'customer_email' => 'm.usman@example.com',
                'rating' => 5,
                'title' => 'Flawless fit, supreme comfort & regal aesthetics',
                'description' => 'I was initially hesitant about online sizing, but the size fits like a bespoke glove. The leather is supple yet holds its shape gracefully. A quintessential pair for formal suits and evening events alike.',
                'status' => 'approved',
                'is_featured' => true,
                'verified_purchase' => true,
                'created_at' => now()->subDays(9),
            ],
            [
                'product_id' => $count > 4 ? $products[4]->id : ($count > 0 ? $products[0]->id : null),
                'customer_name' => 'Wajahat Hassan Janjua',
                'customer_email' => 'wajahat.janjua@example.com',
                'rating' => 5,
                'title' => 'A benchmark of luxury – exceeded all expectations',
                'description' => 'The attention to detail in the sole finish and burnished leather texture is truly artistic. Velto’s commitment to genuine materials is evident in every stride. Prompt Cash On Delivery and impeccable packaging.',
                'status' => 'approved',
                'is_featured' => true,
                'verified_purchase' => true,
                'created_at' => now()->subDays(12),
            ],
            [
                'product_id' => $count > 0 ? $products[0]->id : null,
                'customer_name' => 'Muhammad Umar',
                'customer_email' => 'm.umar@example.com',
                'rating' => 5,
                'title' => 'Unmatched sophistication and top-tier durability',
                'description' => 'These shoes stand out effortlessly in any boardroom or gathering. The cushioned insole provides all-day support, and the leather only looks better with time. Truly an investment in authentic gentleman’s style.',
                'status' => 'approved',
                'is_featured' => true,
                'verified_purchase' => true,
                'created_at' => now()->subDays(15),
            ],
        ];

        foreach ($reviewsData as $data) {
            Review::create($data);
        }
    }
}
