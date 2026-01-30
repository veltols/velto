<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title') - {{ config('app.name', 'Velto') }} @else {{ config('app.name', 'Velto') }} @endif</title>
    @stack('seo')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/headerlogo.png') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        
        /* Nav Underline Effect */
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 1.5px;
            bottom: -4px;
            left: 0;
            background-color: currentColor;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body class="antialiased bg-white text-gray-900 flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false, cartOpen: false }">
    
    <!-- Top Utility Bar -->
    <div class="bg-black text-white py-2 px-4 text-xs font-medium tracking-wide">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12 flex justify-center items-center">
             <div>
                 <span class="text-white">Welcome to Velto Leather Shoes</span>
             </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-40">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="flex justify-between items-center h-20 lg:h-24">
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-4">
                    <button @click="mobileMenuOpen = true" class="lg:hidden p-2 -ml-2 text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/headerlogo.png') }}" alt="Velto Leather Shoes" class="h-10 w-auto">
                    </a>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="hidden lg:block flex-1 max-w-2xl mx-12">
                    <form action="{{ route('shop.index') }}" method="GET" class="relative group">
                        <div class="flex items-center border border-gray-200 rounded-full bg-white focus-within:border-black focus-within:shadow-md transition-all duration-300 overflow-hidden">
                            <input type="text" name="search" placeholder="Search for products..." class="w-full border-none bg-transparent px-6 py-3 text-sm focus:ring-0 placeholder-gray-400">
                            <button type="submit" class="px-6 py-3 text-gray-500 hover:text-black transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-6 lg:space-x-8">
                     @auth
                        <div class="relative" x-data="{ userOpen: false }">
                            <button @click="userOpen = !userOpen" class="flex items-center space-x-2 text-gray-900 hover:text-gray-600 transition">
                                <div class="p-2 rounded-full hover:bg-gray-50 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div class="hidden lg:block text-left">
                                    <p class="text-[10px] uppercase text-gray-500 font-bold tracking-wider">Account</p>
                                    <p class="text-xs font-semibold">{{ auth()->user()->name }}</p>
                                </div>
                            </button>
                            <div x-show="userOpen" @click.outside="userOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50">
                                <a href="{{ route('account.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Dashboard</a>
                                <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">Admin Panel</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center space-x-2 text-gray-900 hover:text-gray-600 transition">
                            <div class="p-2 rounded-full hover:bg-gray-50 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div class="hidden lg:block text-left">
                                <p class="text-[10px] uppercase text-gray-500 font-bold tracking-wider">Account</p>
                                <p class="text-xs font-semibold">Sign In</p>
                            </div>
                        </a>
                    @endauth



                <a href="{{ route('cart.index') }}" class="flex items-center space-x-2 text-gray-900 hover:text-gray-600 transition">
                        <div class="relative p-2 rounded-full hover:bg-gray-50 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span id="cart-count" class="absolute top-1 right-0 rounded-full bg-black text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center">{{ $cartCount ?? 0 }}</span>
                        </div>
                        <div class="hidden lg:block text-left">
                            <p class="text-[10px] uppercase text-gray-500 font-bold tracking-wider">Cart</p>
                             <p id="cart-total" class="text-xs font-semibold">PKR {{ number_format($cartTotal ?? 0) }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav class="hidden lg:block border-b border-gray-100/50 bg-white">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
            <div class="flex items-center space-x-12 h-14">
                <!-- Dropdown -->
                <div class="relative h-full" x-data="{ open: false }">
                    <button @click="open = !open" 
                        class="flex items-center bg-black text-white px-8 h-full text-xs font-black uppercase tracking-[0.15em] hover:bg-black/90 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        BROWSE CATEGORIES
                    </button>
                    <div x-cloak x-show="open" @click.outside="open = false" x-transition.opacity.duration.200ms 
                        class="absolute top-full left-0 w-64 bg-white shadow-xl border border-gray-100 py-2 z-50">
                        @if(isset($categories) && $categories->count() > 0)
                            @foreach($categories as $category)
                                <a href="{{ route('shop.category', $category->slug) }}" class="block px-6 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-black transition-colors border-b border-gray-50 last:border-0 uppercase tracking-wide">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        @else
                            <span class="block px-6 py-3 text-sm text-gray-500">No categories found</span>
                        @endif
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="flex items-center space-x-12 h-full">
                    <a href="{{ route('home') }}" class="nav-link text-[12px] font-black uppercase tracking-[0.1em] text-gray-900 whitespace-nowrap">HOME</a>
                    <a href="{{ route('shop.index') }}" class="nav-link text-[12px] font-black uppercase tracking-[0.1em] text-gray-900 whitespace-nowrap">SHOP</a>
                    <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="nav-link text-[12px] font-black uppercase tracking-[0.1em] text-gray-900 whitespace-nowrap">NEW ARRIVAL</a>
                    <a href="{{ route('shop.index', ['on_sale' => 1]) }}" class="nav-link text-[12px] font-black uppercase tracking-[0.1em] text-gray-900 whitespace-nowrap">SALE</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div x-cloak x-show="mobileMenuOpen" class="relative z-50 lg:hidden" aria-modal="true">
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>
        
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-full max-w-xs bg-white shadow-2xl overflow-y-auto">
            <div class="flex items-center justify-between px-6 pt-6 pb-8">
                <img src="{{ asset('images/headerlogo.png') }}" alt="Velto Leather Shoes" class="h-8 w-auto">
                <button @click="mobileMenuOpen = false" class="-m-2 p-2 text-gray-500 hover:text-gray-700">
                    <span class="sr-only">Close menu</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="px-6 space-y-8">
                <nav class="flex flex-col space-y-6">
                    <a href="{{ route('home') }}" class="text-lg font-medium text-gray-900 border-b border-gray-100 pb-2">Home</a>
                    <a href="{{ route('shop.index') }}" class="text-lg font-medium text-gray-900 border-b border-gray-100 pb-2">Shop</a>
                    <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="text-lg font-medium text-gray-900 border-b border-gray-100 pb-2">New Arrivals</a>
                    <a href="{{ route('shop.index', ['on_sale' => 1]) }}" class="text-lg font-medium text-gray-900 border-b border-gray-100 pb-2">Sale</a>
                    @foreach($categories as $category)
                        <a href="{{ route('shop.category', $category->slug) }}" class="text-lg font-medium text-gray-900 border-b border-gray-100 pb-2">{{ $category->name }}</a>
                    @endforeach
                </nav>
                 @if(auth()->check() && auth()->user()->is_admin)
                    <div class="pt-6">
                         <a href="{{ route('admin.dashboard') }}" class="block w-full text-center bg-black text-white px-4 py-3 text-sm font-semibold uppercase tracking-widest">Admin Dashboard</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-black text-white pt-20 pb-10 border-t border-gray-800">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
             <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-16 border-b border-white/10 pb-16">
                 <!-- Brand Section -->
                 <div class="space-y-6">
                     <a href="/" class="block">
                        <img src="{{ asset('images/footerlogo.png') }}" alt="Velto" class="h-12 w-auto">
                     </a>
                     <p class="text-gray-400 leading-relaxed max-w-sm font-light text-sm">
                        Crafting legacy through leather. Velto combines Italian tradition with modern aesthetics to create footwear that stands the test of time.
                     </p>
                 </div>
                 
                 <!-- Contact Info -->
                 <div>
                     <h4 class="text-sm font-bold uppercase tracking-[0.2em] mb-8 text-white relative inline-block after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-8 after:h-0.5 after:bg-white">Contact Us</h4>
                     <ul class="space-y-6 text-sm text-gray-400 font-light">
                         <li class="flex items-start space-x-4">
                             <svg class="w-5 h-5 text-gray-100 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                             <span>Abid Market Qainchi Main Ferozpur Road,<br>Lahore</span>
                         </li>
                         <li class="flex items-center space-x-4">
                             <svg class="w-5 h-5 text-gray-100 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                             <span>0306 9101633</span>
                         </li>
                         <li class="flex items-center space-x-4">
                             <svg class="w-5 h-5 text-gray-100 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                             <span>support@veltshoes.com</span>
                         </li>
                     </ul>
                 </div>

                 <!-- Social Media -->
                 <div>
                     <h4 class="text-sm font-bold uppercase tracking-[0.2em] mb-8 text-white relative inline-block after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-8 after:h-0.5 after:bg-white">Follow Us</h4>
                     <p class="text-gray-400 text-sm mb-6 font-light">Stay updated with our latest collections and offers.</p>
                     <div class="flex space-x-4">
                        <a href="https://www.facebook.com/p/Velto-LS-61567129000247/" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white hover:text-black transition-all duration-300 transform hover:-translate-y-1">
                            <img src="{{ asset('images/facebook.svg') }}" alt="Facebook" class="w-5 h-5">
                        </a>
                        <a href="https://www.instagram.com/velto_ls/" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white hover:text-black transition-all duration-300 transform hover:-translate-y-1">
                            <img src="{{ asset('images/insta.svg') }}" alt="Instagram" class="w-5 h-5">
                        </a>
                        <a href="https://www.tiktok.com/@velto.ls" target="_blank" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white hover:text-black transition-all duration-300 transform hover:-translate-y-1">
                            <img src="{{ asset('images/tiktok.svg') }}" alt="TikTok" class="w-5 h-5">
                        </a>
                     </div>
                 </div>
             </div>

             <div class="pt-8 flex flex-col md:flex-row justify-center items-center text-xs text-gray-500 font-medium uppercase tracking-wider">
                 <p>&copy; {{ date('Y') }} Velto Leather Shoes. All rights reserved.</p>
             </div>
        </div>
    </footer>
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/923069101633" target="_blank" style="background-color: #25D366;" class="fixed bottom-8 right-8 z-50 text-white p-4 rounded-full shadow-lg transition-transform hover:scale-110 flex items-center justify-center border border-white/20">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-8.683-2.031-.967-.272-.297-.471-.421-.917-.421-.446 0-.967.173-1.464.718-.495.544-1.881 1.782-1.881 4.354 0 2.572 1.881 5.045 2.154 5.391.272.347 3.658 5.618 8.905 7.697 3.639 1.442 4.385 1.157 5.176 1.082.793-.075 2.527-1.041 2.872-2.03.348-.991.348-1.832.248-2.006z"/></svg>
    </a>

    <!-- Toast Notification -->
    <div 
        x-data="{ show: false, message: '', type: 'success' }" 
        @notify.window="show = true; message = $event.detail.message; type = $event.detail.type || 'success'; setTimeout(() => show = false, 3000)"
        x-cloak
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="fixed top-24 right-8 z-[999] px-6 py-4 rounded-sm shadow-2xl flex items-center space-x-4 min-w-[320px]"
        :class="type === 'success' ? 'bg-black text-white' : 'bg-gray-900 text-white'"
    >
       <div class="flex-shrink-0">
           <template x-if="type === 'success'">
               <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
           </template>
           <template x-if="type === 'error'">
               <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
           </template>
       </div>
       <p x-text="message" class="text-sm font-bold uppercase tracking-wide"></p>
    </div>

    <!-- Cart Scripts -->
    <script>
        function showNotification(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('notify', { detail: { message, type } }));
        }

        async function addToCart(productId, quantity = 1, variantId = null) {
            try {
                const response = await fetch("{{ route('cart.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        variant_id: variantId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update ALL instances of cart-count and cart-total
                    const countEls = document.querySelectorAll('#cart-count');
                    const totalEls = document.querySelectorAll('#cart-total');

                    countEls.forEach(el => {
                        el.innerHTML = data.cart_count;
                        el.innerText = data.cart_count;
                    });

                    totalEls.forEach(el => {
                        el.innerHTML = data.cart_total;
                        el.innerText = data.cart_total;
                    });
                    
                    // Show success toast
                    showNotification(data.message || 'Item added to cart!', 'success');
                } else {
                    showNotification(data.message || 'Could not add to cart.', 'error');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showNotification('Failed to add item to cart.', 'error');
            }
        }
    </script>
</body>
</html>
