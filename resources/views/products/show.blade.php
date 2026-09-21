<x-app-layout>
    @section('title', $product->name . ' | Velto Leather Shoes')
    @section('meta_description', $product->name .' Buy premium quality men shoes from Velto Leather Shoes. Cash on delivery available across Pakistan.')
    @section('og_type', 'product')
    @section('og_image', $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : ($product->images->isNotEmpty() ? asset('storage/' . $product->images->first()->image_path) : asset('images/headerlogo.png')))
    
    @push('seo')
        <meta property="product:price:amount" content="{{ $product->sale_price ?? $product->base_price }}">
        <meta property="product:price:currency" content="PKR">
        <script type="application/ld+json">
            {
                "@context": "https://schema.org/",
                "@type": "Product",
                "name": "{{ $product->name }}",
                "image": [
                    "{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->image_path) : '' }}"
                ],
                "description": "{{ Str::limit(strip_tags($product->description), 200) }}",
                "brand": {
                    "@type": "Brand",
                    "name": "Velto Leather Shoes"
                },
                "offers": {
                    "@type": "Offer",
                    "url": "{{ url()->current() }}",
                    "priceCurrency": "PKR",
                    "price": "{{ $product->sale_price ?? $product->base_price }}",
                    "availability": "https://schema.org/InStock"
                }
            }
        </script>
        <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "{{ route('home') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Shop",
      "item": "{{ route('shop.index') }}"
    }
    @if($product->category),
    {
      "@type": "ListItem",
      "position": 3,
      "name": "{{ $product->category->name }}",
      "item": "{{ route('shop.category', $product->category->slug) }}"
    },
    {
      "@type": "ListItem",
      "position": 4,
      "name": "{{ $product->name }}",
      "item": "{{ url()->current() }}"
    }
    @else
    ,
    {
      "@type": "ListItem",
      "position": 3,
      "name": "{{ $product->name }}",
      "item": "{{ url()->current() }}"
    }
    @endif
  ]
}
</script>
    @endpush
    <div class="bg-white" x-data="productDetail()">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12 py-6 lg:py-10">
            <!-- Breadcrumbs -->
            {{-- <nav class="flex mb-6 text-xs text-gray-500 font-medium" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-black transition">Home</a>
                    </li>
                    <li class="flex items-center">
                        <span class="text-gray-300 mr-2">/</span>
                        <a href="{{ route('shop.index') }}" class="hover:text-black transition">Shop</a>
                    </li>
                    @if($product->category)
                    <li class="flex items-center">
                        <span class="text-gray-300 mr-2">/</span>
                        <a href="{{ route('shop.category', $product->category->slug) }}" class="hover:text-black transition">
                            {{ $product->category->name }}
                        </a>
                    </li>
                    @endif
                    <li class="flex items-center text-gray-900 font-semibold truncate max-w-[200px] sm:max-w-xs" aria-current="page">
                        <span class="text-gray-300 mr-2">/</span>
                        <span class="truncate">{{ $product->name }}</span>
                    </li>
                </ol>
            </nav> --}}

            <div class="lg:grid lg:grid-cols-2 lg:gap-x-14 lg:items-start">
                
                <!-- Image Gallery -->
                <div class="relative flex flex-col gap-3 select-none">
                  @php
                        // Sort so primary image appears absolutely first natively
                        $sortedImages = collect();
                        if ($product->images && $product->images->count() > 0) {
                            $sortedImages = $product->images->sort(function ($a, $b) {
                                if ($a->is_primary && !$b->is_primary) return -1;
                                if (!$a->is_primary && $b->is_primary) return 1;
                                return ($a->display_order ?? 0) <=> ($b->display_order ?? 0);
                            })->values();
                        } elseif ($product->primary_image) {
                            $fakeImage = new \stdClass();
                            $fakeImage->image_path = $product->primary_image;
                            $sortedImages = collect([$fakeImage]);
                        } else {
                            $fakeImage = new \stdClass();
                            $fakeImage->image_path = 'https://placehold.co/400x500?text=No+Image';
                            $sortedImages = collect([$fakeImage]);
                        }
                    @endphp 

                    <!-- Main Slider Area (object-contain with white background so images don't get cropped) -->
                    <div class="swiper main-swiper w-full aspect-square md:aspect-[4/5] bg-white border border-gray-100 overflow-hidden rounded-md group relative">
                        <div class="swiper-wrapper">
                            @foreach($sortedImages as $image)
                                <div class="swiper-slide w-full h-full flex items-center justify-center p-4">
                                    @php
                                        $path = is_object($image) ? $image->image_path : $image['image_path'];
                                        $url = Str::startsWith($path, 'http') ? $path : asset('storage/' . $path);
                                    @endphp
                                    <img src="{{ $url }}" class="w-full h-full object-contain object-center block" alt="{{ $product->name }} - Men's leather shoes Pakistan" onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                </div>
                            @endforeach
                        </div>

                        @if($product->isOnSale())
                            <span class="absolute top-4 left-4 z-10 bg-black text-white text-[11px] font-bold px-3 py-1 uppercase tracking-wider shadow-sm rounded-none">
                                Sale
                            </span>
                        @endif
                        
                        <!-- Navigation Arrows (Hidden if single image) -->
                        @if($sortedImages->count() > 1)
                            <div class="swiper-button-next !text-black !w-10 !h-10 !bg-white/90 hover:!bg-white !rounded-full !shadow-md transition transform hover:scale-110 after:!text-sm after:!font-bold"></div>
                            <div class="swiper-button-prev !text-black !w-10 !h-10 !bg-white/90 hover:!bg-white !rounded-full !shadow-md transition transform hover:scale-110 after:!text-sm after:!font-bold"></div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if($sortedImages->count() > 1)
                        <div class="swiper thumb-swiper w-full overflow-hidden pt-1">
                            <div class="swiper-wrapper">
                                @foreach($sortedImages as $image)
                                    <div class="swiper-slide !w-20 !h-20 sm:!w-24 sm:!h-24 aspect-square bg-white border-2 border-gray-200 transition-all duration-200 cursor-pointer overflow-hidden rounded-md opacity-60 hover:opacity-100 [&.swiper-slide-thumb-active]:border-black [&.swiper-slide-thumb-active]:opacity-100 p-1 flex items-center justify-center">
                                        @php
                                            $path = is_object($image) ? $image->image_path : $image['image_path'];
                                            $url = Str::startsWith($path, 'http') ? $path : asset('storage/' . $path);
                                        @endphp
                                        <img src="{{ $url }}" class="w-full h-full object-contain object-center" onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Error';">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="mt-8 lg:mt-0 sticky top-28 lg:self-start h-fit pr-1">
                    <div class="border-b border-gray-100 pb-6 mb-6">
                        @if($product->category)
                            <a href="{{ route('shop.category', $product->category->slug) }}" class="text-xs uppercase tracking-widest text-gray-500 font-bold hover:text-black transition block mb-2">
                                {{ $product->category->name }}
                            </a>
                        @endif
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold tracking-tight text-gray-950 mb-3 leading-tight">{{ $product->name }}</h1>
                        
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                            <div class="flex items-baseline space-x-3">
                                <!-- Static Blade view for SEO/Initial load -->
                                <template x-if="!selectedVariant">
                                    <div class="flex items-baseline space-x-3">
                                        @if($product->isOnSale())
                                            <p class="text-2xl sm:text-3xl font-bold text-gray-950">Rs. {{ number_format($product->sale_price) }}</p>
                                            <p class="text-base sm:text-lg font-normal text-gray-400 line-through">Rs. {{ number_format($product->base_price) }}</p>
                                            <span class="text-white text-[11px] font-extrabold px-2 py-0.5 rounded-sm uppercase tracking-wider" style="background-color: #7B1B2A;">SAVE {{ $product->discountPercentage() }}%</span>
                                        @else
                                            <p class="text-2xl sm:text-3xl font-bold text-gray-950">Rs. {{ number_format($product->base_price) }}</p>
                                        @endif
                                    </div>
                                </template>

                                <!-- Dynamic Alpine view for Variant Selection -->
                                <template x-if="selectedVariant">
                                    <div class="flex items-baseline space-x-3">
                                        <p class="text-2xl sm:text-3xl font-bold text-gray-950" x-text="'Rs. ' + Number(currentPrice.sale || currentPrice.regular).toLocaleString()"></p>
                                        <template x-if="currentPrice.onSale">
                                            <div class="flex items-baseline space-x-3">
                                                <p class="text-base sm:text-lg font-normal text-gray-400 line-through" x-text="'Rs. ' + Number(currentPrice.regular).toLocaleString()"></p>
                                                <span class="text-white text-[11px] font-extrabold px-2 py-0.5 rounded-sm uppercase tracking-wider" style="background-color: #7B1B2A;" x-text="'SAVE ' + currentPrice.discount + '%'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Stock Status Indicator -->
                            <div class="flex items-center">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider"
                                      :class="checkStockStatus().class">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="checkStockStatus().text === 'In Stock' ? 'bg-green-600' : 'bg-gray-400'"></span>
                                    <span x-text="checkStockStatus().text"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($product->description)
                        <div class="prose prose-sm text-gray-600 mb-6 leading-relaxed">
                            <p>{{ $product->description }}</p>
                        </div>
                    @endif

                    <form @submit.prevent="addToBag">
                        <!-- Color Selector -->
                        <div class="mb-6" x-show="uniqueColors.length > 0">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-900">Color: <span class="font-normal text-gray-600 normal-case" x-text="selectedColor || 'Choose a color'"></span></span>
                            </div>
                            <div class="flex flex-wrap gap-2.5">
                                <template x-for="color in uniqueColors" :key="color">
                                    <button type="button" 
                                            @click="selectColor(color)"
                                            class="px-4 py-2 border rounded-sm text-xs font-semibold uppercase tracking-wider transition-all duration-200"
                                            :class="selectedColor === color 
                                                ? 'border-black bg-black text-white shadow-sm ring-1 ring-black' 
                                                : 'border-gray-200 text-gray-700 hover:border-gray-900 hover:text-black bg-white'">
                                        <span x-text="color"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Size Selector -->
                        <div class="mb-6" x-show="availableSizes.length > 0">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-900">
                                    Size: 
                                    <span class="font-normal text-gray-600" x-text="selectedVariant ? selectedVariant.size : 'Choose size'"></span>
                                </span>
                                <button type="button" @click="showSizeGuide = true" class="text-xs font-semibold text-gray-700 underline hover:text-black flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Size Guide
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                                <template x-for="variant in availableSizes" :key="variant.id">
                                    <button type="button" 
                                            @click="selectedVariant = variant"
                                            class="group relative flex flex-col items-center justify-center py-2.5 px-2 border rounded-sm text-xs font-bold uppercase tracking-wide focus:outline-none transition-all duration-150"
                                            :class="selectedVariant && selectedVariant.id === variant.id 
                                                ? 'ring-2 ring-black border-black bg-black text-white' 
                                                : (variant.stock_quantity <= 0 ? 'border-gray-100 text-gray-300 bg-gray-50 cursor-not-allowed line-through' : 'border-gray-200 text-gray-900 hover:border-black bg-white shadow-xs')">
                                        <span x-text="variant.size"></span>
                                        <span class="text-[9px] font-normal" :class="selectedVariant && selectedVariant.id === variant.id ? 'text-gray-300' : 'text-gray-400'" x-text="variant.stock_quantity > 0 ? '' : 'Sold'"></span>
                                    </button>
                                </template>
                            </div>
                            <p x-show="!selectedColor && uniqueColors.length > 0" class="text-xs text-amber-700 mt-2">Please select a color first.</p>
                        </div>

                        <!-- Actions Container -->
                        <div class="flex flex-col gap-2.5 mb-6 w-full">
                            <!-- Quantity & Add to Cart Row -->
                            <div class="flex flex-row gap-2.5 w-full">
                                <!-- Modern Quantity Selector -->
                                <div class="flex items-center border border-gray-300 rounded-sm w-28 sm:w-32 h-11 sm:h-12 flex-shrink-0 bg-white">
                                    <button type="button" class="w-9 h-full flex items-center justify-center text-gray-600 hover:text-black hover:bg-gray-100 font-bold transition text-sm select-none" @click="if(quantity > 1) quantity--">−</button>
                                    <input type="number" x-model="quantity" class="w-full h-full text-center border-none focus:ring-0 text-gray-900 font-bold text-sm bg-transparent p-0" min="1" readonly>
                                    <button type="button" class="w-9 h-full flex items-center justify-center text-gray-600 hover:text-black hover:bg-gray-100 font-bold transition text-sm select-none" @click="incrementQuantity()">+</button>
                                </div>
                                
                                <!-- Add to Bag -->
                                <button type="submit" 
                                        :disabled="loading || buyLoading || !canAddToCart"
                                        class="flex-1 bg-white border-2 border-black text-black h-11 sm:h-12 px-4 sm:px-6 text-xs sm:text-sm font-bold uppercase tracking-[0.12em] hover:bg-gray-900 hover:text-white disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center gap-2 rounded-sm shadow-xs active:scale-[0.99] whitespace-nowrap">
                                    <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span x-text="loading ? 'Adding...' : (checkStockStatus().text === 'Out of Stock' ? 'Out of Stock' : 'Add to Bag')"></span>
                                </button>
                            </div>

                            <!-- Buy Now Button (High Priority Conversion) -->
                            <button type="button" 
                                    @click="buyNow()"
                                    :disabled="loading || buyLoading || !canAddToCart"
                                    class="w-full bg-black text-white h-11 sm:h-12 px-6 text-xs sm:text-sm font-bold uppercase tracking-[0.12em] hover:bg-gray-800 disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center gap-2 rounded-sm shadow-md active:scale-[0.99]">
                                <span x-text="buyLoading ? 'Redirecting to Checkout...' : 'Buy Now'"></span>
                                <svg x-show="!buyLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </button>

                            <!-- WhatsApp Order Button -->
                            <a :href="generateWhatsAppLink()" target="_blank" class="w-full bg-[#25D366] hover:bg-[#128C7E] text-white h-11 sm:h-12 px-6 text-xs sm:text-sm font-bold uppercase tracking-[0.12em] transition-all duration-200 flex items-center justify-center gap-2 rounded-sm shadow-xs active:scale-[0.99]">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.489-1.761-1.663-2.06-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Order via WhatsApp
                            </a>
                        </div>
                    </form>

                    <!-- Trust & Guarantee Highlights -->
                    <div class="rounded-md border border-gray-200 bg-gray-50/70 p-3.5 mb-6">
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="font-medium text-gray-800 leading-tight">Cash on Delivery</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12h12L19 8M10 12v4M14 12v4"/></svg>
                                <span class="font-medium text-gray-800 leading-tight">3–5 Days Delivery</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span class="font-medium text-gray-800 leading-tight">7-Day Easy Exchange</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                <span class="font-medium text-gray-800 leading-tight">100% Genuine Leather</span>
                            </div>
                        </div>
                    </div>

                    <!-- Accordions -->
                    <div class="border-t border-gray-200 divide-y divide-gray-200" x-data="{ activeTab: 'details' }">
                        @if($product->long_description)
                        <div>
                            <button @click="activeTab = activeTab === 'details' ? null : 'details'" class="group relative w-full py-4 flex justify-between items-center text-left focus:outline-none">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-900">Product Specifications & Care</span>
                                <span class="ml-6 flex items-center">
                                    <svg class="h-4 w-4 transform transition-transform duration-200 text-gray-500" :class="activeTab === 'details' ? '-rotate-180 text-black' : 'rotate-0'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>
                            <div x-show="activeTab === 'details'" x-collapse class="pb-5 prose prose-sm text-gray-600 max-w-none text-xs leading-relaxed">
                                {!! nl2br(e($product->long_description)) !!}
                            </div>
                        </div>
                        @endif
                        <div>
                            <button @click="activeTab = activeTab === 'shipping' ? null : 'shipping'" class="group relative w-full py-4 flex justify-between items-center text-left focus:outline-none">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-900">Shipping, Delivery & Returns</span>
                                <span class="ml-6 flex items-center">
                                    <svg class="h-4 w-4 transform transition-transform duration-200 text-gray-500" :class="activeTab === 'shipping' ? '-rotate-180 text-black' : 'rotate-0'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>
                            <div x-show="activeTab === 'shipping'" x-collapse class="pb-5 prose prose-sm text-gray-600 text-xs leading-relaxed">
                                <p>We offer Cash On Delivery across Pakistan. Delivery typically takes 3-5 business days. We provide a hassle-free 7-day exchange policy for unworn items in original packaging.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Products -->
             @if($relatedProducts->isNotEmpty())
            <section class="mt-24 border-t border-gray-200 pt-16">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4 md:gap-0">
                    <div>
                         <span class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-2 block">Related</span>
                        <h2 class="text-2xl md:text-4xl font-serif font-bold text-gray-900">You may also like</h2>
                    </div>
                    <a href="{{ route('shop.index') }}" class="text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-black transition flex items-center group">
                        View All
                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-10">
                     @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" />
                     @endforeach
                </div>
            </section>
            @endif
            <!-- Size Guide Modal -->
            <div x-show="showSizeGuide" 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">
                
                <div class="bg-white rounded-lg shadow-xl w-full max-w-lg relative overflow-hidden"
                     @click.away="showSizeGuide = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                    
                    <!-- Close Button -->
                    <button @click="showSizeGuide = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <div class="p-8">
                        <h3 class="text-xl font-bold uppercase tracking-wide text-gray-900 mb-6 text-center">Men Shoe Size Chart</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-center border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="border border-gray-200 p-3 text-sm font-bold text-gray-900 uppercase">US</th>
                                        <th class="border border-gray-200 p-3 text-sm font-medium text-gray-700">7</th>
                                        <th class="border border-gray-200 p-3 text-sm font-medium text-gray-700">8</th>
                                        <th class="border border-gray-200 p-3 text-sm font-medium text-gray-700">9</th>
                                        <th class="border border-gray-200 p-3 text-sm font-medium text-gray-700">10</th>
                                        <th class="border border-gray-200 p-3 text-sm font-medium text-gray-700">11</th>
                                        <th class="border border-gray-200 p-3 text-sm font-medium text-gray-700">12</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-200 p-3 text-sm font-bold text-gray-900 uppercase bg-gray-50">EURO</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">40</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">41</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">42</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">43</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">44</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">45</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/swiper@11/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper@11/swiper-bundle.min.js"></script>

    <script>
        function productDetail() {
            return {
                product: @json($product),
                variants: @json($product->variants),
                
                showSizeGuide: false,
                selectedColor: null,
                selectedVariant: null,
                quantity: 1,
                loading: false,
                buyLoading: false,

                init() {
                    // Ensure stock is number
                    if (this.variants) {
                        this.variants.forEach(v => v.stock_quantity = parseInt(v.stock_quantity));
                    }
                    // TikTok ViewContent - Production only
                    @if(config('app.env') == 'production')
                        if (typeof ttq !== 'undefined') {
                            ttq.track('ViewContent', {
                                content_id: String(this.product.id),
                                content_type: 'product',
                                value: Number(
                                    this.product.sale_price || this.product.base_price
                                ),
                                currency: 'PKR'
                            });

                            console.log('TikTok ViewContent fired:', {
                                content_id: String(this.product.id),
                                value: Number(
                                    this.product.sale_price || this.product.base_price
                                )
                            });
                        }
                    @endif
                    // Auto-select first color and first available size
                    if (this.uniqueColors.length > 0) {
                        this.selectColor(this.uniqueColors[0]);
                    } else if (this.variants.length > 0) {
                        // No color variants, auto-select first in-stock size
                        const firstAvailable = this.variants.find(v => v.stock_quantity > 0) || this.variants[0];
                        if (firstAvailable) this.selectedVariant = firstAvailable;
                    }

                    // Initialize Swipers safely after Alpine renders and scripts load
                    this.$nextTick(() => {
                        let thumbSwiper = null;
                        if (document.querySelector('.thumb-swiper')) {
                            thumbSwiper = new Swiper(".thumb-swiper", {
                                spaceBetween: 16,
                                slidesPerView: 5,
                                freeMode: true,
                                watchSlidesProgress: true,
                            });
                        }

                        if (document.querySelector('.main-swiper')) {
                            new Swiper(".main-swiper", {
                                spaceBetween: 10,
                                navigation: {
                                    nextEl: ".swiper-button-next",
                                    prevEl: ".swiper-button-prev",
                                },
                                thumbs: thumbSwiper ? { swiper: thumbSwiper } : {},
                                grabCursor: true,
                            });
                        }

                        // Override Global WhatsApp button to send current Product details
                        const globalWaBtn = document.getElementById('global-whatsapp-btn');
                        if (globalWaBtn) {
                            globalWaBtn.addEventListener('click', (e) => {
                                e.preventDefault();
                                window.open(this.generateWhatsAppLink(), '_blank');
                            });
                        }
                    });
                },

                get uniqueColors() {
                    const colors = this.variants.map(v => v.color).filter(c => c);
                    return [...new Set(colors)];
                },

                get availableSizes() {
                    if (!this.selectedColor && this.uniqueColors.length > 0) return [];
                    if (this.uniqueColors.length === 0) return this.variants; // Return all if no colors defined
                    
                    return this.variants.filter(v => v.color === this.selectedColor)
                        .sort((a, b) => {
                            // Try numeric sort
                            const sizeA = parseFloat(a.size);
                            const sizeB = parseFloat(b.size);
                            if (!isNaN(sizeA) && !isNaN(sizeB)) return sizeA - sizeB;
                            return a.size.localeCompare(b.size);
                        });
                },
                
                get currentPrice() {
                    const price = this.selectedVariant?.price || this.product.base_price;
                    const salePrice = this.selectedVariant?.sale_price || this.product.sale_price;
                    
                    if (salePrice) {
                        return {
                            onSale: true,
                            sale: salePrice,
                            regular: price,
                            discount: Math.round(((price - salePrice) / price) * 100)
                        };
                    }
                    return { onSale: false, regular: price };
                },
                
                get canAddToCart() {
                    if (this.variants.length > 0 && !this.selectedVariant) return false;
                    if (this.selectedVariant && this.selectedVariant.stock_quantity <= 0) return false;
                    return true;
                },

                checkStockStatus() {
                    if (this.variants.length > 0 && !this.selectedVariant) return { text: 'Select Option', class: 'text-gray-500 bg-gray-100' };
                    if (this.selectedVariant) {
                        console.log('Checking variant stock:', this.selectedVariant.stock_quantity);
                        return this.selectedVariant.stock_quantity > 0 
                            ? { text: 'In Stock', class: 'text-green-600 bg-green-50' }
                            : { text: 'Out of Stock', class: 'text-gray-900 bg-gray-100' };
                    }
                    // Fallback
                    return this.product.stock_quantity > 0 
                        ? { text: 'In Stock', class: 'text-green-600 bg-green-50' }
                        : { text: 'Out of Stock', class: 'text-gray-900 bg-gray-100' };
                },

                selectColor(color) {
                    this.selectedColor = color;
                    this.selectedVariant = null;
                    this.quantity = 1;
                    // Auto-select first in-stock size for chosen color
                    this.$nextTick(() => {
                        const firstAvailable = this.availableSizes.find(v => v.stock_quantity > 0) || this.availableSizes[0];
                        if (firstAvailable) this.selectedVariant = firstAvailable;
                    });
                },

                incrementQuantity() {
                    const max = this.selectedVariant ? this.selectedVariant.stock_quantity : this.product.stock_quantity;
                    if (this.quantity < max) {
                        this.quantity++;
                    }
                },

                generateWhatsAppLink() {
                    let text = `Hello! I would like to order the following product:\n\n`;
                    text += `*${this.product.name}*\n`;
                    
                    if (this.selectedColor) {
                        text += `Color: ${this.selectedColor}\n`;
                    }
                    if (this.selectedVariant && this.selectedVariant.size) {
                        text += `Size: ${this.selectedVariant.size}\n`;
                    }
                    
                    text += `Quantity: ${this.quantity}\n`;
                    
                    const price = this.currentPrice.onSale ? this.currentPrice.sale : this.currentPrice.regular;
                    text += `Price: Rs. ${Number(price).toLocaleString()}\n`;
                    text += `Total: Rs. ${Number(price * this.quantity).toLocaleString()}\n\n`;
                    text += `Product Link: ${window.location.href}`;
                    
                    const encodedText = encodeURIComponent(text);
                    // Use WhatsApp phone number
                    return `https://wa.me/923069101633?text=${encodedText}`; 
                },

                addToBag() {
                    if (!this.canAddToCart) {
                         showNotification('Please select a size', 'error');
                         return;
                    }

                    this.loading = true;
                    // Call the global addToCart function defined in app layout
                    addToCart(this.product.id, this.quantity, this.selectedVariant ? this.selectedVariant.id : null)
                        .then(() => {
                            this.loading = false;
                        })
                        .catch(() => {
                            this.loading = false;
                        });
                },

                buyNow() {
                    if (!this.canAddToCart) {
                         showNotification('Please select a size', 'error');
                         return;
                    }

                    this.buyLoading = true;
                    addToCart(this.product.id, this.quantity, this.selectedVariant ? this.selectedVariant.id : null)
                        .then((res) => {
                            if (res && res.success) {
                                window.location.href = "{{ route('checkout.index') }}";
                            } else {
                                this.buyLoading = false;
                            }
                        })
                        .catch(() => {
                            this.buyLoading = false;
                        });
                }
            }
        }
    </script>
</x-app-layout>
