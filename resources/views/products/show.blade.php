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
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold tracking-tight text-gray-950 mb-2 leading-tight">{{ $product->name }}</h1>
                        
                        <!-- Star Rating Summary Snippet -->
                        <div class="mb-3 flex items-center gap-2">
                            <a href="#customer-reviews" class="inline-flex items-center gap-1.5 group">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($averageRating))
                                            <svg class="w-4 h-4 text-black fill-current" viewBox="0 0 24 24">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-xs font-semibold text-gray-800 group-hover:text-black transition ml-1">
                                    {{ $averageRating }} ({{ $reviewsCount }} {{ Str::plural('review', $reviewsCount) }})
                                </span>
                            </a>
                            <span class="text-xs text-gray-300">·</span>
                            <a href="#write-review-form" @click="$dispatch('open-review-form')" class="text-xs font-medium text-gray-500 hover:text-black underline underline-offset-2">Write a review</a>
                        </div>

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
                        <!-- Color Selector (Shown only when product has 2 or more colors) -->
                        <div class="mb-5" x-show="uniqueColors.length > 1">
                            <div class="flex items-center justify-between mb-2.5">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-900">
                                    Color: <span class="font-semibold text-stone-600 normal-case ml-1" x-text="selectedColor"></span>
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2.5">
                                <template x-for="color in uniqueColors" :key="color">
                                    <button type="button" 
                                            @click="selectColor(color)"
                                            class="group relative flex items-center gap-2 px-3 py-2 border rounded-md text-xs transition-all duration-150 select-none focus:outline-none"
                                            :class="selectedColor === color 
                                                ? 'border-black bg-black text-white shadow-sm ring-1 ring-black' 
                                                : 'border-gray-200 text-gray-800 hover:border-gray-400 bg-gray-50/70 hover:bg-gray-100/70'">
                                        <!-- Visual Color Dot -->
                                        <span class="w-3.5 h-3.5 rounded-full border border-gray-300 shadow-xs flex-shrink-0"
                                              :style="'background: ' + getColorHex(color)"></span>
                                        <span class="font-bold tracking-wide uppercase text-[11px]" x-text="color"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Colour & Texture Note Accordion -->
                        <div class="border-t border-b border-gray-200 py-3.5 my-6" x-data="{ openNote: true }">
                            <button type="button" @click="openNote = !openNote" class="w-full flex items-center justify-between text-left focus:outline-none select-none group">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-stone-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke-width="1.8"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16v-4m0-4h.01"/>
                                    </svg>
                                    <span class="text-xs font-semibold uppercase tracking-wider text-stone-900">Colour &amp; Texture</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-stone-400 transform transition-transform duration-200" :class="openNote ? 'rotate-180' : 'rotate-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="openNote" x-collapse>
                                <p class="mt-2.5 pl-6 text-[11px] sm:text-xs text-stone-500 uppercase tracking-wide leading-relaxed font-normal">
                                    PLEASE NOTE: LEATHER IS A NATURAL MATERIAL, AND PRODUCT PHOTOS ARE TAKEN UNDER STUDIO LIGHTING. COLOUR AND TEXTURE MAY APPEAR SLIGHTLY DIFFERENT IN PERSON DUE TO LIGHTING, SCREEN SETTINGS, AND NATURAL VARIATIONS IN THE LEATHER.
                                </p>
                            </div>
                        </div>

                        <!-- Size Selector -->
                        <div class="mb-6" x-show="availableSizes.length > 0">
                            <div class="flex items-center justify-between mb-3.5">
                                <span class="text-xs sm:text-[13px] font-bold uppercase tracking-wider text-gray-900">
                                    Select Size
                                </span>
                                <button type="button" @click="showSizeGuide = true" class="inline-flex items-center gap-1.5 pb-0.5 border-b-2 border-black text-xs font-bold uppercase tracking-wider text-gray-900 hover:opacity-75 transition cursor-pointer">
                                    <!-- Table / Grid Icon -->
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="1.8"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M10 3v18"/>
                                    </svg>
                                    <span>Size Guide</span>
                                    <svg class="w-3 h-3 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2.5 sm:gap-3">
                                <template x-for="variant in availableSizes" :key="variant.id">
                                    <button type="button" 
                                            @click="selectedVariant = variant"
                                            class="group relative flex flex-col items-center justify-center py-3 px-2 border rounded-md text-xs transition-all duration-150 focus:outline-none select-none min-h-[58px]"
                                            :class="selectedVariant && selectedVariant.id === variant.id 
                                                ? 'border-black bg-black text-white shadow-sm ring-1 ring-black' 
                                                : 'border-gray-200 text-gray-800 hover:border-gray-400 bg-gray-50/70 hover:bg-gray-100/70'">
                                        <!-- Dual Size Label: PK X | EU Y -->
                                        <span class="font-bold tracking-wide text-xs sm:text-[13px]" 
                                              :class="selectedVariant && selectedVariant.id === variant.id ? 'text-white' : 'text-gray-900'"
                                              x-text="formatSizeLabel(variant.size)"></span>

                                        <!-- Subtext: PRE-ORDER or status -->
                                        <template x-if="getVariantStatus(variant)">
                                            <span class="text-[9px] sm:text-[10px] tracking-wider uppercase font-semibold mt-0.5 leading-tight" 
                                                  :class="selectedVariant && selectedVariant.id === variant.id ? 'text-amber-500' : 'text-gray-400'"
                                                  x-text="getVariantStatus(variant)"></span>
                                        </template>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Actions Container -->
                        <div class="flex flex-col mb-6 w-full">
                            <!-- Quantity Selector -->
                            <div class="flex items-center gap-3 mb-3.5">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-900">Quantity:</span>
                                <div class="flex items-center border border-gray-300 rounded-md w-28 h-9 bg-white">
                                    <button type="button" class="w-8 h-full flex items-center justify-center text-gray-600 hover:text-black hover:bg-gray-100 font-bold transition text-sm select-none" @click="if(quantity > 1) quantity--">−</button>
                                    <input type="number" x-model="quantity" class="w-full h-full text-center border-none focus:ring-0 text-gray-900 font-bold text-xs bg-transparent p-0" min="1" readonly>
                                    <button type="button" class="w-8 h-full flex items-center justify-center text-gray-600 hover:text-black hover:bg-gray-100 font-bold transition text-sm select-none" @click="incrementQuantity()">+</button>
                                </div>
                            </div>

                            <!-- Action Buttons Row: Add to Bag, Buy Now, WhatsApp in Single Row -->
                            <div class="grid grid-cols-3 gap-2 sm:gap-2.5 w-full">
                                <!-- Add to Bag -->
                                <button type="submit" 
                                        :disabled="loading || buyLoading || !canAddToCart"
                                        class="bg-white border-2 border-black text-black h-11 sm:h-12 px-2 sm:px-3 text-[11px] sm:text-xs font-bold uppercase tracking-wider hover:bg-black hover:text-white disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center gap-1.5 rounded-md shadow-xs active:scale-[0.98]">
                                    <svg x-show="!loading" class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span class="truncate" x-text="loading ? 'Adding...' : (selectedVariant && selectedVariant.stock_quantity <= 0 ? 'Pre-Order' : 'Add to Bag')"></span>
                                </button>

                                <!-- Buy Now Button -->
                                <button type="button" 
                                        @click="buyNow()"
                                        :disabled="loading || buyLoading || !canAddToCart"
                                        class="bg-black border-2 border-black text-white h-11 sm:h-12 px-2 sm:px-3 text-[11px] sm:text-xs font-bold uppercase tracking-wider hover:bg-neutral-800 disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center gap-1.5 rounded-md shadow-sm active:scale-[0.98]">
                                    <span class="truncate" x-text="buyLoading ? 'Redirecting...' : (selectedVariant && selectedVariant.stock_quantity <= 0 ? 'Pre-Order' : 'Buy Now')"></span>
                                    <svg x-show="!buyLoading" class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </button>

                                <!-- WhatsApp Order Button -->
                                <a :href="generateWhatsAppLink()" target="_blank" 
                                   class="bg-[#25D366] hover:bg-[#128C7E] text-white h-11 sm:h-12 px-2 sm:px-3 text-[11px] sm:text-xs font-bold uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-1.5 rounded-md shadow-xs active:scale-[0.98]">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.489-1.761-1.663-2.06-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    <span class="truncate"><span class="hidden sm:inline">Order via </span>WhatsApp</span>
                                </a>
                            </div>
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
            
            <!-- Customer Reviews Section -->
            <section id="customer-reviews" class="mt-20 border-t border-gray-200 pt-16 scroll-mt-24" 
                x-data="reviewsManager()"
                @open-review-form.window="showReviewForm = true; $nextTick(() => { document.getElementById('write-review-form')?.scrollIntoView({behavior: 'smooth'}) })">
                <!-- Section Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-[0.25em] text-gray-400 mb-2 block">Real Experiences</span>
                        <div class="flex items-center gap-3">
                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold text-gray-950">Customer Reviews</h2>
                            <span class="rounded-full bg-black text-white text-xs font-bold px-2.5 py-0.5" x-text="allReviews.length"></span>
                        </div>
                    </div>
                    <button type="button" 
                            @click="showReviewForm = !showReviewForm; $nextTick(() => { if(showReviewForm) document.getElementById('write-review-form')?.scrollIntoView({behavior: 'smooth'}) })" 
                            class="inline-flex items-center gap-2 rounded-none bg-black text-white px-6 py-3 text-xs font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition shadow-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span x-text="showReviewForm ? 'Cancel Review' : 'Write a Review'">Write a Review</span>
                    </button>
                </div>

                @if(session('success'))
                    <div class="mb-8 rounded-none bg-amber-50 border border-amber-300 p-5 text-sm text-amber-950 flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <h4 class="font-bold text-amber-900 text-sm">Review Submitted (Pending Moderation)</h4>
                            <p class="text-xs text-amber-800 mt-0.5 leading-relaxed">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Dynamic Success Message (AJAX) -->
                <div id="review-success-banner" x-show="formSuccess" x-cloak class="mb-8 rounded-none bg-amber-50 border border-amber-300 p-5 text-sm text-amber-950 flex items-start justify-between gap-3 shadow-xs">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <h4 class="font-bold text-amber-900 text-sm">Review Submitted (Pending Moderation)</h4>
                            <p class="text-xs text-amber-800 mt-0.5 leading-relaxed" x-text="formMessage"></p>
                        </div>
                    </div>
                    <button type="button" @click="formSuccess = false" class="text-amber-700 hover:text-amber-950 text-xs font-bold uppercase p-1">✕</button>
                </div>

                <!-- Write a Review Form Panel -->
                <div id="write-review-form" x-show="showReviewForm" x-collapse x-cloak class="mb-14">
                    <div class="bg-[#faf9f6] border border-gray-200 p-6 sm:p-8 rounded-none shadow-sm">
                        <div class="border-b border-gray-200 pb-4 mb-6">
                            <h3 class="text-xl font-serif font-bold text-gray-900">Share Your Experience</h3>
                            <p class="text-xs text-gray-500 mt-1">Help other gentlemen choose the perfect pair. Your feedback is appreciated.</p>
                        </div>

                        <form action="{{ route('product.reviews.store', $product) }}" 
                              method="POST" 
                              @submit.prevent="
                                submittingReview = true;
                                const formData = new FormData($el);
                                const formEl = $el;
                                fetch('{{ route('product.reviews.store', $product) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    submittingReview = false;
                                    if(data.success) {
                                        formSuccess = true;
                                        formMessage = data.message;
                                        showReviewForm = false;
                                        formEl.reset();
                                        userRating = 5;
                                        $nextTick(() => {
                                            document.getElementById('review-success-banner')?.scrollIntoView({behavior: 'smooth'});
                                        });
                                    } else {
                                        alert(data.message || 'Error submitting review. Please check all fields.');
                                    }
                                })
                                .catch(() => {
                                    formEl.submit();
                                });
                              " 
                              class="space-y-6">
                            @csrf

                            <!-- Interactive Rating Selector -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-900 mb-2">Overall Rating <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center space-x-1" @mouseleave="hoverRating = 0">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" 
                                                    @mouseenter="hoverRating = {{ $i }}" 
                                                    @click="userRating = {{ $i }}" 
                                                    class="p-1 hover:scale-115 transition-all focus:outline-none cursor-pointer"
                                                    :title="'{{ $i }} Stars'">
                                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-black transition-colors" 
                                                     :fill="(hoverRating ? hoverRating >= {{ $i }} : userRating >= {{ $i }}) ? 'currentColor' : 'none'" 
                                                     stroke="currentColor" 
                                                     stroke-width="1.8" 
                                                     stroke-linecap="round" 
                                                     stroke-linejoin="round" 
                                                     viewBox="0 0 24 24">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-semibold text-gray-800" x-text="(hoverRating || userRating) + ' / 5 Stars'"></span>
                                    <input type="hidden" name="rating" :value="userRating">
                                </div>
                            </div>

                            <!-- Customer Name & Email -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="customer_name" class="block text-xs font-bold uppercase tracking-wider text-gray-900 mb-1.5">Your Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="customer_name" id="customer_name" required placeholder="e.g. Tariq Mehmood" class="w-full bg-white border border-gray-300 px-4 py-2.5 text-sm focus:border-black focus:ring-black">
                                </div>
                                <div>
                                    <label for="customer_email" class="block text-xs font-bold uppercase tracking-wider text-gray-900 mb-1.5">Email Address (Optional)</label>
                                    <input type="email" name="customer_email" id="customer_email" placeholder="e.g. tariq@example.com" class="w-full bg-white border border-gray-300 px-4 py-2.5 text-sm focus:border-black focus:ring-black">
                                </div>
                            </div>

                            <!-- Review Headline / Title -->
                            <div>
                                <label for="review_title" class="block text-xs font-bold uppercase tracking-wider text-gray-900 mb-1.5">Review Headline / Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="review_title" required placeholder="e.g. Supremely comfortable and exquisite finish" class="w-full bg-white border border-gray-300 px-4 py-2.5 text-sm focus:border-black focus:ring-black">
                            </div>

                            <!-- Review Description -->
                            <div>
                                <label for="review_description" class="block text-xs font-bold uppercase tracking-wider text-gray-900 mb-1.5">Your Review / Experience <span class="text-red-500">*</span></label>
                                <textarea name="description" id="review_description" rows="4" required placeholder="Tell us about the craftsmanship, comfort, sole flexibility, and sizing fit..." class="w-full bg-white border border-gray-300 px-4 py-2.5 text-sm focus:border-black focus:ring-black"></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                                <button type="button" @click="showReviewForm = false" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-gray-600 hover:text-black transition cursor-pointer">Cancel</button>
                                <button type="submit" :disabled="submittingReview" class="bg-black text-white px-8 py-3 text-xs font-bold uppercase tracking-[0.2em] hover:bg-gray-800 disabled:opacity-50 transition flex items-center gap-2 cursor-pointer">
                                    <span x-text="submittingReview ? 'Submitting...' : 'Submit Review'">Submit Review</span>
                                    <svg x-show="!submittingReview" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reviews Layout: Left Column (Sticky Sidebar) & Right Column (Feed + Toolbar) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
                    
                    <!-- Left: Rating Overview Card (Sticky Sidebar on Desktop) -->
                    <div class="lg:col-span-4 lg:sticky lg:top-28 lg:self-start space-y-5">
                        <div class="bg-[#faf9f6] border border-gray-200 p-6 sm:p-7">
                            <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-gray-500 mb-4">Overall Score</h3>
                            
                            <div class="flex items-baseline gap-3 mb-2">
                                <span class="text-5xl font-serif font-bold text-gray-950">{{ number_format($averageRating, 1) }}</span>
                                <span class="text-sm font-medium text-gray-500">out of 5.0</span>
                            </div>

                            <!-- Stars -->
                            <div class="flex items-center gap-0.5 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($averageRating))
                                        <svg class="w-5 h-5 text-black fill-current" viewBox="0 0 24 24">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    @endif
                                @endfor
                            </div>

                            <p class="text-xs text-gray-600 mb-5">Based on {{ $reviewsCount }} {{ Str::plural('verified review', $reviewsCount) }}</p>

                            <!-- Breakdown bars (Click to filter) -->
                            <div class="space-y-1.5 pt-4 border-t border-gray-200 text-xs">
                                <div class="flex items-center justify-between text-[11px] text-gray-400 font-semibold mb-2">
                                    <span>Click star to filter</span>
                                    <button type="button" x-show="starFilter !== 'all'" @click="clearFilter()" class="text-black font-bold hover:underline cursor-pointer">
                                        Reset
                                    </button>
                                </div>

                                @foreach([5, 4, 3, 2, 1] as $star)
                                    @php
                                        $item = $ratingBreakdown[$star] ?? ['count' => 0, 'percentage' => 0];
                                    @endphp
                                    <button type="button" 
                                            @click="setFilter(starFilter === {{ $star }} ? 'all' : {{ $star }})" 
                                            class="w-full group flex items-center gap-3 py-1.5 px-2 -mx-2 rounded transition text-left cursor-pointer"
                                            :class="starFilter === {{ $star }} ? 'bg-black/10 ring-1 ring-black/20 font-bold' : 'hover:bg-gray-200/60'">
                                        <span class="w-12 text-gray-700 font-semibold flex items-center gap-1 group-hover:text-black">
                                            {{ $star }} <span class="text-black text-sm">★</span>
                                        </span>
                                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-black rounded-full transition-all duration-500" style="width: {{ $item['percentage'] }}%;"></div>
                                        </div>
                                        <span class="w-10 text-right text-gray-500 text-[11px] font-medium" :class="starFilter === {{ $star }} ? 'text-black font-bold' : ''">{{ $item['count'] }}</span>
                                    </button>
                                @endforeach
                            </div>

                            <!-- Trust Highlights -->
                            <div class="mt-7 pt-6 border-t border-gray-200 space-y-3">
                                <div class="flex items-center gap-2.5 text-xs text-gray-700">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>100% Genuine Full-Grain Leather</span>
                                </div>
                                <div class="flex items-center gap-2.5 text-xs text-gray-700">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Verified Buyers across Pakistan</span>
                                </div>
                                <div class="flex items-center gap-2.5 text-xs text-gray-700">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>7-Day Hassle-Free Exchange</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Sticky Trigger -->
                        <button type="button" 
                                @click="showReviewForm = true; $nextTick(() => { document.getElementById('write-review-form')?.scrollIntoView({behavior: 'smooth'}) })"
                                class="w-full text-center py-3 px-4 border border-black text-black hover:bg-black hover:text-white transition text-xs font-bold uppercase tracking-[0.18em] cursor-pointer">
                            Write a Review
                        </button>
                    </div>

                    <!-- Right: Reviews Feed & Controls (8 cols on lg) -->
                    <div class="lg:col-span-8 space-y-6">

                        <!-- Filter & Sort Toolbar -->
                        <div x-show="allReviews.length > 0" class="bg-[#faf9f6] border border-gray-200 p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <!-- Star Filter Pills -->
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mr-1 hidden sm:inline">Filter:</span>
                                <button type="button" 
                                        @click="setFilter('all')" 
                                        :class="starFilter === 'all' ? 'bg-black text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                                        class="px-3 py-1 text-xs font-semibold rounded-none transition cursor-pointer">
                                    All (<span x-text="allReviews.length"></span>)
                                </button>
                                <template x-for="star in [5, 4, 3, 2, 1]" :key="star">
                                    <button type="button" 
                                            @click="setFilter(star)" 
                                            :class="starFilter === star ? 'bg-black text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                                            class="px-2.5 py-1 text-xs font-semibold rounded-none transition flex items-center gap-1 cursor-pointer">
                                        <span x-text="star"></span>★
                                        <span class="text-[10px] opacity-75" x-text="'(' + countForStar(star) + ')'"></span>
                                    </button>
                                </template>
                            </div>

                            <!-- Sort Dropdown -->
                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                <label for="review-sort" class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Sort:</label>
                                <select id="review-sort" 
                                        x-model="sortBy" 
                                        class="bg-white border border-gray-300 text-xs font-medium text-gray-800 py-1.5 pl-3 pr-8 focus:border-black focus:ring-black rounded-none">
                                    <option value="recent">Most Recent</option>
                                    <option value="highest">Highest Rating</option>
                                    <option value="lowest">Lowest Rating</option>
                                </select>
                            </div>
                        </div>

                        <!-- Active Filter Status Banner -->
                        <div x-show="starFilter !== 'all'" x-cloak class="flex items-center justify-between bg-black/5 border border-black/10 px-4 py-2 text-xs text-gray-800">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-500">Filtered by:</span>
                                <span class="font-bold text-black" x-text="starFilter + ' Star Reviews (' + totalFiltered + ')'"></span>
                            </div>
                            <button type="button" @click="clearFilter()" class="text-xs font-bold text-black hover:underline flex items-center gap-1 cursor-pointer">
                                <span>Show all reviews</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Dynamic Reviews List -->
                        <div class="space-y-4">
                            <template x-for="review in visibleReviews" :key="review.id">
                                <div class="bg-white border border-gray-200 p-6 sm:p-7 shadow-xs hover:border-gray-300 transition">
                                    <!-- Reviewer Header -->
                                    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-black text-white font-bold text-xs flex items-center justify-center flex-shrink-0" x-text="review.initials || 'C'">
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h4 class="text-sm font-bold text-gray-950" x-text="review.customer_name"></h4>
                                                    <span x-show="review.verified_purchase" class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                        Verified Buyer
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2 mt-0.5 text-[11px] text-gray-400">
                                                    <span x-text="review.formatted_date || 'Recent'"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stars -->
                                        <div class="flex items-center gap-0.5">
                                            <template x-for="s in [1, 2, 3, 4, 5]" :key="s">
                                                <span>
                                                    <svg x-show="s <= review.rating" class="w-4 h-4 text-black fill-current" viewBox="0 0 24 24">
                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                    </svg>
                                                    <svg x-show="s > review.rating" class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                    </svg>
                                                </span>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Review Title -->
                                    <h5 class="text-base font-bold text-gray-900 mb-2 leading-snug" x-text="review.title"></h5>

                                    <!-- Review Description -->
                                    <p class="text-sm text-gray-600 leading-relaxed font-normal whitespace-pre-line" x-text="review.description"></p>
                                </div>
                            </template>
                        </div>

                        <!-- Empty Filter State -->
                        <div x-show="totalFiltered === 0 && allReviews.length > 0" x-cloak class="bg-[#faf9f6] border border-dashed border-gray-300 p-8 text-center">
                            <p class="text-sm font-semibold text-gray-800">No reviews found matching <span x-text="starFilter"></span> stars.</p>
                            <button type="button" @click="clearFilter()" class="mt-3 inline-flex items-center gap-1.5 bg-black text-white px-4 py-2 text-xs font-bold uppercase tracking-wider hover:bg-gray-800 transition cursor-pointer">
                                Show All Reviews
                            </button>
                        </div>

                        <!-- Zero Reviews Overall State -->
                        <div x-show="allReviews.length === 0" class="text-center py-14 px-4 bg-[#faf9f6] border border-dashed border-gray-300">
                            <div class="w-12 h-12 mx-auto rounded-full bg-black text-white flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            </div>
                            <h4 class="text-base font-serif font-bold text-gray-900">Be the First to Review</h4>
                            <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Have you ordered this pair? Let fellow gentlemen know about the craftsmanship and fit.</p>
                            <button type="button" @click="showReviewForm = true; $nextTick(() => document.getElementById('write-review-form')?.scrollIntoView({behavior: 'smooth'}))" class="mt-4 inline-flex items-center gap-2 bg-black text-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider hover:bg-gray-800 transition cursor-pointer">
                                Write a Review
                            </button>
                        </div>

                        <!-- Load More Bar & Pagination (for scalable large review lists) -->
                        <div x-show="allReviews.length > 0" class="pt-6 border-t border-gray-200">
                            <div class="flex flex-col items-center justify-center text-center gap-3">
                                <!-- Progress Counter -->
                                <p class="text-xs text-gray-500 font-medium">
                                    Showing <span class="font-bold text-gray-900" x-text="Math.min(visibleCount, totalFiltered)"></span> of <span class="font-bold text-gray-900" x-text="totalFiltered"></span> reviews
                                </p>

                                <!-- Progress Bar -->
                                <div class="w-48 sm:w-64 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-black transition-all duration-300 rounded-full" 
                                         :style="'width: ' + (totalFiltered > 0 ? Math.min(100, Math.round((Math.min(visibleCount, totalFiltered) / totalFiltered) * 100)) : 0) + '%'"></div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-3 mt-2" x-show="hasMore">
                                    <button type="button" 
                                            @click="loadMore()" 
                                            class="bg-black text-white px-8 py-3 text-xs font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition shadow-sm flex items-center gap-2 cursor-pointer">
                                        <span>Load More Reviews</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <button type="button" 
                                            @click="showAll()" 
                                            class="border border-gray-300 bg-white text-gray-800 px-5 py-3 text-xs font-bold uppercase tracking-[0.15em] hover:border-black transition cursor-pointer">
                                        Show All (<span x-text="totalFiltered"></span>)
                                    </button>
                                </div>

                                <!-- All Loaded Message -->
                                <p x-show="!hasMore && totalFiltered > 0" class="text-xs text-gray-400 italic mt-1">
                                    You have viewed all reviews in this view.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            </section>

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
                                        <th class="border border-gray-200 p-3 text-sm font-bold text-gray-900 uppercase">PK / UK</th>
                                        <th class="border border-gray-200 p-3 text-sm font-semibold text-gray-800">5</th>
                                        <th class="border border-gray-200 p-3 text-sm font-semibold text-gray-800">6</th>
                                        <th class="border border-gray-200 p-3 text-sm font-semibold text-gray-800">7</th>
                                        <th class="border border-gray-200 p-3 text-sm font-semibold text-gray-800">8</th>
                                        <th class="border border-gray-200 p-3 text-sm font-semibold text-gray-800">9</th>
                                        <th class="border border-gray-200 p-3 text-sm font-semibold text-gray-800">10</th>
                                        <th class="border border-gray-200 p-3 text-sm font-semibold text-gray-800">11</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-200 p-3 text-sm font-bold text-gray-900 uppercase bg-gray-50">EURO</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">39</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">40</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">41</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">42</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">43</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">44</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">45</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-200 p-3 text-sm font-bold text-gray-900 uppercase bg-gray-50">US</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">6</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">7</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">8</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">9</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">10</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">11</td>
                                        <td class="border border-gray-200 p-3 text-sm font-medium text-gray-700">12</td>
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
                    // Auto-select initial color if multiple, or auto-select first in-stock variant
                    if (this.uniqueColors.length > 1) {
                        this.selectColor(this.uniqueColors[0]);
                    } else if (this.variants && this.variants.length > 0) {
                        const firstAvailable = this.variants.find(v => v.stock_quantity > 0) || this.variants[0];
                        if (firstAvailable) {
                            this.selectedVariant = firstAvailable;
                            this.selectedColor = firstAvailable.color || null;
                        }
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
                    if (!this.variants) return [];
                    const colors = this.variants.map(v => v.color).filter(c => c && c.trim() !== '');
                    return [...new Set(colors)];
                },

                getColorHex(color) {
                    if (!color) return '#111111';
                    const c = color.toLowerCase().trim();
                    const map = {
                        'black': '#111111',
                        'dark brown': '#3e2723',
                        'brown': '#5d4037',
                        'chocolate brown': '#4e3629',
                        'tan brown': '#8d6e63',
                        'camel brown': '#a07855',
                        'tan': '#b5835a',
                        'camel': '#c19a6b',
                        'beige': '#e6d7c3',
                        'off white': '#f5f5f0',
                        'white': '#ffffff',
                        'blue': '#1e3a8a',
                        'navy': '#0f172a',
                        'olive': '#556b2f',
                        'green': '#2e7d32',
                        'burgundy': '#800020',
                        'reddish': '#9b3d2b',
                        'grey': '#78716c',
                        'gray': '#78716c',
                        'two-tone': 'linear-gradient(135deg, #111 50%, #5d4037 50%)',
                    };
                    return map[c] || '#8d6e63';
                },

                get availableSizes() {
                    if (!this.variants || this.variants.length === 0) return [];
                    
                    let list = this.variants;
                    if (this.selectedColor && this.uniqueColors.length > 1) {
                        list = list.filter(v => v.color === this.selectedColor);
                    }
                    
                    // Deduplicate by size so a size is NEVER shown twice
                    const seen = new Map();
                    list.forEach(v => {
                        const key = String(v.size).trim();
                        // Prefer in-stock variant if there are duplicate size entries
                        if (!seen.has(key) || (seen.get(key).stock_quantity <= 0 && v.stock_quantity > 0)) {
                            seen.set(key, v);
                        }
                    });

                    return Array.from(seen.values()).sort((a, b) => {
                        const numA = parseInt(String(a.size).replace(/\D/g, ''), 10) || 0;
                        const numB = parseInt(String(b.size).replace(/\D/g, ''), 10) || 0;
                        if (numA && numB) return numA - numB;
                        return String(a.size).localeCompare(String(b.size));
                    });
                },

                selectColor(color) {
                    this.selectedColor = color;
                    this.selectedVariant = null;
                    this.quantity = 1;
                    this.$nextTick(() => {
                        const firstAvailable = this.availableSizes.find(v => v.stock_quantity > 0) || this.availableSizes[0];
                        if (firstAvailable) this.selectedVariant = firstAvailable;
                    });
                },

                formatSizeLabel(rawSize) {
                    if (!rawSize) return '';
                    let str = String(rawSize).trim();
                    if (str.toUpperCase().includes('PK') && str.toUpperCase().includes('EU')) {
                        return str;
                    }
                    const num = parseInt(str.replace(/\D/g, ''), 10);
                    if (!isNaN(num)) {
                        if (num >= 35 && num <= 48) {
                            return `PK ${num - 34} | EU ${num}`;
                        }
                        if (num >= 4 && num <= 14) {
                            return `PK ${num} | EU ${num + 34}`;
                        }
                    }
                    return str;
                },

                getVariantStatus(variant) {
                    if (!variant) return '';
                    if (variant.stock_quantity <= 0 || variant.is_preorder) {
                        return 'PRE-ORDER';
                    }
                    return '';
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
                    return true;
                },

                checkStockStatus() {
                    if (this.variants.length > 0 && !this.selectedVariant) return { text: 'Select Size', class: 'text-gray-500 bg-gray-100' };
                    if (this.selectedVariant) {
                        return this.selectedVariant.stock_quantity > 0 
                            ? { text: 'In Stock', class: 'text-green-700 bg-green-50' }
                            : { text: 'Pre-Order', class: 'text-amber-800 bg-amber-50' };
                    }
                    // Fallback
                    return this.product.stock_quantity > 0 
                        ? { text: 'In Stock', class: 'text-green-700 bg-green-50' }
                        : { text: 'Pre-Order', class: 'text-amber-800 bg-amber-50' };
                },

                incrementQuantity() {
                    const max = this.selectedVariant ? this.selectedVariant.stock_quantity : this.product.stock_quantity;
                    if (!max || max <= 0 || this.quantity < max) {
                        this.quantity++;
                    }
                },

                generateWhatsAppLink() {
                    let text = `Hello! I would like to order the following product:\n\n`;
                    if (this.selectedColor) {
                        text += `Color: ${this.selectedColor}\n`;
                    }
                    if (this.selectedVariant && this.selectedVariant.size) {
                        text += `Size: ${this.formatSizeLabel(this.selectedVariant.size)}`;
                        if (this.getVariantStatus(this.selectedVariant)) {
                            text += ` (${this.getVariantStatus(this.selectedVariant)})`;
                        }
                        text += `\n`;
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

        function reviewsManager() {
            return {
                allReviews: @json($reviews) || [],
                starFilter: 'all',
                sortBy: 'recent',
                visibleCount: 5,
                perPage: 5,
                showReviewForm: false,
                submittingReview: false,
                userRating: 5,
                hoverRating: 0,
                formSuccess: false,
                formMessage: '',

                get filteredReviews() {
                    let list = Array.isArray(this.allReviews) ? [...this.allReviews] : [];
                    if (this.starFilter !== 'all') {
                        const s = parseInt(this.starFilter);
                        list = list.filter(r => parseInt(r.rating) === s);
                    }
                    if (this.sortBy === 'recent') {
                        list.sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
                    } else if (this.sortBy === 'highest') {
                        list.sort((a, b) => (b.rating - a.rating) || (new Date(b.created_at || 0) - new Date(a.created_at || 0)));
                    } else if (this.sortBy === 'lowest') {
                        list.sort((a, b) => (a.rating - b.rating) || (new Date(b.created_at || 0) - new Date(a.created_at || 0)));
                    }
                    return list;
                },

                get visibleReviews() {
                    return this.filteredReviews.slice(0, this.visibleCount);
                },

                get totalFiltered() {
                    return this.filteredReviews.length;
                },

                get hasMore() {
                    return this.visibleCount < this.totalFiltered;
                },

                loadMore() {
                    this.visibleCount += this.perPage;
                },

                showAll() {
                    this.visibleCount = this.totalFiltered;
                },

                setFilter(star) {
                    this.starFilter = star;
                    this.visibleCount = this.perPage;
                },

                clearFilter() {
                    this.starFilter = 'all';
                    this.visibleCount = this.perPage;
                },

                countForStar(star) {
                    if (!Array.isArray(this.allReviews)) return 0;
                    return this.allReviews.filter(r => parseInt(r.rating) === star).length;
                }
            };
        }

        // Expose to window for Alpine
        window.productDetail = productDetail;
        window.reviewsManager = reviewsManager;

        if (window.Alpine) {
            window.Alpine.data('productDetail', productDetail);
            window.Alpine.data('reviewsManager', reviewsManager);
        } else {
            document.addEventListener('alpine:init', () => {
                window.Alpine.data('productDetail', productDetail);
                window.Alpine.data('reviewsManager', reviewsManager);
            });
        }
    </script>
</x-app-layout>
