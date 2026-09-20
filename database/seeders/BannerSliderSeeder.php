<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BannerSliderSeeder extends Seeder
{
    /**
     * Hero Slider Banners matching the Shopify store design.
     */
    protected array $slides = [
        [
            'title'       => 'Walk In Excellence',
            'text'        => 'Exquisite craftsmanship. Premium leather footwear designed for the modern gentleman.',
            'button_text' => 'SHOP NOW',
            'button_link' => '/shop',
            'sort_order'  => 0,
            'local_source'=> 'public/images/split_banner_1.jpg',
            'storage_path'=> 'banners/split_banner_1.jpg',
        ],
        [
            'title'       => 'Artisan Heritage',
            'text'        => 'Handcrafted perfection meeting contemporary style and uncompromised comfort.',
            'button_text' => 'EXPLORE COLLECTION',
            'button_link' => '/shop',
            'sort_order'  => 1,
            'local_source'=> 'public/images/split_banner_2.jpg',
            'storage_path'=> 'banners/split_banner_2.jpg',
        ],
    ];

    public function run(): void
    {
        // Clear existing slider banners before seeding
        Banner::where('is_slider', true)->delete();

        $this->command->info('🌱 Seeding hero slider banners matching Shopify design...');

        foreach ($this->slides as $slideData) {
            $imagePath = $slideData['storage_path'];

            // Ensure file exists in storage/app/public/banners/
            $sourceFile = base_path($slideData['local_source']);
            $targetPath = storage_path('app/public/' . $slideData['storage_path']);

            if (File::exists($sourceFile)) {
                File::ensureDirectoryExists(dirname($targetPath));
                File::copy($sourceFile, $targetPath);
                $this->command->info("  ✅ Copied asset: {$slideData['storage_path']}");
            }

            Banner::create([
                'title'       => $slideData['title'],
                'text'        => $slideData['text'],
                'button_text' => $slideData['button_text'],
                'button_link' => $slideData['button_link'],
                'image_path'  => $imagePath,
                'is_active'   => true,
                'is_slider'   => true,
                'sort_order'  => $slideData['sort_order'],
            ]);

            $this->command->info("  ✅ Created slide #{$slideData['sort_order']}: {$slideData['title']}");
        }

        $this->command->info('');
        $this->command->info('✅ Done! Banners seeded successfully.');
    }
}
