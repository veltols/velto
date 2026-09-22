<x-app-layout>
    @section('title', "Men's Leather Shoes in Pakistan | Premium Loafers & Formal Shoes")
    @section('meta_description', "Shop premium men's leather shoes in Pakistan from Velto. Explore leather loafers, suede loafers and formal shoes crafted for comfort, style and everyday elegance. Cash on delivery available.")
    <!-- Hero Slider Section -->
    @if($sliderBanners->isNotEmpty())
    <section class="relative w-full overflow-hidden hero-slider-section">

        <!-- Swiper CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <div class="swiper hero-swiper w-full">
            <div class="swiper-wrapper">
                @foreach($sliderBanners as $index => $slide)
                <div class="swiper-slide">
                    <div class="relative w-full h-full overflow-hidden hero-slide-inner">
                        {{-- Slide Image (object-cover with balanced height so it is not heavily zoomed) --}}
                        @if($slide->image_path)
                            <img src="{{ Storage::url($slide->image_path) }}"
                                 alt="{{ $slide->title ?: 'Velto Banner' }}"
                                 class="absolute inset-0 w-full h-full object-cover object-center hero-slide-img"
                                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                 fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}">
                        @else
                            <div class="w-full h-full bg-gray-900"></div>
                        @endif

                        {{-- Dark Scrim on Image for High Legibility --}}
                        @if($slide->title || $slide->text || $slide->button_text)
                            <div class="hero-slide-scrim" style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.48) 50%, rgba(0,0,0,0.75) 100%); pointer-events: none; z-index: 2;"></div>

                            {{-- Slide Content Overlay --}}
                            <div class="absolute inset-0 z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center text-center pb-6 sm:pb-4">
                                <div class="max-w-3xl text-white flex flex-col items-center justify-center swiper-slide-content py-2 px-4 sm:py-6 sm:px-8" style="background: radial-gradient(ellipse at center, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 85%); border-radius: 16px;">
                                    {{-- Main Title --}}
                                    @if($slide->title)
                                        @if($index === 0)
                                            <h1 class="text-xl sm:text-3xl md:text-5xl lg:text-6xl font-serif font-normal tracking-wide text-white mb-1.5 sm:mb-3 leading-tight hero-text-shadow">
                                                {!! nl2br(e($slide->title)) !!}
                                            </h1>
                                        @else
                                            <h2 class="text-xl sm:text-3xl md:text-5xl lg:text-6xl font-serif font-normal tracking-wide text-white mb-1.5 sm:mb-3 leading-tight hero-text-shadow">
                                                {!! nl2br(e($slide->title)) !!}
                                            </h2>
                                        @endif
                                    @endif

                                    {{-- Subtitle / Description --}}
                                    @if($slide->text)
                                        <p class="text-[11px] sm:text-xs md:text-base text-white/95 max-w-xl sm:max-w-2xl mx-auto font-light leading-relaxed mb-3 sm:mb-5 tracking-wide hero-subtext-shadow line-clamp-2 sm:line-clamp-none">
                                            {{ $slide->text }}
                                        </p>
                                    @endif

                                    {{-- Button --}}
                                    @if($slide->button_text)
                                        <div class="pt-0.5">
                                            <a href="{{ $slide->button_link ?: '#' }}" class="hero-btn">
                                                {{ $slide->button_text }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($slide->button_link)
                            <a href="{{ $slide->button_link }}" class="absolute inset-0 z-10 block" aria-label="{{ $slide->title ?: 'Banner link' }}"></a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Navigation Arrows --}}
            @if($sliderBanners->count() > 1)
                <div class="swiper-button-next hero-swiper-next"></div>
                <div class="swiper-button-prev hero-swiper-prev"></div>
                <div class="swiper-pagination hero-swiper-pagination"></div>
            @endif
        </div>

        <style>
            .hero-slider-section,
            .hero-swiper,
            .hero-swiper .swiper-wrapper,
            .hero-swiper .swiper-slide,
            .hero-slide-inner {
                width: 100% !important;
                height: 280px !important;
                min-height: 280px !important;
                position: relative !important;
            }
            @media (min-width: 640px) {
                .hero-slider-section,
                .hero-swiper,
                .hero-swiper .swiper-wrapper,
                .hero-swiper .swiper-slide,
                .hero-slide-inner {
                    height: 420px !important;
                    min-height: 420px !important;
                }
            }
            @media (min-width: 768px) {
                .hero-slider-section,
                .hero-swiper,
                .hero-swiper .swiper-wrapper,
                .hero-swiper .swiper-slide,
                .hero-slide-inner {
                    height: 500px !important;
                    min-height: 500px !important;
                }
            }
            @media (min-width: 1024px) {
                .hero-slider-section,
                .hero-swiper,
                .hero-swiper .swiper-wrapper,
                .hero-swiper .swiper-slide,
                .hero-slide-inner {
                    height: 580px !important;
                    min-height: 580px !important;
                }
            }
            @media (min-width: 1280px) {
                .hero-slider-section,
                .hero-swiper,
                .hero-swiper .swiper-wrapper,
                .hero-swiper .swiper-slide,
                .hero-slide-inner {
                    height: 640px !important;
                    min-height: 640px !important;
                }
            }
            .hero-slide-img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                object-position: center !important;
                display: block !important;
            }
            .hero-slide-scrim {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 2;
            }
            .hero-text-shadow {
                text-shadow: 0 2px 4px rgba(0,0,0,1), 0 4px 16px rgba(0,0,0,0.95), 0 0 24px rgba(0,0,0,0.9) !important;
            }
            .hero-subtext-shadow {
                text-shadow: 0 1px 3px rgba(0,0,0,1), 0 2px 10px rgba(0,0,0,0.95), 0 0 16px rgba(0,0,0,0.85) !important;
            }
            .hero-swiper .swiper-button-next,
            .hero-swiper .swiper-button-prev {
                color: #fff;
                background: rgba(0,0,0,0.35);
                width: 48px;
                height: 48px;
                border-radius: 50%;
                border: 1px solid rgba(255,255,255,0.4);
                transition: all 0.3s ease;
                z-index: 20;
            }
            .hero-swiper .swiper-button-next:hover,
            .hero-swiper .swiper-button-prev:hover {
                background: rgba(0,0,0,0.8);
                border-color: #fff;
                transform: scale(1.08);
            }
            .hero-swiper .swiper-button-next::after,
            .hero-swiper .swiper-button-prev::after {
                font-size: 16px;
                font-weight: 700;
            }
            .hero-swiper .swiper-pagination {
                bottom: 8px !important;
                z-index: 20 !important;
            }
            .hero-swiper .swiper-pagination-bullet {
                width: 20px;
                height: 3px;
                border-radius: 0;
                background: rgba(255,255,255,0.5);
                opacity: 1;
                transition: all 0.3s ease;
            }
            .hero-swiper .swiper-pagination-bullet-active {
                background: #fff;
                width: 34px;
            }
            @media (max-width: 640px) {
                .hero-swiper .swiper-button-next,
                .hero-swiper .swiper-button-prev {
                    display: none !important;
                }
            }
            .hero-btn {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                background-color: #000000 !important;
                color: #ffffff !important;
                padding: 9px 24px !important;
                font-size: 11px !important;
                font-weight: 700 !important;
                letter-spacing: 0.18em !important;
                text-indent: 0.18em !important;
                text-transform: uppercase !important;
                border: 1px solid rgba(255, 255, 255, 0.35) !important;
                border-radius: 0px !important;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.7) !important;
                transition: all 0.3s ease !important;
                text-decoration: none !important;
                line-height: 1.2 !important;
                white-space: nowrap !important;
                box-sizing: border-box !important;
            }
            .hero-btn:hover {
                background-color: #1f2937 !important;
                border-color: #ffffff !important;
                color: #ffffff !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 14px 28px -4px rgba(0, 0, 0, 0.8) !important;
            }
            @media (min-width: 640px) {
                .hero-btn {
                    padding: 14px 40px !important;
                    font-size: 14px !important;
                    letter-spacing: 0.22em !important;
                    text-indent: 0.22em !important;
                }
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swiper !== 'undefined') {
                    initHeroSwiper();
                } else {
                    window.addEventListener('load', initHeroSwiper);
                }

                function initHeroSwiper() {
                    const swiperEl = document.querySelector('.hero-swiper');
                    if (!swiperEl) return;

                    const heroSwiper = new Swiper('.hero-swiper', {
                        loop: {{ $sliderBanners->count() > 1 ? 'true' : 'false' }},
                        speed: 800,
                        observer: true,
                        observeParents: true,
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false,
                        },
                        effect: 'fade',
                        fadeEffect: { crossFade: true },
                        navigation: {
                            nextEl: '.hero-swiper-next',
                            prevEl: '.hero-swiper-prev',
                        },
                        pagination: {
                            el: '.hero-swiper-pagination',
                            clickable: true,
                        },
                    });
                }
            });
        </script>
    </section>
    @else
    {{-- Fallback: Static hero when no slider banners configured --}}
    <section class="relative w-full overflow-hidden hero-slider-section">
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
    @endif

    <!-- Categories Section -->
    <section class="py-20 bg-white border-b border-gray-100">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            
            {{-- Section Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4 md:gap-0">
                <div>
                    <span class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-2 block">Categories</span>
                    <h2 class="text-4xl font-serif font-bold text-gray-900">Shop Men's Shoes by Category</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-black transition flex items-center group">
                    View All Categories
                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            {{-- Categories Grid (Matches Featured & New Arrivals layout) --}}
            @if($categories->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                @foreach($categories as $category)
                    @php
                        $imageUrl = null;
                        
                        // 1. Primary: Use 'image' column from categories table
                        if (!empty($category->image)) {
                            if (\Illuminate\Support\Str::startsWith($category->image, ['http://', 'https://'])) {
                                $imageUrl = $category->image;
                            } else {
                                $imageUrl = asset('storage/' . $category->image);
                            }
                        } 
                        // 2. Secondary: Fallback to first product image in this category
                        elseif ($category->products->isNotEmpty() && $category->products->first()->primaryImage) {
                            $imageUrl = asset('storage/' . $category->products->first()->primaryImage->image_path);
                        } 
                        // 3. Fallback default
                        else {
                            $imageUrl = asset('images/hero-shoes.png');
                        }
                    @endphp

                    <div class="group cursor-pointer">
                        <div class="relative overflow-hidden bg-gray-100 aspect-[4/5] mb-4 rounded-none">
                            <a href="{{ route('shop.category', $category->slug) }}" class="block w-full h-full">
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $category->name }} for Men - Velto" 
                                     class="w-full h-full object-cover object-center transition duration-700 ease-out group-hover:scale-105"
                                     onerror="this.onerror=null;this.src='{{ asset('images/hero-shoes.png') }}';">
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-black/25 transition duration-300"></div>
                                
                                {{-- Item Count Badge matching Product Badges --}}
                                <div class="absolute top-0 right-0 bg-black text-white font-extrabold uppercase shadow-md" style="font-size: 9px; padding: 8px 12px; line-height: 1; letter-spacing: 0.1em; z-index: 10;">
                                    {{ $category->products_count }} {{ \Illuminate\Support\Str::plural('item', $category->products_count) }}
                                </div>
                            </a>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-gray-400 tracking-[0.2em] uppercase block mb-1">COLLECTION</span>
                            <h3 class="text-xl sm:text-2xl font-serif font-bold text-gray-900 mb-2 group-hover:text-black transition-colors">
                                <a href="{{ route('shop.category', $category->slug) }}">{{ $category->name }}</a>
                            </h3>
                            <a href="{{ route('shop.category', $category->slug) }}" class="inline-flex items-center text-xs font-bold uppercase tracking-[0.2em] text-gray-800 hover:text-black transition group/btn mt-1">
                                <span class="leading-tight text-left">SHOP<br>COLLECTION</span>
                                <svg class="w-6 h-6 ml-3 transform group-hover/btn:translate-x-1.5 transition-transform duration-300 text-gray-800" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Centered Solid Black VIEW ALL Button for Categories --}}
            <div class="mt-14 text-center">
                <a href="{{ route('shop.index') }}" class="inline-block bg-black text-white px-12 py-4 text-xs sm:text-sm font-bold uppercase tracking-[0.25em] hover:bg-gray-800 transition duration-300 rounded-none shadow-sm">
                    VIEW ALL
                </a>
            </div>
            @endif

        </div>
    </section>

    <!-- New Arrivals Section (Immediately below Categories) -->
    <section id="new-arrivals" class="py-24 bg-gray-50 border-t border-b border-gray-100">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4 md:gap-0">
                <div>
                    <span class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-2 block">Exclusive</span>
                    <h2 class="text-4xl font-serif font-bold text-gray-900">New Arrivals</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-12">
                @foreach($newArrivals as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            {{-- Image 2: Solid Black VIEW ALL Button --}}
            <div class="mt-14 text-center">
                <a href="{{ route('shop.index') }}" class="inline-block bg-black text-white px-12 py-4 text-xs sm:text-sm font-bold uppercase tracking-[0.25em] hover:bg-black transition duration-300 rounded-none shadow-sm">
                    VIEW ALL
                </a>
            </div>
        </div>
    </section>

    <!-- Side-by-Side Promo Banner Section (Directly below New Arrivals) -->
    <section class="py-20 sm:py-24 bg-white border-b border-gray-100">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                
                {{-- Left Image: Studio Shot --}}
                <div class="group relative overflow-hidden aspect-[4/5] bg-[#f5f2ee] rounded-none border border-gray-100 shadow-sm">
                    <a href="{{ route('shop.index') }}" class="block w-full h-full">
                        <img src="{{ asset('images/pro2.webp') }}" 
                             alt="Velto Crocodile Leather Loafers" 
                             class="w-full h-full object-cover object-center transition duration-700 ease-out group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/25 transition duration-300"></div>
                        
                        <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-md p-6 border border-gray-100 shadow-lg transition-transform duration-300 group-hover:-translate-y-1">
                            <span class="text-[11px] font-bold text-gray-400 tracking-[0.25em] uppercase block mb-1">Croco Collection</span>
                            <h3 class="text-2xl font-serif font-bold text-gray-900 mb-3">Crocodile Leather Loafers</h3>
                            <div class="inline-flex items-center text-xs font-bold uppercase tracking-[0.2em] text-black hover:text-gray-700 transition">
                                <span>SHOP NOW</span>
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Right Image: On-Model Lookbook --}}
                <div class="group relative overflow-hidden aspect-[4/5] bg-[#f5f2ee] rounded-none border border-gray-100 shadow-sm">
                    <a href="{{ route('shop.index') }}" class="block w-full h-full">
                        <img src="{{ asset('images/pro1.webp') }}" 
                             alt="Velto men's premium leather shoes lookbook"
                             class="w-full h-full object-cover object-center transition duration-700 ease-out group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/25 transition duration-300"></div>
                        
                        <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-md p-6 border border-gray-100 shadow-lg transition-transform duration-300 group-hover:-translate-y-1">
                            <span class="text-[11px] font-bold text-gray-400 tracking-[0.25em] uppercase block mb-1">Lookbook 2026</span>
                            <h3 class="text-2xl font-serif font-bold text-gray-900 mb-3">The Gentleman's Stride</h3>
                            <div class="inline-flex items-center text-xs font-bold uppercase tracking-[0.2em] text-black hover:text-gray-700 transition">
                                <span>DISCOVER MORE</span>
                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Section -->
    <section class="py-24 bg-white border-t border-gray-100">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4 md:gap-0">
                <div>
                    <span class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-2 block">Special</span>
                    <h2 class="text-4xl font-serif font-bold text-gray-900">Featured Items</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                @foreach($featured as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            {{-- Image 2: Solid Black VIEW ALL Button --}}
            <div class="mt-14 text-center">
                <a href="{{ route('shop.index') }}" class="inline-block bg-black text-white px-12 py-4 text-xs sm:text-sm font-bold uppercase tracking-[0.25em] hover:bg-black transition duration-300 rounded-none shadow-sm">
                    VIEW ALL
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Velto / Mid-page Feature Section -->
    <section class="py-16 sm:py-24 bg-white border-t border-b border-gray-100">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="flex flex-col lg:flex-row items-stretch overflow-hidden bg-[#faf9f6] rounded-none border border-gray-200/80 shadow-lg">
                
                {{-- Left Side: Image Container (50% on lg) --}}
                <div class="w-full lg:w-1/2 relative min-h-[360px] sm:min-h-[460px] lg:min-h-[540px] overflow-hidden group bg-gray-100">
                    @php
                        $midBannerImg = ($banner && $banner->image_path) 
                            ? Storage::url($banner->image_path) 
                            : asset('images/why_choose_velto.jpg');
                    @endphp
                    <img src="{{ $midBannerImg }}" 
                         alt="{{ ($banner && $banner->title) ? $banner->title : 'Velto Leather Collection' }}" 
                         class="absolute inset-0 w-full h-full object-cover object-left sm:object-center transition-transform duration-1000 ease-out group-hover:scale-105"
                         onerror="this.onerror=null;this.src='{{ asset('images/why_choose_velto.jpg') }}';">
                    
                    {{-- Subtle Overlay --}}
                    <div class="absolute inset-0 bg-black/5 group-hover:bg-black/0 transition-colors duration-300"></div>

                    {{-- Floating Glass Badge on Image --}}
                    <div class="absolute bottom-6 left-6 bg-black/85 backdrop-blur-md px-5 py-3 border border-white/20 text-white flex items-center gap-3 shadow-xl">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                        <span class="text-xs font-bold tracking-[0.2em] uppercase text-gray-100">Artisan Handcrafted</span>
                    </div>
                </div>

                {{-- Right Side: Elegant Light Luxury Content Panel (50% on lg) --}}
                <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center items-start text-left bg-[#faf9f6] text-gray-900 relative">
                    
                    {{-- Sub-tag & Accent Line --}}
                    <div class="flex items-center gap-3 mb-5">
                        <span class="w-8 h-[2px] bg-black"></span>
                        <span class="text-xs font-bold uppercase tracking-[0.3em] text-gray-500">Heritage Collection</span>
                    </div>

                    {{-- Main Title --}}
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-bold tracking-tight text-gray-900 mb-5 leading-tight">
                        {{ ($banner && $banner->title) ? $banner->title : 'Velto Leather Collection' }}
                    </h2>

                    {{-- Description --}}
                    <p class="text-sm sm:text-base text-gray-600 font-light leading-relaxed mb-8 max-w-xl">
                        {{ ($banner && $banner->text) ? $banner->text : 'Refined craftsmanship meets modern elegance in every pair. Handcrafted from top-grain leather in small batches with meticulous attention to detail.' }}
                    </p>

                    {{-- Key Features Highlights Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 mb-10 w-full pt-6 border-t border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-5 h-5 rounded-full bg-black/5 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-800">Full-Grain Leather</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-5 h-5 rounded-full bg-black/5 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-800">Hand-Stitched Finish</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-5 h-5 rounded-full bg-black/5 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-800">Ergonomic Comfort</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-5 h-5 rounded-full bg-black/5 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-800">Master Craftsmanship</span>
                        </div>
                    </div>

                    {{-- Button --}}
                    @php
                        $targetLink = route('shop.index');
                        if ($banner && $banner->button_link) {
                            $targetLink = $banner->button_link;
                        }
                    @endphp
                    <div>
                        <a href="{{ $targetLink }}" 
                           class="inline-flex items-center justify-center bg-black text-white px-10 py-4 text-xs sm:text-sm font-bold uppercase tracking-[0.25em] hover:bg-black transition-all duration-300 shadow-lg group/btn hover:shadow-xl">
                            <span>{{ ($banner && $banner->button_text) ? $banner->button_text : 'SHOP NOW' }}</span>
                            <svg class="w-4 h-4 ml-3 transform group-hover/btn:translate-x-1.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- Elegant Customer Reviews Section -->
    <section class="py-20 sm:py-28 bg-[#faf9f6] border-t border-gray-200">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <span class="w-8 h-[2px] bg-black"></span>
                    <span class="text-xs font-bold uppercase tracking-[0.3em] text-gray-500">Testimonials</span>
                    <span class="w-8 h-[2px] bg-black"></span>
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-bold text-gray-950 mb-4 tracking-tight">
                    What Our Gentlemen Say
                </h2>
                <p class="text-sm sm:text-base text-gray-600 font-light leading-relaxed mb-6">
                    Handcrafted comfort and uncompromising quality. Genuine impressions from discerning buyers across Pakistan.
                </p>

                <!-- Trust Metric Badge -->
                <div class="inline-flex flex-wrap items-center justify-center gap-3 bg-white px-5 py-2.5 border border-gray-200 shadow-xs">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 text-black fill-current" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-xs font-bold text-gray-900 tracking-wider uppercase">4.9 / 5.0 Rating</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-xs text-gray-600 font-medium">1,000+ Satisfied Customers Nationwide</span>
                </div>
            </div>

            <!-- Reviews Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($reviews as $review)
                    <div class="bg-white border border-gray-200/90 p-7 sm:p-8 flex flex-col justify-between shadow-xs hover:border-black transition-all duration-300 group">
                        <div>
                            <!-- Star Rating & Quote mark -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <svg class="w-4 h-4 text-black fill-current" viewBox="0 0 24 24">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-xs font-bold text-gray-800">{{ $review->rating }}.0</span>
                                </div>
                                <svg class="w-7 h-7 text-gray-200 group-hover:text-black/20 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            </div>

                            <!-- Review Title -->
                            <h3 class="text-base font-bold text-gray-950 mb-3 leading-snug">
                                "{{ $review->title }}"
                            </h3>

                            <!-- Review Description -->
                            <p class="text-sm text-gray-600 leading-relaxed font-light mb-6 line-clamp-4">
                                {{ $review->description }}
                            </p>
                        </div>

                        <!-- Author & Product Footer -->
                        <div class="pt-5 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-black text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
                                        {{ $review->initials }}
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900">{{ $review->customer_name }}</h4>
                                        @if($review->verified_purchase)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-600">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                Verified Buyer
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($review->product)
                                <div class="mt-3 pt-3 border-t border-gray-100/70 flex items-center justify-between text-[11px]">
                                    <span class="text-gray-400">Purchased:</span>
                                    <a href="{{ route('product.show', $review->product->slug) }}" class="font-medium text-gray-700 hover:text-black transition truncate max-w-[200px] underline underline-offset-2">
                                        {{ $review->product->name }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white border border-gray-200">
                        <p class="text-sm text-gray-500">Reviews will be shown here once published.</p>
                    </div>
                @endforelse
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
