<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-8 text-center border-b border-gray-200">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-serif font-bold text-gray-900 mb-2">Thank you for your order!</h1>
                    <p class="text-gray-500">Your order <span class="font-bold text-gray-900">{{ $order->order_number }}</span> has been placed successfully.</p>
                    <p class="text-gray-500 mt-2">We've sent a confirmation email to <span class="font-medium text-gray-900">{{ $order->email }}</span>.</p>
                </div>
                
                <div class="p-8">
                    <!-- Order Details -->
                    <div class="mb-8">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Order Details</h2>
                        <ul class="divide-y divide-gray-200 border border-gray-200 rounded-md">
                            @foreach($order->items as $item)
                                <li class="p-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $item->product_name }}</p>
                                            @if($item->variant_info)
                                            <p class="text-sm text-gray-500">{{ $item->variant_info }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400">Qty: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">Rs. {{ number_format($item->subtotal) }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Customer & Shipping -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Shipping Address</h3>
                            <div class="bg-gray-50 p-4 rounded-md text-sm text-gray-700">
                                <p class="font-bold text-gray-900">{{ $order->customer_name }}</p>
                                <p>{{ $order->shipping_address }}</p>
                                <p>{{ $order->city }} {{ $order->postal_code }}</p>
                                <p class="mt-2">{{ $order->phone }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Payment Summary</h3>
                             <div class="bg-gray-50 p-4 rounded-md text-sm">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Payment Method</span>
                                    <span class="font-medium text-gray-900 capitalize">{{ $order->payment_method_display ?? 'Cash on Delivery' }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">Rs. {{ number_format($order->subtotal) }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium text-gray-900">Rs. {{ number_format($order->shipping_cost) }}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-200 mt-2">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-gray-900 text-lg">Rs. {{ number_format($order->total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center pt-8 border-t border-gray-200">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-black hover:bg-gray-800">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
