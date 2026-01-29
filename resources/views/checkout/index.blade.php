<x-app-layout>
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900 mb-8 text-center">Checkout</h1>
            
            <form action="{{ route('checkout.store') }}" method="POST" class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                @csrf
                
                <!-- Contact & Shipping Info -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-medium text-gray-900 mb-6">Contact & Shipping</h2>
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', auth()->user() ? auth()->user()->name : '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                            @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user() ? auth()->user()->email : '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                            @error('email') <p class="text-gray-900 font-bold text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number (0300...)</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', auth()->user() ? auth()->user()->phone : '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                             @error('phone') <p class="text-gray-900 font-bold text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">{{ old('shipping_address', auth()->user() ? auth()->user()->address : '') }}</textarea>
                            @error('shipping_address') <p class="text-gray-900 font-bold text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city', auth()->user() ? auth()->user()->city : '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                            @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', auth()->user() ? auth()->user()->postal_code : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="mt-10 lg:mt-0">
                    <div class="bg-white p-6 rounded-lg shadow-sm sticky top-24">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Order Summary</h2>

                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <li class="flex py-6">
                                     <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                        @if($item->product->primaryImage)
                                            @php 
                                                $path = $item->product->primaryImage->image_path;
                                                $url = Str::startsWith($path, 'http') ? $path : asset('storage/' . $path);
                                            @endphp
                                             <img src="{{ $url }}" 
                                                  class="h-full w-full object-cover object-center"
                                                  onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Error';">
                                        @else
                                            <div class="h-full w-full bg-gray-100 flex items-center justify-center text-xs">No Img</div>
                                        @endif
                                    </div>

                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3>{{ $item->product->name }}</h3>
                                                <div class="flex flex-col items-end">
                                                    @php
                                                        $onSale = $item->variant ? $item->variant->isOnSale() : $item->product->isOnSale();
                                                        $currentPrice = $item->variant ? $item->variant->final_price : $item->product->price;
                                                        $originalPrice = $item->variant ? ($item->variant->price ?: $item->product->base_price) : $item->product->base_price;
                                                    @endphp
                                                    <p class="ml-4 font-bold text-black">Rs. {{ number_format($currentPrice * $item->quantity) }}</p>
                                                    @if($onSale)
                                                        <p class="ml-4 text-xs text-gray-400 line-through text-right">Rs. {{ number_format($originalPrice * $item->quantity) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">{{ $item->variant ? $item->variant->size . ' | ' . $item->variant->color : '' }}</p>
                                        </div>
                                        <div class="flex flex-1 items-end justify-between text-sm">
                                            <p class="text-gray-500">Qty {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900">Rs. {{ number_format($subtotal) }}</dd>
                            </div>
                            <div class="flex items-center justify-between pt-4">
                                <dt class="text-sm text-gray-600">Shipping</dt>
                                <dd class="text-sm font-medium text-gray-900">Rs. {{ number_format($shipping) }}</dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-4 mt-4">
                                <dt class="text-base font-medium text-gray-900">Order Total</dt>
                                <dd class="text-base font-medium text-gray-900">Rs. {{ number_format($total) }}</dd>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-gray-200 pt-6">
                             <div class="flex items-center mb-4">
                                <input id="payment_cod" name="payment_method" type="radio" checked class="h-4 w-4 border-gray-300 text-black focus:ring-black">
                                <label for="payment_cod" class="ml-3 block text-sm font-medium text-gray-700">Cash on Delivery</label>
                            </div>
                            <p class="text-sm text-gray-500 mb-2">Pay when you receive your order.</p>
                            <div class="bg-gray-100 border-l-4 border-black p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-bold text-black">Payment Policy</h3>
                                        <div class="mt-2 text-sm text-gray-700 space-y-1">
                                            <p><strong>Cash on Delivery (COD) | (50% Advance Payment Required)</strong></p>
                                            <p>Since our shoes are made to order, we take <strong>50% Advance Payment</strong> at the time of booking order.</p>
                                            <p><strong>100% Payment Required for all Sale Articles.</strong></p>
                                            <p class="pt-1 font-semibold">Free shipping across Pakistan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-black border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-black">
                                Place Order (Rs. {{ number_format($total) }})
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
