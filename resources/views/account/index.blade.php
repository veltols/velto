<x-account-layout>
    <div class="bg-gray-50 p-8 rounded-sm mb-8">
        <h3 class="text-2xl font-serif mb-2">Hello, {{ auth()->user()->name }}</h3>
        <p class="text-gray-600">From your account dashboard you can view your <a href="{{ route('account.orders') }}" class="text-black underline">recent orders</a>, manage your <a href="{{ route('profile.edit') }}" class="text-black underline">shipping and billing addresses</a>, and <a href="{{ route('profile.edit') }}" class="text-black underline">edit your password and account details</a>.</p>
    </div>

    <h3 class="text-lg font-serif font-bold mb-4">Recent Orders</h3>
    @if($recentOrders->count() > 0)
        <div class="bg-white border border-gray-200 rounded-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentOrders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->order_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $order->status === 'delivered' ? 'bg-black text-white' : 
                                       ($order->status === 'cancelled' ? 'bg-gray-400 text-white' : 'bg-gray-100 text-gray-900 border border-gray-200') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rs. {{ number_format($order->total) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('account.orders.show', $order) }}" class="text-black hover:underline">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">No orders found.</p>
        <a href="{{ route('shop.index') }}" class="inline-block mt-4 bg-black text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">Start Shopping</a>
    @endif
</x-account-layout>
