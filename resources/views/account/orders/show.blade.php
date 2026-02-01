<x-account-layout>
    <div class="flex-1">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-serif font-bold">Order #{{ $order->order_number }}</h3>
            <span class="px-3 py-1 text-xs font-bold uppercase tracking-widest rounded-full 
                {{ $order->status === 'delivered' ? 'bg-black text-white' : 
                   ($order->status === 'cancelled' ? 'bg-gray-400 text-white' : 'bg-gray-100 text-gray-900 border border-gray-200') }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <p class="text-sm text-gray-600 mb-8">
            Placed on {{ $order->created_at->format('M d, Y') }}
        </p>

        <div class="bg-white border border-gray-200 rounded-sm overflow-x-auto mb-8">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-4 sm:px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-16 w-16 bg-gray-100 rounded overflow-hidden">
                                    @if($item->product->primaryImage)
                                         @php 
                                            $imagePath = $item->product->primaryImage->image_path;
                                            $imageUrl = Str::startsWith($imagePath, 'http') ? $imagePath : asset('storage/' . $imagePath);
                                        @endphp
                                         <img src="{{ $imageUrl }}" 
                                              class="h-16 w-16 object-cover"
                                              onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Error';">
                                    @else
                                        <div class="h-16 w-16 flex items-center justify-center text-gray-400 text-xs">No Img</div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->product->name }}</div>
                                    @if($item->variant)
                                        <div class="text-xs text-gray-500">Size: {{ $item->variant->size }} | Color: {{ $item->variant->color }}</div>
                                    @endif
                                    <div class="text-xs text-gray-500">Qty: {{ $item->quantity }} x Rs. {{ number_format($item->price) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900 font-medium">Rs. {{ number_format($item->price * $item->quantity) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                     <tr>
                        <td class="px-4 sm:px-6 py-3 text-right text-xs font-bold uppercase tracking-wider">Subtotal</td>
                        <td class="px-4 sm:px-6 py-3 text-sm font-medium text-gray-900">Rs. {{ number_format($order->total) }}</td> <!-- Assuming no tax/shipping split currently -->
                    </tr>
                    @if($order->advance_amount > 0)
                     <tr>
                        <td class="px-4 sm:px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-green-600">Advance Paid</td>
                        <td class="px-4 sm:px-6 py-3 text-sm font-bold text-green-600">- Rs. {{ number_format($order->advance_amount) }}</td>
                    </tr>
                     <tr>
                        <td class="px-4 sm:px-6 py-3 text-right text-xs font-bold uppercase tracking-wider">Remaining Amount</td>
                        <td class="px-4 sm:px-6 py-3 text-lg font-bold text-gray-900">Rs. {{ number_format($order->total - $order->advance_amount) }}</td>
                    </tr>
                    @else
                    <tr>
                        <td class="px-4 sm:px-6 py-3 text-right text-xs font-bold uppercase tracking-wider">Total</td>
                        <td class="px-4 sm:px-6 py-3 text-lg font-bold text-gray-900">Rs. {{ number_format($order->total) }}</td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gray-50 p-6 rounded-sm">
                <h4 class="text-sm font-bold uppercase tracking-widest mb-4">Billing Address</h4>
                <address class="not-italic text-sm text-gray-600 leading-relaxed">
                    <span class="block font-bold text-gray-900">{{ $order->customer_name }}</span>
                    {{ $order->address }}<br>
                    {{ $order->city }}<br>
                    {{ $order->postal_code }}<br>
                    <br>
                    {{ $order->phone }}<br>
                    {{ $order->email }}
                </address>
            </div>
             <div class="bg-gray-50 p-6 rounded-sm">
                <h4 class="text-sm font-bold uppercase tracking-widest mb-4">Payment Method</h4>
                <p class="text-sm text-gray-600">Cash on Delivery</p>
                <p class="text-xs text-gray-500 mt-2">Payment Status: <span class="capitalize font-medium">{{ $order->payment_status }}</span></p>
            </div>
        </div>
        
         <div class="mt-8">
            <a href="{{ route('account.orders') }}" class="text-sm text-gray-600 hover:text-black hover:underline">&larr; Back to Order History</a>
        </div>
    </div>
</x-account-layout>
