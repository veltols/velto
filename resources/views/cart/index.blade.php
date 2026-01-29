<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="bg-white min-h-screen" x-data="cart()" x-init="initCart()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-serif font-bold text-gray-900 tracking-tight">Shopping Bag</h1>
                <span x-show="items.length > 0" class="text-gray-500 text-sm" x-text="items.length + ' items'"></span>
            </div>

            <!-- Cart Content -->
            <div x-show="items.length > 0" class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
                
                <!-- Items List -->
                <section class="lg:col-span-8">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-4">
                        <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Product</h2>
                        <button @click="clearCart()" class="text-xs text-gray-900 hover:text-black font-bold uppercase tracking-wide flex items-center transition-colors underline">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Empty Cart
                        </button>
                    </div>

                    <ul role="list" class="divide-y divide-gray-100">
                        <template x-for="(item, index) in items" :key="item.id">
                            <li class="flex py-6 sm:py-8 transition-opacity duration-300" :class="{'opacity-50 pointer-events-none': item.loading}">
                                <!-- Image -->
                                <div class="flex-shrink-0 h-24 w-24 sm:h-32 sm:w-32 rounded-sm overflow-hidden border border-gray-200 bg-gray-50">
                                    <img :src="item.image_url" 
                                         :alt="item.name" 
                                         class="w-full h-full object-center object-cover"
                                         onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Error';">
                                </div>

                                <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                                    <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                        <div>
                                            <div class="flex justify-between">
                                                <h3 class="text-base font-bold text-gray-900">
                                                    <a :href="'/product/' + item.slug" x-text="item.name" class="hover:text-gray-600 transition-colors"></a>
                                                </h3>
                                            </div>
                                            <div class="mt-2 space-y-1">
                                                <template x-if="item.size">
                                                    <div class="flex items-center text-sm">
                                                        <span class="text-gray-500 min-w-[3rem]">Size:</span>
                                                        <span class="font-bold text-gray-900" x-text="item.size"></span>
                                                    </div>
                                                </template>
                                                <template x-if="item.color">
                                                    <div class="flex items-center text-sm">
                                                        <span class="text-gray-500 min-w-[3rem]">Color:</span>
                                                        <span class="font-bold text-gray-900" x-text="item.color"></span>
                                                    </div>
                                                </template>
                                            </div>
                                            <!-- Price -->
                                            <div class="mt-2 flex items-center gap-2">
                                                <p class="text-sm font-bold text-black" x-text="'Rs. ' + numberFormat(item.price)"></p>
                                                <template x-if="item.is_on_sale">
                                                    <p class="text-xs text-gray-400 line-through" x-text="'Rs. ' + numberFormat(item.original_price)"></p>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="mt-4 sm:mt-0 sm:pr-9 flex flex-col items-end justify-between">
                                            <!-- Quantity Control -->
                                            <div class="flex items-center border border-gray-300 rounded-sm">
                                                <button @click="updateQuantity(item.id, item.quantity - 1)" 
                                                        class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition disabled:opacity-50"
                                                        :disabled="item.quantity <= 1">
                                                    -
                                                </button>
                                                <span class="px-3 py-1 text-sm font-bold text-gray-900 w-8 text-center" x-text="item.quantity"></span>
                                                <button @click="updateQuantity(item.id, item.quantity + 1)" 
                                                        class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition">
                                                    +
                                                </button>
                                            </div>

                                            <!-- Remove -->
                                            <div class="absolute top-0 right-0">
                                                <button type="button" @click="removeItem(item.id)" class="-m-2 p-2 inline-flex text-gray-400 hover:text-black transition-colors">
                                                    <span class="sr-only">Remove</span>
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Item Subtotal -->
                                    <p class="mt-4 text-sm text-gray-500 text-right">
                                        Subtotal: <span class="font-bold text-gray-900" x-text="'Rs. ' + numberFormat(item.price * item.quantity)"></span>
                                    </p>
                                </div>
                            </li>
                        </template>
                    </ul>
                </section>

                <!-- Order Summary -->
                <section class="mt-16 bg-gray-50 rounded-lg px-4 py-6 sm:p-8 lg:mt-0 lg:col-span-4 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 font-serif mb-6">Order Summary</h2>

                    <dl class="space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900" x-text="cartTotalFormatted"></dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-sm text-gray-600">Shipping estimate</dt>
                            <dd class="text-sm font-medium text-gray-900">Calculated at checkout</dd>
                        </div>
                        
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-bold text-gray-900">Order Total</dt>
                            <dd class="text-xl font-bold text-gray-900" x-text="cartTotalFormatted"></dd>
                        </div>
                    </dl>

                    <div class="mt-8">
                        <a href="{{ route('checkout.index') }}" class="w-full bg-black text-white h-12 flex items-center justify-center rounded-sm text-sm font-bold uppercase tracking-[0.15em] shadow-lg hover:shadow-xl hover:bg-gray-800 transition transform hover:-translate-y-0.5">
                            Proceed to Checkout
                        </a>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('shop.index') }}" class="text-sm text-gray-500 hover:text-black hover:underline transition-colors">
                            or Continue Shopping
                        </a>
                    </div>
                </section>
            </div>

            <!-- Empty State -->
            <div x-show="items.length === 0" x-cloak class="text-center py-32">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 font-serif mb-2">Your Bag is Empty</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Looks like you haven't added anything to your cart yet. Explore our collection to find your perfect pair.</p>
                <a href="{{ route('shop.index') }}" class="inline-flex bg-black text-white px-8 py-3 rounded-sm text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-lg">
                    Start Shopping
                </a>
            </div>
        </div>
    </div>


    @php
        $jsItems = $cartItems->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->product->name,
                'slug' => $item->product->slug,
                'size' => $item->variant ? $item->variant->size : null,
                'color' => $item->variant ? $item->variant->color : null,
                'price' => $item->variant ? $item->variant->final_price : $item->product->price,
                'original_price' => $item->variant ? ($item->variant->price ?: $item->product->base_price) : $item->product->base_price,
                'is_on_sale' => $item->variant ? $item->variant->isOnSale() : $item->product->isOnSale(),
                'quantity' => $item->quantity,
                'stock' => $item->variant ? $item->variant->stock_quantity : $item->product->stock_quantity,
                'image_url' => $item->product->primaryImage 
                    ? (Str::startsWith($item->product->primaryImage->image_path, 'http') 
                        ? $item->product->primaryImage->image_path 
                        : asset('storage/' . $item->product->primaryImage->image_path))
                    : 'https://placehold.co/100x100?text=No+Img',
                'loading' => false
            ];
        });
    @endphp

    <script>
        function cart() {
            return {
                items: @json($jsItems),
                
                cartTotal: {{ $subtotal }},

                get cartTotalFormatted() {
                    return 'Rs. ' + this.numberFormat(this.cartTotal);
                },

                numberFormat(number) {
                    return new Intl.NumberFormat('en-PK').format(number);
                },
                
                initCart() {
                    // Optional: fetch fresh data just in case
                },

                updateQuantity(id, newQty) {
                    if(newQty < 1) return;
                    
                    const item = this.items.find(i => i.id === id);
                    if(!item) return;

                    // Optimistic update check (prevent > stock via JS first if possible, though strict dynamic stock might vary)
                    // We rely on backend validation primarily
                    
                    item.loading = true;

                    fetch(`/cart/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ 
                            _method: 'PUT',
                            quantity: newQty 
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        item.loading = false;
                        if(data.success) {
                            item.quantity = newQty;
                            this.cartTotal = parseInt(data.cart_total.replace(/[^0-9]/g, ''));
                            
                            // Update header cart count
                            document.querySelectorAll('#cart-count').forEach(el => el.innerText = data.cart_count);
                            // Update header cart total
                            document.querySelectorAll('#cart-total').forEach(el => el.innerText = data.cart_total);
                        } else {
                            showNotification(data.message || 'Cannot update quantity', 'error');
                        }
                    })
                    .catch(err => {
                        item.loading = false;
                        showNotification('Something went wrong', 'error');
                    });
                },

                removeItem(id) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to remove this item from your cart?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#000',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, remove it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const item = this.items.find(i => i.id === id);
                            if(item) item.loading = true;

                            fetch(`/cart/${id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ _method: 'DELETE' })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    Swal.fire({
                                        title: 'Removed!',
                                        text: 'Item has been removed.',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                    })
                },

                clearCart() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to clear your entire cart?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#000',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, empty it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ route('cart.clear') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ _method: 'DELETE' })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    Swal.fire({
                                        title: 'Cleared!',
                                        text: 'Your cart is now empty.',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                    })
                }
            }
        }
    </script>
</x-app-layout>
