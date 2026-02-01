<x-account-layout>
    <div class="flex-1">
        <h3 class="text-lg font-serif font-bold mb-6">My Orders</h3>
        
        @if($orders->count() > 0)
            <div class="bg-white border border-gray-200 rounded-sm overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->order_number }}</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $order->status === 'delivered' ? 'bg-black text-white' : 
                                           ($order->status === 'cancelled' ? 'bg-gray-400 text-white' : 'bg-gray-100 text-gray-900 border border-gray-200') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rs. {{ number_format($order->total) }}</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('account.orders.show', $order) }}" class="text-black hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        @else
            <div class="text-center py-12 bg-gray-50 rounded-sm">
                <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('shop.index') }}" class="inline-block bg-black text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">Start Shopping</a>
            </div>
        @endif
    </div>
</x-account-layout>
