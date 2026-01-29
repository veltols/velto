@extends('layouts.admin')

@section('header', 'Order Details #' . $order->order_number)

@section('content')
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Order Info -->
        <div class="flex-1">
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                    <span class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->variant_info }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">Rs. {{ number_format($item->price) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">Rs. {{ number_format($item->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Subtotal</td>
                            <td class="px-6 py-3 text-right text-sm text-gray-900">Rs. {{ number_format($order->subtotal) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Shipping</td>
                            <td class="px-6 py-3 text-right text-sm text-gray-900">Rs. {{ number_format($order->shipping_cost) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-base font-bold text-gray-900">Total</td>
                            <td class="px-6 py-3 text-right text-base font-bold text-gray-900">Rs. {{ number_format($order->total) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Customer Details -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase">Contact</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->customer_name }}</p>
                        <p class="text-sm text-gray-900">{{ $order->email }}</p>
                        <p class="text-sm text-gray-900">{{ $order->phone }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase">Shipping Address</h4>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $order->shipping_address }}</p>
                        <p class="text-sm text-gray-900">{{ $order->city }} {{ $order->postal_code }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Order</h3>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Order Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('status') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('payment_status') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                            <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ old('payment_status', $order->payment_status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                        @error('payment_status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700">Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="3" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('admin_notes') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                        @error('admin_notes') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Update Order</button>
                </form>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <button onclick="window.print()" class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 flex justify-center items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Invoice
                </button>
            </div>
        </div>
    </div>
@endsection
