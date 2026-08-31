<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    @if(config('app.env') !== 'production')
        <meta name="robots" content="noindex">
    @endif
    @if(config('app.env') == 'production')
    <meta name="google-site-verification" content="2gNOJLFRAmeJL8EBqmY7dWvhb0rQFdeHDWVhV0JXgLk" />
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title')  @else Velto Leather Shoes: Premium Quality Men's Shoes Brand | Top Shoes Brand | Velto Leather Shoes @endif</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Best quality men\'s shoes. Velto Leather Shoes Top Shoes Brand in Pakistan. Shop our latest collection of stylish and comfortable footwear designed to elevate your everyday look. Enjoy free shipping and easy returns!')">
    <meta name="keywords" content="@yield('meta_keywords', 'leather shoes, handcrafted shoes, velto, velto leather shoes, premium footwear, men shoes, luxury leather, pakistan shoes brand, customize shoes')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:title" content="@hasSection('title') @yield('title') - {{ config('app.name', 'Velto Leather Shoes') }} @else Velto Leather Shoes: Premium Quality Men's Shoes Brand | Top Shoes Brand – Velto Leather Shoes @endif">
    <meta property="og:description" content="@yield('meta_description', 'Best quality men\'s shoes. Velto Leather Shoes Top Shoes Brand in Pakistan. Shop our latest collection of stylish and comfortable footwear.')">
    <meta property="og:image" content="@yield('og_image', asset('images/headerlogo.png'))">
    <meta property="og:site_name" content="{{ config('app.name', 'Velto Leather Shoes') }}">
    <meta property="article:publisher" content="https://www.facebook.com/p/Velto-LS-61567129000247/">

    @stack('seo')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.webp') }}">

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
    @if(config('app.env') == 'production')
<!-- TikTok Pixel Code Start -->
<script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};


  ttq.load('D9TEUKBC77U97D5QJ1KG');
  ttq.page();
}(window, document, 'ttq');
</script>
<!-- TikTok Pixel Code End -->
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '2259213124835399');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=2259213124835399&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
@endif
</head>
<body class="antialiased bg-white text-gray-900 flex flex-col min-h-screen" 
      x-data="{ 
          mobileMenuOpen: false, 
          cartOpen: false,
          cartLoading: false,
          cartItems: {{ Js::from($initialCartItems ?? []) }},
          cartCount: {{ $cartCount ?? 0 }},
          cartTotalFormatted: 'PKR {{ number_format($cartTotal ?? 0) }}',
          openCartDrawer() {
              this.cartOpen = true;
          },
          closeCartDrawer() {
              this.cartOpen = false;
          },
          async fetchCart() {
              this.cartLoading = true;
              try {
                  const res = await fetch('{{ route('cart.index') }}', {
                      headers: { 'Accept': 'application/json' }
                  });
                  const data = await res.json();
                  if (data.success) {
                      this.cartItems = data.items;
                      this.cartCount = data.cart_count;
                      this.cartTotalFormatted = data.cart_total;
                  }
              } catch (e) {
                  console.error('Failed to fetch cart:', e);
              } finally {
                  this.cartLoading = false;
              }
          },
          async updateCartItemQty(id, qty) {
              if (qty < 1) return this.removeCartItem(id);
              this.cartLoading = true;
              try {
                  const res = await fetch(`{{ url('/cart') }}/${id}`, {
                      method: 'PUT',
                      headers: {
                          'Content-Type': 'application/json',
                          'Accept': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                      },
                      body: JSON.stringify({ quantity: qty })
                  });
                  const data = await res.json();
                  if (data.success) {
                      if (data.items) {
                          this.cartItems = data.items;
                      }
                      this.cartCount = data.cart_count;
                      this.cartTotalFormatted = data.cart_total;
                      updateGlobalCartHeader(data.cart_count, data.cart_total);
                  } else {
                      showNotification(data.message || 'Error updating cart', 'error');
                  }
              } catch (e) {
                  showNotification('Error updating cart', 'error');
              } finally {
                  this.cartLoading = false;
              }
          },
          async removeCartItem(id) {
              this.cartLoading = true;
              try {
                  const res = await fetch(`{{ url('/cart') }}/${id}`, {
                      method: 'DELETE',
                      headers: {
                          'Accept': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                      }
                  });
                  const data = await res.json();
                  if (data.success) {
                      if (data.items) {
                          this.cartItems = data.items;
                      } else {
                          this.cartItems = this.cartItems.filter(i => i.id !== id);
                      }
                      this.cartCount = data.cart_count;
                      this.cartTotalFormatted = data.cart_total;
                      updateGlobalCartHeader(data.cart_count, data.cart_total);
                      showNotification('Item removed from cart', 'success');
                  }
              } catch (e) {
                  showNotification('Error removing item', 'error');
              } finally {
                  this.cartLoading = false;
              }
          }
      }"
      @cart-updated.window="
          if ($event.detail.items) { cartItems = $event.detail.items; }
          if ($event.detail.cart_count !== undefined) { cartCount = $event.detail.cart_count; }
          if ($event.detail.cart_total !== undefined) { cartTotalFormatted = $event.detail.cart_total; }
      "
      @open-cart-drawer.window="cartOpen = true"
