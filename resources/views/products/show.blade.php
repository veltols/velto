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
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12 py-12 lg:py-16">
            <!-- Breadcrumbs -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="text-gray-300 mx-2">/</span>
                            <a href="{{ route('shop.index') }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">
                                Shop
                            </a>
                        </div>
                    </li>
                    @if($product->category)
                    <li>
                        <div class="flex items-center">
                            <span class="text-gray-300 mx-2">/</span>
                            <a href="{{ route('shop.category', $product->category->slug) }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">
                                {{ $product->category->name }}
                            </a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <span class="text-gray-300 mx-2">/</span>
                            <span class="text-xs font-bold uppercase tracking-widest text-black">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-16 lg:items-start">
                
                <!-- Image Gallery -->
                <div class="relative flex flex-col gap-4 select-none">
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

                    <!-- Main Slider Area -->
                    <div class="swiper main-swiper w-full aspect-square md:aspect-[4/5] bg-gray-50 overflow-hidden rounded-sm group">
                        <div class="swiper-wrapper">
                            @foreach($sortedImages as $image)
                                <div class="swiper-slide w-full h-full">
                                    @php
                                        $path = is_object($image) ? $image->image_path : $image['image_path'];
                                        $url = Str::startsWith($path, 'http') ? $path : asset('storage/' . $path);
                                    @endphp
                                    <img src="{{ $url }}" class="w-full h-full object-cover object-center block" alt="{{ $product->name }} - Men's leather shoes Pakistan" onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Navigation Arrows (Hidden if single image) -->
                        @if($sortedImages->count() > 1)
                            <div class="swiper-button-next !text-black !w-10 !h-10 !bg-white/90 hover:!bg-white !rounded-full !shadow-md transition transform hover:scale-110 after:!text-sm after:!font-bold"></div>
                            <div class="swiper-button-prev !text-black !w-10 !h-10 !bg-white/90 hover:!bg-white !rounded-full !shadow-md transition transform hover:scale-110 after:!text-sm after:!font-bold"></div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if($sortedImages->count() > 1)
                        <div class="swiper thumb-swiper w-full overflow-hidden">
                            <div class="swiper-wrapper">
                                @foreach($sortedImages as $image)
                                    <div class="swiper-slide !w-1/5 aspect-square bg-gray-50 border-2 border-transparent transition-all duration-300 cursor-pointer overflow-hidden rounded-sm opacity-60 hover:opacity-100 [&.swiper-slide-thumb-active]:border-black [&.swiper-slide-thumb-active]:opacity-100">
                                        @php
                                            $path = is_object($image) ? $image->image_path : $image['image_path'];
                                            $url = Str::startsWith($path, 'http') ? $path : asset('storage/' . $path);
                                        @endphp
                                        <img src="{{ $url }}" class="w-full h-full object-cover object-center" onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Error';">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="mt-12 lg:mt-0 sticky top-32 lg:self-start h-fit max-h-[calc(100vh-10rem)] overflow-y-auto pr-4 scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent">
                    <div class="border-b border-gray-100 pb-8 mb-8">
                        <h1 class="text-xl md:text-3xl lg:text-4xl font-serif font-bold tracking-tight text-gray-900 mb-2 md:mb-4 break-words leading-tight">{{ $product->name }}</h1>
                        <div class="flex flex-col gap-3">
                            <div class="flex items-baseline space-x-4">
                                <!-- Static Blade view for SEO/Initial load -->
                                <template x-if="!selectedVariant">
                                    <div class="flex items-baseline space-x-4">
                                        @if($product->isOnSale())
                                            <p class="text-xl md:text-3xl font-bold text-black">Rs. {{ number_format($product->sale_price) }}</p>
                                            <p class="text-base md:text-xl font-medium text-gray-400 line-through">Rs. {{ number_format($product->base_price) }}</p>
                                            <span class="text-white text-[10px] font-bold px-2 py-1 rounded-sm uppercase tracking-widest" style="background-color: #7B1B2A;">-{{ $product->discountPercentage() }}%</span>
                                        @else
                                            <p class="text-xl md:text-3xl font-medium text-gray-900">Rs. {{ number_format($product->base_price) }}</p>
                                        @endif
                                    </div>
                                </template>

                                <!-- Dynamic Alpine view for Variant Selection -->
                                <template x-if="selectedVariant">
                                    <div class="flex items-baseline space-x-4">
                                        <p class="text-xl md:text-3xl font-bold" :class="currentPrice.onSale ? 'text-black' : 'text-gray-900'" x-text="'Rs. ' + Number(currentPrice.sale || currentPrice.regular).toLocaleString()"></p>
                                        <template x-if="currentPrice.onSale">
                                            <div class="flex items-baseline space-x-4">
                                                <p class="text-base md:text-xl font-medium text-gray-400 line-through" x-text="'Rs. ' + Number(currentPrice.regular).toLocaleString()"></p>
                                                <span class="text-white text-[10px] font-bold px-2 py-1 rounded-sm uppercase tracking-widest" style="background-color: #7B1B2A;" x-text="'-' + currentPrice.discount + '%'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                            
                            <div class="flex items-center space-x-4 self-start md:self-auto">
                                <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full"
                                      :class="checkStockStatus().class" 
                                      x-text="checkStockStatus().text"></span>
                            </div>
                        </div>
                    </div>

                    <div class="prose prose-sm text-gray-600 mb-10 leading-relaxed">
                        <p>{{ $product->description }}</p>
                    </div>

                    <form @submit.prevent="addToBag">
                        <!-- Color Selector -->
                        <div class="mb-8" x-show="uniqueColors.length > 0">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-900 mb-4">Select Color</h3>
                            <div class="flex flex-wrap gap-3 p-2">
                                <template x-for="color in uniqueColors" :key="color">
                                    <button type="button" 
                                            @click="selectColor(color)"
                                            class="px-6 py-3 border rounded-sm text-sm font-medium uppercase tracking-wide transition-all duration-200 min-w-[4rem]"
                                            :class="selectedColor === color 
                                                ? 'border-black bg-black text-white shadow-md' 
                                                : 'border-gray-200 text-gray-700 hover:border-black hover:text-black bg-white'">
                                        <span x-text="color"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Size Selector -->
                        <div class="mb-10" x-show="availableSizes.length > 0">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-bold uppercase tracking-widest text-gray-900">Select Size</h3>
                                <button type="button" @click="showSizeGuide = true" class="text-xs font-medium text-gray-500 underline hover:text-black">Size Guide</button>
                            </div>
                            
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3 p-2">
                                <template x-for="variant in availableSizes" :key="variant.id">
                                    <button type="button" 
                                            @click="selectedVariant = variant; console.log('Selected:', variant.stock_quantity)"
                                            class="group relative flex items-center justify-center py-3 border rounded-sm text-sm font-bold uppercase tracking-wide focus:outline-none transition-all duration-200"
                                            :class="selectedVariant && selectedVariant.id === variant.id 
                                                ? 'ring-2 ring-black border-transparent' 
                                                : (variant.stock_quantity <= 0 ? 'border-gray-100 text-gray-300 bg-gray-50' : 'border-gray-200 text-gray-900 shadow-sm hover:border-gray-300 hover:bg-gray-50')">
                                        <span x-text="variant.size"></span>
                                        <!-- Checkmark for selected -->
                                        <span class="absolute top-0 right-0 -mt-0.5 -mr-0.5 h-2 w-2 rounded-full bg-black ring-2 ring-white" x-show="selectedVariant && selectedVariant.id === variant.id"></span>
                                    </button>
                                </template>
                            </div>
                            <p x-show="!selectedColor && uniqueColors.length > 0" class="text-xs text-gray-400 mt-2 italic">Select a color to view sizes.</p>
                        </div>

                        <!-- Actions Container -->
                        <div class="flex flex-col gap-3 mb-8 md:mb-10 w-full">
                            <!-- Quantity & Add to Cart -->
                            <div class="flex flex-row gap-3 w-full">
                                <div class="flex items-center border border-gray-300 rounded-sm w-28 md:w-32 h-10 md:h-12 flex-shrink-0">
                                    <button type="button" class="w-8 md:w-10 h-full flex items-center justify-center text-gray-500 hover:text-black hover:bg-gray-50 transition" @click="if(quantity > 1) quantity--">-</button>
                                    <input type="number" x-model="quantity" class="w-full h-full text-center border-none focus:ring-0 text-gray-900 font-bold text-sm bg-transparent" min="1" readonly>
                                    <button type="button" class="w-8 md:w-10 h-full flex items-center justify-center text-gray-500 hover:text-black hover:bg-gray-50 transition" @click="incrementQuantity()">+</button>
                                </div>
                                
                                <button type="submit" 
                                        :disabled="loading || !canAddToCart"
                                        class="flex-1 bg-black text-white h-10 md:h-12 px-4 md:px-8 text-xs md:text-sm font-bold uppercase tracking-[0.15em] hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition transform active:scale-95 flex items-center justify-center gap-2 md:gap-3 shadow-lg hover:shadow-xl whitespace-nowrap">
                                    <span x-text="loading ? 'Adding...' : (checkStockStatus().text === 'Out of Stock' ? 'Out of Stock' : 'Add to Bag')"></span>
                                    <svg x-show="!loading && checkStockStatus().text !== 'Out of Stock'" class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </button>
                            </div>
                            
                            <!-- WhatsApp Order Button -->
                            <a :href="generateWhatsAppLink()" target="_blank" style="background-color: #25D366;" onmouseover="this.style.backgroundColor='#128C7E'" onmouseout="this.style.backgroundColor='#25D366'" class="w-full text-white h-10 md:h-12 px-4 md:px-8 text-xs md:text-sm font-bold uppercase tracking-[0.15em] transition transform active:scale-95 flex items-center justify-center gap-2 md:gap-3 shadow-lg hover:shadow-xl rounded-sm">
                                <svg class="w-5 h-5 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.489-1.761-1.663-2.06-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Order via WhatsApp
                            </a>
                        </div>
                    </form>

                    <!-- Accordions -->
                    <div class="border-t border-gray-200 divide-y divide-gray-200" x-data="{ activeTab: 'details' }">
                        @if($product->long_description)
                        <div>
                            <button @click="activeTab = activeTab === 'details' ? null : 'details'" class="group relative w-full py-6 flex justify-between items-center text-left focus:outline-none">
                                <span class="text-sm font-bold uppercase tracking-widest text-gray-900">Product Details</span>
                                <span class="ml-6 flex items-center">
                                    <svg class="h-5 w-5 transform transition-transform duration-200" :class="activeTab === 'details' ? '-rotate-180' : 'rotate-0'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>
                            <div x-show="activeTab === 'details'" x-collapse class="pb-6 prose prose-sm text-gray-500 max-w-none">
                                {!! nl2br(e($product->long_description)) !!}
                            </div>
                        </div>
                        @endif
                        <div>
                            <button @click="activeTab = activeTab === 'shipping' ? null : 'shipping'" class="group relative w-full py-6 flex justify-between items-center text-left focus:outline-none">
                                <span class="text-sm font-bold uppercase tracking-widest text-gray-900">Shipping & Returns</span>
                                <span class="ml-6 flex items-center">
                                    <svg class="h-5 w-5 transform transition-transform duration-200" :class="activeTab === 'shipping' ? '-rotate-180' : 'rotate-0'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>
                            <div x-show="activeTab === 'shipping'" x-collapse class="pb-6 prose prose-sm text-gray-500">
                                <p>We offer cash on delivery across Pakistan. Standard delivery time is 3-5 working days. Returns/Exchanges accepted within 7 days of delivery for unworn items.</p>
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
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-12">
                     @foreach($relatedProducts as $related)
                    <div class="group cursor-pointer">
                        <div class="relative overflow-hidden bg-white aspect-square mb-4 rounded-sm p-6">
                            <a href="{{ route('product.show', $related->slug) }}">
                                @if($related->primaryImage)
                                    <img src="{{ asset('storage/' . $related->primaryImage->image_path) }}" 
                                         alt="{{ $related->name }} - Men's leather shoes Pakistan" 
                                         class="w-full h-full object-contain object-center transition duration-700 ease-out group-hover:scale-105"
                                         onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                @elseif($related->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $related->images->first()->image_path) }}" 
                                         alt="{{ $related->name }} - Men's leather shoes Pakistan" 
                                         class="w-full h-full object-contain object-center transition duration-700 ease-out group-hover:scale-105"
                                         onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                @else
                                    <img src="https://placehold.co/400x500?text=No+Image+400x500" class="w-full h-full object-cover object-center">
                                @endif
                            </a>
                             @if($related->isOnSale())
                                 <!-- Left Ribbon: Discount Percentage -->
                                 <div class="absolute top-0 left-0 text-white font-extrabold text-center uppercase shadow-md" style="background-color: #7B1B2A; clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 85%, 0 100%); font-size: 9px; padding: 8px 12px 14px 12px; line-height: 1; letter-spacing: 0.05em; z-index: 10;">
                                     {{ $related->discountPercentage() }}% OFF
                                 </div>
                                 <!-- Right Badge: Sale -->
                                 <div class="absolute top-0 right-0 bg-black text-white font-extrabold uppercase shadow-md" style="font-size: 9px; padding: 8px 12px; line-height: 1; letter-spacing: 0.1em; z-index: 10;">Sale</div>
                             @endif
                             @if($related->variants->sum('stock_quantity') <= 0)
                                 <div class="absolute top-0 right-0 bg-black text-white font-extrabold uppercase shadow-md" style="font-size: 9px; padding: 8px 12px; line-height: 1; letter-spacing: 0.1em; z-index: 20;">Sold Out</div>
                             @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1 tracking-wide">{{ $related->category->name ?? 'Category' }}</p>
                            <h3 class="text-base font-bold text-gray-900 mb-1">
                                <a href="{{ route('product.show', $related->slug) }}">{{ $related->name }}</a>
                            </h3>
                            <div class="flex items-center gap-3">
                                @if($related->isOnSale())
                                    <p class="text-sm text-black font-bold">Rs. {{ number_format($related->sale_price) }}</p>
                                    <p class="text-xs text-gray-400 line-through">Rs. {{ number_format($related->base_price) }}</p>
                                @else
                                    <p class="text-sm text-gray-900 font-medium">Rs. {{ number_format($related->base_price) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
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

                init() {
                    // Ensure stock is number
                    if (this.variants) {
                        this.variants.forEach(v => v.stock_quantity = parseInt(v.stock_quantity));
                    }
                    
                    // Auto-select first color/size if needed
                    if (this.uniqueColors.length === 1) {
                        this.selectColor(this.uniqueColors[0]);
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
                }
            }
        }
    </script>
</x-app-layout>
