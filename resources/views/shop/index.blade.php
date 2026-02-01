<x-app-layout>
    @section('title', isset($category) ? $category->name . ' - Shop' : 'Shop All Products')
    @push('seo')
        <meta name="description" content="{{ isset($category) ? 'Shop our exclusive collection of ' . $category->name . ' at Velto.' : 'Explore the full collection of Velto handcrafted leather shoes.' }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ isset($category) ? $category->name . ' - Velto Shop' : 'Shop All Products - Velto' }}">
        <meta property="og:description" content="{{ isset($category) ? 'Shop our exclusive collection of ' . $category->name . ' at Velto.' : 'Explore the full collection of Velto handcrafted leather shoes.' }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ asset('images/banner-shoes-wide.png') }}">
    @endpush
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8" x-data="{ filtersOpen: false }">
                <!-- Mobile Filter Toggle -->
                <div class="md:hidden mb-4">
                    <button @click="filtersOpen = !filtersOpen" class="w-full flex items-center justify-center space-x-2 bg-white border border-gray-200 px-4 py-3 text-sm font-bold uppercase tracking-widest text-gray-900 shadow-sm hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        <span>Filters</span>
                    </button>
                </div>

                <!-- Sidebar Filters -->
                <!-- Sidebar Filters -->
                <!-- Sidebar Filters -->
                <div class="fixed inset-0 z-40 flex md:static md:z-auto md:w-1/4" :class="filtersOpen ? 'block' : 'hidden md:block'" x-show="filtersOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    
                    <!-- Mobile Backdrop -->
                    <div class="fixed inset-0 bg-black bg-opacity-25 md:hidden" @click="filtersOpen = false" aria-hidden="true"></div>

                    <div class="relative w-full max-w-xs h-full ml-auto md:ml-0 md:w-full bg-white p-6 shadow-xl md:shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] rounded-sm md:rounded-sm overflow-y-auto md:overflow-visible sticky-none md:sticky md:top-24">
                        
                        <!-- Mobile Close Button -->
                        <div class="flex items-center justify-between mb-6 md:hidden">
                            <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                            <button @click="filtersOpen = false" class="-mr-2 w-10 h-10 bg-white p-2 rounded-md flex items-center justify-center text-gray-400 hover:bg-gray-50">
                                <span class="sr-only">Close menu</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <form action="{{ url()->current() }}" method="GET">
                            <!-- Preserve Search -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div class="flex justify-between items-center mb-6">
                                <h2 class="font-serif text-xl font-bold text-gray-900">Filters</h2>
                                @if(request()->anyFilled(['min_price', 'max_price', 'sizes', 'colors', 'sort']))
                                    <a href="{{ url()->current() }}" class="text-xs font-bold uppercase tracking-wider text-gray-900 hover:text-black transition underline">Clear All</a>
                                @endif
                            </div>

                            <!-- Categories -->
                            <div class="mb-8">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-900 border-b border-gray-100 pb-3 mb-4">Categories</h3>
                                <div class="max-h-60 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent">
                                    <ul class="space-y-4">
                                        <li>
                                            <a href="{{ route('shop.index') }}" class="flex items-center text-sm group">
                                                <span class="w-2 h-2 rounded-full {{ !isset($category) ? 'bg-black' : 'bg-transparent border border-gray-300 group-hover:border-black' }} mr-4 transition-all"></span>
                                                <span class="{{ !isset($category) ? 'font-medium text-black' : 'text-gray-600 group-hover:text-black' }} transition-colors">All Products</span>
                                            </a>
                                        </li>
                                        @foreach($categories as $cat)
                                            <li>
                                                <a href="{{ route('shop.category', $cat->slug) }}" class="flex items-center text-sm group">
                                                    <span class="w-2 h-2 rounded-full {{ (isset($category) && $category->id == $cat->id) ? 'bg-black' : 'bg-transparent border border-gray-300 group-hover:border-black' }} mr-4 transition-all"></span>
                                                    <span class="{{ (isset($category) && $category->id == $cat->id) ? 'font-medium text-black' : 'text-gray-600 group-hover:text-black' }} transition-colors">
                                                        {{ $cat->name }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Sort By -->
                            <div class="mb-8">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-900 border-b border-gray-100 pb-3 mb-4">Sort By</h3>
                                <div class="relative">
                                    <select name="sort" onchange="this.form.submit()" class="w-full appearance-none bg-transparent border border-gray-200 rounded-sm text-sm px-4 py-3 focus:border-black focus:ring-0 cursor-pointer">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>New Arrivals</option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Filter -->
                            <div class="mb-8">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-900 border-b border-gray-100 pb-3 mb-4">Price Range</h3>
                                <div class="flex items-center space-x-4">
                                    <div class="relative w-full">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rs.</span>
                                        <input type="number" name="min_price" value="{{ request('min_price', $minPrice) }}" class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-sm text-sm focus:border-black focus:ring-0" placeholder="Min">
                                    </div>
                                    <span class="text-gray-300">—</span>
                                    <div class="relative w-full">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rs.</span>
                                        <input type="number" name="max_price" value="{{ request('max_price', $maxPrice) }}" class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-sm text-sm focus:border-black focus:ring-0" placeholder="Max">
                                    </div>
                                </div>
                            </div>

                            <!-- Size Filter -->
                            @if($sizes->count() > 0)
                                <div class="mb-8">
                                    <h3 class="text-xs font-bold uppercase tracking-widest text-gray-900 border-b border-gray-100 pb-3 mb-4">Size</h3>
                                    <div class="max-h-48 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent">
                                        <div class="grid grid-cols-3 gap-3">
                                            @foreach($sizes as $size)
                                                <label class="cursor-pointer">
                                                    <input type="checkbox" name="sizes[]" value="{{ $size }}" {{ in_array($size, request('sizes', [])) ? 'checked' : '' }} class="peer sr-only">
                                                    <div class="w-full py-3 text-xs font-bold text-center border border-gray-200 rounded-sm peer-checked:bg-black peer-checked:text-white peer-checked:border-black hover:border-black transition duration-200">
                                                        {{ $size }}
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Color Filter -->
                            @if($colors->count() > 0)
                                <div class="mb-8">
                                    <h3 class="text-xs font-bold uppercase tracking-widest text-gray-900 border-b border-gray-100 pb-3 mb-4">Color</h3>
                                    <div class="max-h-48 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent">
                                        <div class="space-y-4">
                                            @foreach($colors as $color)
                                                <label class="flex items-center cursor-pointer group">
                                                    <div class="relative mr-4">
                                                         <input type="checkbox" name="colors[]" value="{{ $color }}" {{ in_array($color, request('colors', [])) ? 'checked' : '' }} class="peer sr-only">
                                                         <div class="w-5 h-5 border border-gray-300 rounded-sm flex items-center justify-center peer-checked:bg-black peer-checked:border-black transition">
                                                             <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                         </div>
                                                    </div>
                                                    <span class="text-sm text-gray-600 group-hover:text-black transition uppercase tracking-wide font-medium">{{ $color }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" class="w-full bg-black text-white py-4 text-xs font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition transform active:scale-95">
                                Apply Filter
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="w-full md:w-3/4">
                    <div class="mb-4 flex justify-between items-center">
                        <h1 class="text-2xl font-serif font-bold text-gray-900">
                            {{ isset($category) ? $category->name : 'All Products' }}
                        </h1>
                        <span class="text-sm text-gray-500">{{ $products->total() }} Products</span>
                    </div>

                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($products as $product)
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

                        <div class="mt-8">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="bg-white p-12 text-center rounded-lg shadow-sm">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter.</p>
                            <div class="mt-6">
                                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                                    Clear Filters
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
