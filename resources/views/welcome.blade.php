<x-app-layout>
    <!-- Hero Section -->
    <section class="relative h-[85vh] w-full overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero-shoes.png') }}" alt="Velto Luxury Collection" class="w-full h-full object-cover object-center transform scale-105">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
        </div>
        <div class="relative h-full max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12 flex items-center">
            <div class="max-w-3xl text-white pt-20 animate-fade-in-up">
                <span class="block text-sm md:text-base font-bold uppercase tracking-[0.3em] mb-6 text-gray-200">Exquisite Craftsmanship</span>
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-serif font-bold leading-none mb-8 tracking-tight">
                    Walk in <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">Excellence</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-300 mb-12 max-w-lg font-light leading-relaxed tracking-wide">
                    Elevate your stride with our 2026 Artisan Collection. Where Italian heritage meets modern luxury.
                </p>
                <div class="flex flex-col sm:flex-row gap-6">
                    <a href="{{ route('shop.index') }}" class="inline-flex justify-center items-center bg-white text-black px-12 py-5 text-sm font-bold uppercase tracking-widest hover:bg-gray-200 transition-all duration-300 transform hover:-translate-y-1">
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
                <div class="col-span-1 md:col-span-2 row-span-2 relative group overflow-hidden h-[600px]">
                    <img src="https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-all"></div>
                    <div class="absolute bottom-10 left-10 text-white">
                        <h3 class="text-4xl font-serif font-bold mb-4">Men's Formal</h3>
                        <p class="mb-6 opacity-90">358 items</p>
                        <a href="{{ route('shop.category', 'formal-shoes') }}" class="text-sm font-bold uppercase tracking-widest border-b-2 border-white pb-1 hover:text-gray-300 hover:border-gray-300 transition-colors">Shop Now</a>
                    </div>
                </div>

                <div class="col-span-1 relative group overflow-hidden h-[290px]">
                    <img src="https://images.unsplash.com/photo-1533867617858-e7b97e060509?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                     <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-all"></div>
                    <div class="absolute bottom-8 left-8 text-white">
                        <h3 class="text-2xl font-serif font-bold mb-2">Casual</h3>
                        <p class="mb-4 opacity-90">273 items</p>
                        <a href="{{ route('shop.category', 'casual-loafers') }}" class="text-xs font-bold uppercase tracking-widest border-b border-white pb-1 hover:text-gray-300 hover:border-gray-300 transition-colors">Shop Now</a>
                    </div>
                </div>

                <div class="col-span-1 relative group overflow-hidden h-[290px]">
                    <img src="https://images.unsplash.com/photo-1559563458-52c69522144a?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                     <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-all"></div>
                    <div class="absolute bottom-8 left-8 text-white">
                        <h3 class="text-2xl font-serif font-bold mb-2">Accessories</h3>
                        <p class="mb-4 opacity-90">159 items</p>
                        <a href="{{ route('shop.category', 'accessories') }}" class="text-xs font-bold uppercase tracking-widest border-b border-white pb-1 hover:text-gray-300 hover:border-gray-300 transition-colors">Shop Now</a>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 relative group overflow-hidden h-[290px]">
                     <img src="https://images.unsplash.com/photo-1608256246200-53e635b5b65f?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                     <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-all"></div>
                    <div class="absolute bottom-8 left-8 text-white">
                        <h3 class="text-2xl font-serif font-bold mb-2">Rugged Boots</h3>
                         <p class="mb-4 opacity-90">120 items</p>
                        <a href="{{ route('shop.category', 'leather-boots') }}" class="text-xs font-bold uppercase tracking-widest border-b border-white pb-1 hover:text-gray-300 hover:border-gray-300 transition-colors">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Arrivals -->
    <section id="new-arrivals" class="py-24">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                     <span class="text-sm font-bold uppercase tracking-widest text-gray-500 mb-2 block">Exclusive</span>
                    <h2 class="text-4xl font-serif font-bold">New Arrivals</h2>
                </div>
                <div class="flex space-x-8 mt-6 md:mt-0 text-gray-500 font-medium text-sm uppercase tracking-wide">
                    <button class="text-black border-b-2 border-black pb-1">All</button>
                    <button class="hover:text-black transition-colors">Men's</button>
                    <button class="hover:text-black transition-colors">Women's</button>
                    <button class="hover:text-black transition-colors">Accessories</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-12">
                @foreach(\App\Models\Product::with('primaryImage', 'category')->take(8)->inRandomOrder()->get() as $product)
                    <div class="group">
                        <div class="relative overflow-hidden mb-6 h-[400px] bg-gray-100">
                             @if($product->primaryImage)
                                <img src="{{ $product->primaryImage->url }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     onerror="this.onerror=null;this.src='https://placehold.co/400x500?text=Image+Not+Found';">
                             @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                             @endif
                            
                            @if($loop->index % 3 == 0)
                                <div class="absolute top-4 left-4 bg-black text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1">New</div>
                            @elseif($loop->index % 4 == 0)
                                <div class="absolute top-4 left-4 bg-black text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1">Sale</div>
                            @endif

                            <ul class="absolute bottom-[-50px] left-0 right-0 flex justify-center space-x-4 transition-all duration-300 group-hover:bottom-6">
                                <li>
                                    <button class="bg-white p-3 rounded-full text-gray-800 hover:bg-black hover:text-white hover:rotate-[360deg] transition-all duration-500 shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    </button>
                                </li>
                                    <li>
                                    <button class="bg-white p-3 rounded-full text-gray-800 hover:bg-black hover:text-white hover:rotate-[360deg] transition-all duration-500 shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    </button>
                                </li>
                                    <li>
                                    <button class="bg-white p-3 rounded-full text-gray-800 hover:bg-black hover:text-white hover:rotate-[360deg] transition-all duration-500 shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <h3 class="text-base text-gray-900 font-medium mb-1 group-hover:text-black transition-colors">
                                <a href="#">{{ $product->name }}</a>
                            </h3>
                            <div class="flex justify-center items-center space-x-1 mb-2">
                                @for($i=0; $i<5; $i++)
                                    <svg class="w-3 h-3 text-black" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <p class="font-bold text-gray-900">${{ $product->price }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="py-20 border-t border-gray-100">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                         <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 uppercase">Free Shipping</h4>
                        <p class="text-xs text-gray-500">For all orders over $99</p>
                    </div>
                </div>
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
            </div>
        </div>
    </section>

</x-app-layout>
