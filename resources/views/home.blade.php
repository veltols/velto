<x-app-layout>
    @section('title', 'Luxury Leather Shoes')
    @push('seo')
        <meta name="description" content="Velto - Exquisite craftsmanship and modern luxury. Shop our 2026 Artisan Collection of handcrafted leather shoes.">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Velto - Luxury Leather Shoes">
        <meta property="og:description" content="Elevate your stride with Velto's handcrafted leather shoes. Where Italian heritage meets modern luxury.">
        <meta property="og:url" content="{{ route('home') }}">
        <meta property="og:image" content="{{ asset('images/velto_banner_wide.png') }}">
    @endpush
    <!-- Hero Section -->
    <section class="relative w-full overflow-hidden" style="height: 800px; min-height: 800px;">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero_banner.png') }}" alt="Velto Luxury Collection" class="w-full h-full object-cover object-center transform scale-105">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
        </div>
        <div class="relative h-full max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12 flex items-center">
            <div class="max-w-3xl text-white pt-20 animate-fade-in-up">
                <span class="block text-xs md:text-sm font-bold uppercase tracking-[0.3em] mb-4 md:mb-6 text-white/70">Exquisite Craftsmanship</span>
                <h1 class="text-4xl sm:text-5xl md:text-7xl lg:text-8xl font-serif font-bold leading-none mb-6 md:mb-8 tracking-tight">
                    Walk in <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">Excellence</span>
                </h1>
                <p class="text-base md:text-lg lg:text-xl text-gray-300 mb-8 md:mb-12 max-w-lg font-light leading-relaxed tracking-wide">
                    Elevate your stride with our 2026 Artisan Collection. Where Italian heritage meets modern luxury.
                </p>
                <div class="flex flex-col sm:flex-row gap-6">
                    <a href="{{ route('shop.index') }}" class="inline-flex justify-center items-center bg-white text-black px-12 py-5 text-sm font-bold uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all duration-300 transform hover:-translate-y-1">
                        Explore Collection
                    </a>
                    <a href="#new-arrivals" class="inline-flex justify-center items-center border border-white text-white px-12 py-5 text-sm font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all duration-300">
                        View Lookbook
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Grid (Ashion Style) -->
    <section class="py-20 bg-white">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories->take(4) as $category)
                    @php
                        // Masonry Layout Logic
                        $colSpan = 'col-span-1';
                        $rowSpan = '';
                        $height = 'h-[290px]';
                        
                        if ($loop->index == 0) {
                            $colSpan = 'col-span-1 md:col-span-2';
                            $rowSpan = 'row-span-2';
                            $height = 'h-[600px]';
                        } elseif ($loop->index == 3) {
                            $colSpan = 'col-span-1 md:col-span-2';
                        }

                        // Handle Category Image
                        if ($category->image) {
                            $imageUrl = asset('storage/' . $category->image);
                        } else {
                            // Fallback based on index for variety
                            if ($loop->index == 0) {
                                 $imageUrl = 'https://placehold.co/800x600?text=' . urlencode($category->name);
                            } elseif ($loop->index == 3) {
                                 $imageUrl = 'https://placehold.co/800x290?text=' . urlencode($category->name);
                            } else {
                                 $imageUrl = 'https://placehold.co/400x290?text=' . urlencode($category->name);
                            }
                        }
                    @endphp

                    <div class="{{ $colSpan }} {{ $rowSpan }} relative group overflow-hidden {{ $height }}">
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $category->name }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             onerror="this.onerror=null;this.src='https://placehold.co/800x600?text=Image+Not+Found';">
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-all"></div>
                        <div style="position: absolute; bottom: 20px; left: 20px; padding: 24px; background-color: rgba(0, 0, 0, 0.6); max-width: 85%; z-index: 10; border-radius: 12px;">
                            <h3 style="color: white; margin-bottom: 8px;" class="{{ $loop->index == 0 ? 'text-4xl' : 'text-2xl' }} font-serif font-bold">{{ $category->name }}</h3>
                            <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 16px;" class="text-sm">{{ $category->products_count }} items</p>
                            <a href="{{ route('shop.category', $category->slug) }}" class="inline-block bg-white text-black px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300 rounded-md">
                                Shop Now
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Section -->
    <section class="py-24 bg-white border-t border-gray-100">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4 md:gap-0">
                <div>
                     <span class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-2 block">Special</span>
                    <h2 class="text-4xl font-serif font-bold">Featured Items</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-black transition flex items-center group">
                    View All Products
                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                @foreach($featured as $product)
                    <div class="group cursor-pointer">
                        <div class="relative overflow-hidden bg-gray-50 aspect-[4/5] mb-4 rounded-sm">
                             <a href="{{ route('product.show', $product->slug) }}">
                                @if($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover object-center transition duration-700 ease-out group-hover:scale-105"
                                         onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                @elseif($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover object-center transition duration-700 ease-out group-hover:scale-105"
                                         onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                @else
                                    <img src="https://placehold.co/400x500?text=No+Image+400x500" class="w-full h-full object-cover object-center">
                                @endif
                            </a>
                            

                             @if($product->isOnSale())
                                <div class="absolute top-2 left-2 bg-black text-white text-[10px] font-bold px-2 py-1 uppercase tracking-widest shadow-lg">Sale</div>
                            @endif
                             @if($product->variants->sum('stock_quantity') <= 0)
                                <div class="absolute top-2 right-2 bg-black text-white text-xs px-2 py-1 uppercase tracking-wide">Sold Out</div>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1 tracking-wide">{{ $product->category->name ?? 'Category' }}</p>
                            <h3 class="text-base font-bold text-gray-900 mb-1">
                                <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            <div class="flex items-center gap-3">
                                @if($product->isOnSale())
                                    <p class="text-sm text-black font-bold">Rs. {{ number_format($product->sale_price) }}</p>
                                    <p class="text-xs text-gray-400 line-through">Rs. {{ number_format($product->base_price) }}</p>
                                @else
                                    <p class="text-sm text-gray-900 font-medium">Rs. {{ number_format($product->base_price) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Mid-page Banner -->
    @if($banner)
    <section class="relative w-full bg-center bg-cover bg-no-repeat flex items-center justify-center" style="height: 600px; min-height: 600px; background-image: url('{{ $banner->image_path ? Storage::url($banner->image_path) : 'https://placehold.co/1920x800/000000/ffffff?text=' . urlencode($banner->title) }}');">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            @if($banner->text)
            <span class="block text-sm md:text-base font-bold uppercase tracking-[0.3em] mb-4 text-white/70 animate-fade-in">{{ $banner->text }}</span>
            @endif
            <h2 class="text-4xl md:text-6xl font-serif font-bold mb-8 text-white leading-tight">{{ $banner->title }}</h2>
            <a href="{{ $banner->button_link }}" class="inline-block bg-black text-white px-10 py-4 text-sm font-bold uppercase tracking-widest hover:bg-white hover:text-black transition duration-300 transform hover:-translate-y-1">
                {{ $banner->button_text }}
            </a>
        </div>
    </section>
    @else
    <section class="relative w-full bg-center bg-cover bg-no-repeat flex items-center justify-center" style="height: 800px; min-height: 800px; background-image: url('https://placehold.co/1920x800/000000/ffffff?text=Velto+Luxury+Collection');">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <span class="block text-sm md:text-base font-bold uppercase tracking-[0.3em] mb-4 text-white/70 animate-fade-in">The Art of Shoemaking</span>
            <h2 class="text-4xl md:text-6xl font-serif font-bold mb-8 text-white leading-tight">Handcrafted Perfection</h2>
            <p class="text-lg md:text-xl text-gray-200 mb-10 font-light leading-relaxed max-w-2xl mx-auto">
                While trends fade, quality endures. Discover the meticulous process behind every pair of Velto shoes, crafted to stand the test of time.
            </p>
            <a href="{{ route('shop.index') }}" class="inline-block border-2 border-white text-white px-10 py-4 text-sm font-bold uppercase tracking-widest hover:bg-white hover:text-black transition duration-300 transform hover:-translate-y-1">
                Discover More
            </a>
        </div>
    </section>
    @endif

    <!-- New Arrivals -->
    <section id="new-arrivals" class="py-24 bg-gray-50">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4 md:gap-0">
                <div>
                     <span class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-2 block">Exclusive</span>
                    <h2 class="text-4xl font-serif font-bold">New Arrivals</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-black transition flex items-center group">
                    View All
                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-12">
                @foreach($newArrivals as $product)
                    <div class="group cursor-pointer">
                        <div class="relative overflow-hidden bg-gray-50 aspect-[4/5] mb-4 rounded-sm">
                             <a href="{{ route('product.show', $product->slug) }}">
                                @if($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover object-center transition duration-700 ease-out group-hover:scale-105"
                                         onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                @elseif($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover object-center transition duration-700 ease-out group-hover:scale-105"
                                         onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                @else
                                    <img src="https://placehold.co/400x500?text=No+Image+400x500" class="w-full h-full object-cover object-center">
                                @endif
                            </a>
                            

                             @if($product->isOnSale())
                                <div class="absolute top-2 left-2 bg-black text-white text-[10px] font-bold px-2 py-1 uppercase tracking-widest shadow-lg">Sale</div>
                            @endif
                             @if($product->variants->sum('stock_quantity') <= 0)
                                <div class="absolute top-2 right-2 bg-black text-white text-xs px-2 py-1 uppercase tracking-wide">Sold Out</div>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1 tracking-wide">{{ $product->category->name ?? 'Category' }}</p>
                            <h3 class="text-base font-bold text-gray-900 mb-1">
                                <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            <div class="flex items-center gap-3">
                                @if($product->isOnSale())
                                    <p class="text-sm text-black font-bold">Rs. {{ number_format($product->sale_price) }}</p>
                                    <p class="text-xs text-gray-400 line-through">Rs. {{ number_format($product->base_price) }}</p>
                                @else
                                    <p class="text-sm text-gray-900 font-medium">Rs. {{ number_format($product->base_price) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



    <!-- Services -->
    <section class="py-20 border-t border-gray-100">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                 <div class="flex items-center space-x-4">
                     <div class="flex-shrink-0">
                         <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     </div>
                    <div>
                        <h4 class="font-bold text-gray-900 uppercase">Money Back</h4>
                        <p class="text-xs text-gray-500">If goods have problems</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                     <div class="flex-shrink-0">
                         <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     </div>
                    <div>
                        <h4 class="font-bold text-gray-900 uppercase">Online Support 24/7</h4>
                        <p class="text-xs text-gray-500">Dedicated support</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                     <div class="flex-shrink-0">
                         <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                     </div>
                    <div>
                        <h4 class="font-bold text-gray-900 uppercase">Secure Payment</h4>
                        <p class="text-xs text-gray-500">100% secure payment</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                     <div class="flex-shrink-0">
                         <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                     </div>
                    <div>
                        <h4 class="font-bold text-gray-900 uppercase">Make to Order</h4>
                        <p class="text-xs text-gray-500">Customized for you</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>
