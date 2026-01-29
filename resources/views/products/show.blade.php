<x-app-layout>
    @section('title', $product->name)
    @push('seo')
        <meta name="description" content="{{ Str::limit($product->description, 160) }}">
        <meta property="og:type" content="product">
        <meta property="og:title" content="{{ $product->name }} - Velto">
        <meta property="og:description" content="{{ Str::limit($product->description, 200) }}">
        <meta property="og:url" content="{{ route('product.show', $product->slug) }}">
        @if($product->primaryImage)
            <meta property="og:image" content="{{ asset('storage/' . $product->primaryImage->image_path) }}">
        @elseif($product->images->isNotEmpty())
            <meta property="og:image" content="{{ asset('storage/' . $product->images->first()->image_path) }}">
        @endif
        <meta property="product:price:amount" content="{{ $product->sale_price ?? $product->base_price }}">
        <meta property="product:price:currency" content="PKR">
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $product->name }}">
        <meta name="twitter:description" content="{{ Str::limit($product->description, 200) }}">
        @if($product->primaryImage)
            <meta name="twitter:image" content="{{ asset('storage/' . $product->primaryImage->image_path) }}">
        @endif
    @endpush
    <div class="bg-white" x-data="productDetail()">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12 py-12 lg:py-16">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-16 lg:items-start">
                
                <!-- Image Gallery -->
                <div class="relative flex flex-col gap-6 select-none">
                    <!-- Main Slider Area -->
                    <div class="relative w-full aspect-w-1 aspect-h-1 md:aspect-w-4 md:aspect-h-5 bg-gray-50 overflow-hidden rounded-sm group">
                        <!-- Scroll Snap Container -->
                        <div x-ref="slider" 
                             class="absolute inset-0 flex flex-nowrap overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide bg-white"
                             @scroll.debounce.50ms="updateActiveIndex">
                            <template x-for="(image, index) in images" :key="index">
                                <div class="w-full h-full flex-none snap-center relative" style="min-width: 100%">
                                    <img :src="getImageUrl(image)" 
                                         class="w-full h-full object-cover object-center block" 
                                         :alt="product.name"
                                         onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                </div>
                            </template>
                        </div>
                        
                        <!-- Navigation Arrows -->
                        <div class="absolute inset-0 flex items-center justify-between p-4" x-show="images.length > 1">
                            <button @click="prevImage" class="w-10 h-10 rounded-full bg-white/90 hover:bg-white flex items-center justify-center text-black shadow-md transition transform hover:scale-110 z-10 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <button @click="nextImage" class="w-10 h-10 rounded-full bg-white/90 hover:bg-white flex items-center justify-center text-black shadow-md transition transform hover:scale-110 z-10 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    <div class="grid grid-cols-5 gap-4" x-show="images.length > 1">
                        <template x-for="(image, index) in images" :key="index">
                            <button @click="activeImageIndex = index" 
                                    class="relative aspect-square overflow-hidden rounded-sm bg-gray-50 border-2 transition-all duration-300"
                                    :class="activeImageIndex === index ? 'border-black opacity-100 ring-1 ring-black' : 'border-transparent opacity-60 hover:opacity-100'">
                                <img :src="getImageUrl(image)" 
                                     class="w-full h-full object-cover object-center"
                                     onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Error';">
                            </button>
                        </template>
                    </div>
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
                                            <span class="bg-black text-white text-[10px] font-bold px-2 py-1 rounded-sm uppercase tracking-widest">-{{ $product->discountPercentage() }}%</span>
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
                                                <span class="bg-black text-white text-[10px] font-bold px-2 py-1 rounded-sm uppercase tracking-widest" x-text="'-' + currentPrice.discount + '%'"></span>
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

                        <!-- Quantity & Add to Cart -->
                        <div class="flex flex-row gap-3 mb-8 md:mb-10">
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
                        <div class="relative overflow-hidden bg-gray-50 aspect-[4/5] mb-4 rounded-sm">
                            <a href="{{ route('product.show', $related->slug) }}">
                                @if($related->primaryImage)
                                    <img src="{{ asset('storage/' . $related->primaryImage->image_path) }}" 
                                         alt="{{ $related->name }}" 
                                         class="w-full h-full object-cover object-center transition duration-700 ease-out group-hover:scale-105"
                                         onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                @elseif($related->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $related->images->first()->image_path) }}" 
                                         alt="{{ $related->name }}" 
                                         class="w-full h-full object-cover object-center transition duration-700 ease-out group-hover:scale-105"
                                         onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                                @else
                                    <img src="https://placehold.co/400x500?text=No+Image+400x500" class="w-full h-full object-cover object-center">
                                @endif
                            </a>
                             @if($related->isOnSale())
                                <div class="absolute top-2 left-2 bg-black text-white text-[10px] font-bold px-2 py-1 uppercase tracking-widest shadow-lg">Sale</div>
                            @endif
                             @if($related->variants->sum('stock_quantity') <= 0)
                                <div class="absolute top-2 right-2 bg-gray-900 text-white text-xs px-2 py-1 uppercase tracking-wide">Sold Out</div>
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

    <script>
        function productDetail() {
            return {
                product: @json($product),
                variants: @json($product->variants),
                images: @json($product->images),
                
                activeImageIndex: 0,
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

                    // Initialize images list (prepend primary if separate, but controller sends 'images' relationship which usually includes all)
                    // If images empty, enable placeholder
                    if (this.images.length === 0 && this.product.primary_image) {
                       this.images = [this.product.primary_image];
                    } else if (this.images.length === 0) {
                       // Fallback placeholder object if absolutely no images
                       this.images = [{ image_path: 'https://placehold.co/400x500?text=No+Image' }];
                    } else {
                        // Ensure primary image is first or handled? 
                        // Assuming $product->images collection contains all relevant images
                    }
                    
                    // Auto-select first color/size if needed? User usually prefers explicit selection.
                    // But if there's only one color, select it.
                    if (this.uniqueColors.length === 1) {
                        this.selectColor(this.uniqueColors[0]);
                    }
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

                updateActiveIndex() {
                    const slider = this.$refs.slider;
                    const scrollPosition = slider.scrollLeft;
                    const width = slider.offsetWidth;
                    this.activeImageIndex = Math.round(scrollPosition / width);
                },

                scrollToImage(index) {
                    this.activeImageIndex = index;
                    this.$refs.slider.scrollTo({
                        left: this.$refs.slider.offsetWidth * index,
                        behavior: 'smooth'
                    });
                },

                prevImage() {
                    const newIndex = this.activeImageIndex === 0 ? this.images.length - 1 : this.activeImageIndex - 1;
                    this.scrollToImage(newIndex);
                },

                nextImage() {
                    const newIndex = this.activeImageIndex === this.images.length - 1 ? 0 : this.activeImageIndex + 1;
                    this.scrollToImage(newIndex);
                },

                getImageUrl(image) {
                     if (!image.image_path) return image; // For string URLs (placeholders)
                     if (image.image_path.startsWith('http')) return image.image_path;
                     return '/storage/' + image.image_path;
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