>
    
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



                <button type="button" @click="openCartDrawer()" class="flex items-center space-x-2 text-gray-900 hover:text-gray-600 transition focus:outline-none cursor-pointer">
                        <div class="relative p-2 rounded-full hover:bg-gray-50 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span id="cart-count" class="absolute top-1 right-0 rounded-full bg-black text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center" x-text="cartCount">{{ $cartCount ?? 0 }}</span>
                        </div>
                        <div class="hidden lg:block text-left">
                            <p class="text-[10px] uppercase text-gray-500 font-bold tracking-wider">Cart</p>
                             <p id="cart-total" class="text-xs font-semibold" x-text="cartTotalFormatted">PKR {{ number_format($cartTotal ?? 0) }}</p>
                        </div>
                    </button>
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
                    <a href="{{ route('shop.index', ['on_sale' => 1]) }}" class="nav-link text-[12px] font-black uppercase tracking-[0.1em] whitespace-nowrap hover:opacity-80 transition-opacity" style="color: #7B1B2A;">SALE</a>
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
                    <a href="{{ route('shop.index', ['on_sale' => 1]) }}" class="text-lg font-bold border-b border-gray-100 pb-2 flex items-center justify-between" style="color: #7B1B2A;">
                        <span>Sale</span>
                        <span class="text-white text-[9px] font-extrabold px-2 py-0.5 rounded-sm uppercase tracking-widest" style="background-color: #7B1B2A;">Hot</span>
                    </a>
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
                             <span>veltoleathershoes@gmail.com</span>
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
    <a id="global-whatsapp-btn" href="https://wa.me/923069101633" target="_blank" style="background-color: #25D366;" class="fixed bottom-8 right-8 z-50 text-white p-4 rounded-full shadow-lg transition-transform hover:scale-110 flex items-center justify-center border border-white/20">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-8.683-2.031-.967-.272-.297-.471-.421-.917-.421-.446 0-.967.173-1.464.718-.495.544-1.881 1.782-1.881 4.354 0 2.572 1.881 5.045 2.154 5.391.272.347 3.658 5.618 8.905 7.697 3.639 1.442 4.385 1.157 5.176 1.082.793-.075 2.527-1.041 2.872-2.03.348-.991.348-1.832.248-2.006z"/></svg>
    </a>

    <!-- Cart Sidebar Drawer -->
    <div x-cloak x-show="cartOpen" class="fixed inset-0 z-[9999]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div x-show="cartOpen"
             x-transition:enter="ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity cursor-pointer"
             @click="closeCartDrawer()"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-6 sm:pl-10 pointer-events-none">
            <div x-show="cartOpen"
                 x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="pointer-events-auto w-screen max-w-md bg-white shadow-2xl flex flex-col h-full">
                
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gray-50/75 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-serif font-bold uppercase tracking-wider text-gray-900" id="slide-over-title">Your Cart</h2>
                        <span class="bg-black text-white text-xs font-bold px-2.5 py-0.5 rounded-full" x-text="cartCount"></span>
                    </div>
                    <button @click.stop="closeCartDrawer()" type="button" class="p-2 text-gray-400 hover:text-black rounded-full hover:bg-gray-200/60 transition focus:outline-none cursor-pointer">
                        <span class="sr-only">Close cart</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body (Item List) -->
                <div class="flex-1 overflow-y-auto px-6 py-2 divide-y divide-gray-100 min-h-0">
                    <!-- Loading indicator -->
                    <div x-show="cartLoading" class="py-4 text-center text-xs text-gray-500 flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>Updating cart...</span>
                    </div>

                    <!-- Empty State -->
                    <div x-show="!cartLoading && cartItems.length === 0" class="py-16 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <h3 class="text-base font-serif font-bold text-gray-900 mb-1">Your cart is empty</h3>
                        <p class="text-xs text-gray-500 mb-6">Looks like you haven't added any pairs yet.</p>
                        <a href="{{ route('shop.index') }}" @click="closeCartDrawer()" class="inline-block bg-black text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition cursor-pointer">
                            Start Shopping
                        </a>
                    </div>

                    <!-- Items Loop -->
                    <template x-for="item in cartItems" :key="item.id">
                        <div class="py-4 flex gap-4">
                            <!-- Image -->
                            <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-sm border border-gray-100 bg-gray-50">
                                <img :src="item.image" :alt="item.name" class="h-full w-full object-contain object-center">
                            </div>

                            <!-- Details -->
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start gap-2">
                                        <a :href="item.url" class="text-xs sm:text-sm font-bold text-gray-900 hover:underline line-clamp-1 cursor-pointer" x-text="item.name"></a>
                                        <!-- Remove button -->
                                        <button type="button" @click.stop="removeCartItem(item.id)" class="text-gray-400 hover:text-red-600 transition p-1.5 cursor-pointer focus:outline-none" title="Remove item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                    <template x-if="item.variant">
                                        <p class="text-xs text-gray-500 mt-0.5" x-text="item.variant"></p>
                                    </template>
                                </div>

                                <div class="flex justify-between items-end mt-3">
                                    <!-- Qty Controls -->
                                    <div class="flex items-center border border-gray-200 rounded-sm">
                                        <button type="button" @click.stop="updateCartItemQty(item.id, item.quantity - 1)" class="px-2.5 py-1 text-gray-600 hover:text-black text-xs font-bold hover:bg-gray-100 transition cursor-pointer focus:outline-none">-</button>
                                        <span class="px-2 py-1 text-xs font-bold text-gray-900 min-w-[1.5rem] text-center select-none" x-text="item.quantity"></span>
                                        <button type="button" @click.stop="updateCartItemQty(item.id, item.quantity + 1)" class="px-2.5 py-1 text-gray-600 hover:text-black text-xs font-bold hover:bg-gray-100 transition cursor-pointer focus:outline-none">+</button>
                                    </div>
                                    <p class="text-xs font-bold text-gray-900" x-text="item.total_formatted"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer -->
                <div x-show="cartItems.length > 0" class="border-t border-gray-100 px-6 py-5 bg-gray-50 space-y-4 flex-shrink-0">
                    <div class="flex justify-between text-sm font-bold text-gray-900">
                        <span>Subtotal</span>
                        <span x-text="cartTotalFormatted"></span>
                    </div>
                    <p class="text-[11px] text-gray-500 text-center">Free shipping & Cash on delivery available.</p>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('cart.index') }}" @click="closeCartDrawer()" class="w-full text-center border border-black bg-white text-black py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-100 transition cursor-pointer flex items-center justify-center">
                            View Cart
                        </a>
                        <a href="{{ route('checkout.index') }}" @click="closeCartDrawer()" style="background-color: #7B1B2A;" class="w-full text-center text-white py-3 text-xs font-bold uppercase tracking-widest hover:opacity-90 transition cursor-pointer flex items-center justify-center">
                            Checkout
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

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

        function updateGlobalCartHeader(count, total) {
            const countEls = document.querySelectorAll('#cart-count');
            const totalEls = document.querySelectorAll('#cart-total');

            countEls.forEach(el => {
                el.innerHTML = count;
                el.innerText = count;
            });

            totalEls.forEach(el => {
                el.innerHTML = total;
                el.innerText = total;
            });
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
                    @if(config('app.env') == 'production')
                        // TikTok AddToCart - Production only
                        if (typeof ttq !== 'undefined') {
                            ttq.track('AddToCart', {
                                content_id: String(data.product_id),
                                content_type: 'product',
                                quantity: Number(data.quantity),
                                price: Number(data.price),
                                value: Number(data.price) * Number(data.quantity),
                                currency: 'PKR'
                            });

                            console.log('TikTok AddToCart fired:', {
                                content_id: String(data.product_id),
                                quantity: Number(data.quantity),
                                price: Number(data.price),
                                value: Number(data.price) * Number(data.quantity)
                            });
                        }
                    @endif

                    // Update header totals
                    updateGlobalCartHeader(data.cart_count, data.cart_total);

                    // Sync cart drawer data and open the drawer slidebar!
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                    window.dispatchEvent(new CustomEvent('open-cart-drawer'));

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
