@extends('layouts.admin')

@section('header', 'Overview')

@section('content')
    <div class="grid grid-cols-1 gap-px bg-gray-900/5 border border-gray-200 sm:grid-cols-2 lg:grid-cols-4 rounded-xl overflow-hidden shadow-sm">
        <div class="bg-white p-6 sm:p-8">
            <p class="text-sm font-medium leading-6 text-gray-500">Total Revenue</p>
            <p class="mt-2 flex items-baseline gap-x-2">
                <span class="text-3xl font-bold tracking-tight text-gray-900">Rs. {{ number_format($stats['total_revenue']) }}</span>
            </p>
        </div>
        <div class="bg-white p-6 sm:p-8">
            <p class="text-sm font-medium leading-6 text-gray-500">Total Orders</p>
            <p class="mt-2 flex items-baseline gap-x-2">
                <span class="text-3xl font-bold tracking-tight text-gray-900">{{ $stats['total_orders'] }}</span>
            </p>
        </div>
        
        <!-- Order Statuses -->
        <div class="bg-white p-6 sm:p-8">
            <p class="text-sm font-medium leading-6 text-gray-500">Pending</p>
            <p class="mt-2 flex items-baseline gap-x-2">
                <span class="text-3xl font-bold tracking-tight text-yellow-600">{{ $stats['pending_orders'] }}</span>
            </p>
        </div>
        <div class="bg-white p-6 sm:p-8">
            <p class="text-sm font-medium leading-6 text-gray-500">Processing</p>
            <p class="mt-2 flex items-baseline gap-x-2">
                <span class="text-3xl font-bold tracking-tight text-blue-600">{{ $stats['processing_orders'] }}</span>
            </p>
        </div>
        <div class="bg-white p-6 sm:p-8">
            <p class="text-sm font-medium leading-6 text-gray-500">Shipped</p>
            <p class="mt-2 flex items-baseline gap-x-2">
                <span class="text-3xl font-bold tracking-tight text-purple-600">{{ $stats['shipped_orders'] }}</span>
            </p>
        </div>
        <div class="bg-white p-6 sm:p-8">
            <p class="text-sm font-medium leading-6 text-gray-500">Delivered</p>
            <p class="mt-2 flex items-baseline gap-x-2">
                <span class="text-3xl font-bold tracking-tight text-green-600">{{ $stats['delivered_orders'] }}</span>
            </p>
        </div>
        <div class="bg-white p-6 sm:p-8 lg:col-span-2">
            <p class="text-sm font-medium leading-6 text-gray-500">Cancelled</p>
            <p class="mt-2 flex items-baseline gap-x-2">
                <span class="text-3xl font-bold tracking-tight text-red-600">{{ $stats['cancelled_orders'] }}</span>
            </p>
        </div>
    </div>

    <div class="mt-10">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-base font-semibold leading-6 text-gray-900">Recent Orders</h2>
                <p class="mt-2 text-sm text-gray-700">A list of the most recent orders including customer info and status.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.orders.index') }}" class="block rounded-md bg-black px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">View all orders</a>
            </div>
        </div>
        
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Order ID</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($recent_orders as $order)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">#{{ $order->order_number }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->customer_name }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                             @php
                                                $statusClasses = [
                                                    'pending' => 'text-yellow-700 bg-yellow-50 ring-yellow-600/20',
                                                    'processing' => 'text-blue-700 bg-blue-50 ring-blue-600/20',
                                                    'shipped' => 'text-purple-700 bg-purple-50 ring-purple-600/20',
                                                    'delivered' => 'text-green-700 bg-green-50 ring-green-600/20',
                                                    'cancelled' => 'text-red-700 bg-red-50 ring-red-600/20',
                                                ];
                                                $class = $statusClasses[$order->status] ?? 'text-gray-600 bg-gray-50 ring-gray-500/10';
                                            @endphp
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $class }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">Rs. {{ number_format($order->total) }}</td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-black hover:text-gray-700">View<span class="sr-only">, {{ $order->order_number }}</span></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No recent orders.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
