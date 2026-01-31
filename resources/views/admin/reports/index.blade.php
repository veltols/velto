@extends('layouts.admin')

@section('header', 'Reports')

@section('content')
<div class="space-y-6">
    <!-- Sales Chart / Data -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <h3 class="text-base font-semibold leading-6 text-gray-900">
                    @if($startDate && $endDate)
                        Sales Report ({{ $startDate }} to {{ $endDate }})
                    @else
                        Sales Report (All Time)
                    @endif
                </h3>
                <form action="{{ route('admin.reports.index') }}" method="GET" class="mt-4 sm:mt-0 sm:flex sm:gap-x-4">
                    <div>
                        <label for="start_date" class="sr-only">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
                    </div>
                    <div>
                        <label for="end_date" class="sr-only">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
                    </div>
                    <button type="submit" class="mt-2 w-full sm:mt-0 sm:w-auto rounded-md bg-black px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">Filter</button>
                    @if(request('start_date') || request('end_date'))
                         <a href="{{ route('admin.reports.index') }}" class="mt-2 w-full sm:mt-0 sm:w-auto flex items-center justify-center rounded-md bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-300">Clear</a>
                    @endif
                </form>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div class="overflow-hidden rounded-lg bg-gray-50 px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total Revenue (Booked)</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">Rs. {{ number_format($totalRevenue) }}</dd>
                </div>
                <div class="overflow-hidden rounded-lg bg-gray-50 px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total Orders</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ number_format($totalOrders) }}</dd>
                </div>
                 <div class="overflow-hidden rounded-lg bg-gray-50 px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Fully Paid Revenue</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">Rs. {{ number_format($totalFullyPaidRevenue) }}</dd>
                </div>
                 <div class="overflow-hidden rounded-lg bg-gray-50 px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Advance Revenue</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">Rs. {{ number_format($totalAdvanceRevenue) }}</dd>
                </div>
                 <div class="overflow-hidden rounded-lg bg-gray-50 px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total Cash Flow</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-green-600">Rs. {{ number_format($totalFullyPaidRevenue + $totalAdvanceRevenue) }}</dd>
                </div>
            </div>

            <div class="mt-5">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Orders</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($salesData as $day)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $day->date }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $day->orders }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">Rs. {{ number_format($day->revenue) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No sales data found for the selected period.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($salesData->isNotEmpty())
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th scope="row" class="pl-4 pr-3 py-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Total</th>
                                <td class="px-3 py-3 text-sm font-semibold text-gray-900">{{ number_format($totalOrders) }}</td>
                                <td class="px-3 py-3 text-sm font-semibold text-gray-900">Rs. {{ number_format($totalRevenue) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Top Products -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Top Selling Products</h3>
                <div class="mt-5">
                    <ul role="list" class="divide-y divide-gray-200">
                        @forelse($topProducts as $product)
                        <li class="py-4 flex justify-between">
                            <span class="text-sm font-medium text-gray-900">{{ $product->name }}</span>
                            <span class="text-sm text-gray-500">{{ $product->total_sold }} sold</span>
                        </li>
                        @empty
                        <li class="py-4 text-sm text-gray-500">No sales data available.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Order Statuses -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Order Status Distribution</h3>
                <div class="mt-5">
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($orderStatus as $status)
                        <li class="py-4 flex justify-between">
                            <span class="text-sm font-medium capitalize text-gray-900">{{ $status->status }}</span>
                            <span class="text-sm text-gray-500">{{ $status->total }} orders</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
