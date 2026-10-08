@extends('layouts.admin')

@section('header', 'Shipping Rates')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Shipping Rates & Zones</h2>
                <p class="text-sm text-gray-500 mt-1">Manage delivery charges, regional rates, and free shipping rules for customer orders.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.shipping-rates.create') }}" class="inline-flex items-center gap-x-2 rounded-md bg-black px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Shipping Rate
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Total Rates</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                </div>
            </div>

            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Active Rates</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Default Rate</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">
                        @if($stats['default'])
                            {{ $stats['default']->formatted_rate }}
                            <span class="text-xs font-normal text-gray-500 block truncate max-w-[140px]">{{ $stats['default']->name }}</span>
                        @else
                            <span class="text-sm font-medium text-amber-600">None Set</span>
                        @endif
                    </p>
                </div>
                <div class="h-10 w-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Free Shipping Rules</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $stats['free_shipping'] }}</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H4.5a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m0-13.5H3.75m8.25 0h8.25" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.shipping-rates.index') }}" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                <div class="flex-1 w-full sm:w-auto flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, city, or note..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:ring-black focus:border-black">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="w-full sm:w-48">
                        <select name="status" onchange="this.form.submit()" class="w-full py-2 border border-gray-300 rounded-md text-sm focus:ring-black focus:border-black">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.shipping-rates.index') }}" class="text-xs text-gray-500 hover:text-black underline px-2">
                            Clear filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Method Name</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rate / Fee</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Free Shipping Rule</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Coverage</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($shippingRates as $rate)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <!-- Name & Notes -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900 text-sm">{{ $rate->name }}</span>
                                        @if($rate->is_default)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                <svg class="h-3 w-3 fill-amber-500" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                    @if($rate->notes)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $rate->notes }}</p>
                                    @endif
                                </td>

                                <!-- Cost / Rate -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if((float)$rate->rate == 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                            FREE
                                        </span>
                                    @else
                                        <span class="text-sm font-bold text-gray-900">Rs. {{ number_format($rate->rate, 2) }}</span>
                                    @endif
                                </td>

                                <!-- Free Shipping Threshold -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if($rate->min_order_amount !== null && (float)$rate->min_order_amount > 0)
                                        <div class="flex items-center gap-1.5">
                                            <span class="inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
                                            <span>Free on orders &ge; <strong class="text-gray-900">Rs. {{ number_format($rate->min_order_amount) }}</strong></span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs italic">No free threshold</span>
                                    @endif
                                </td>

                                <!-- Cities / Coverage -->
                                <td class="px-6 py-4">
                                    @if(!empty($rate->cities))
                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                            @foreach($rate->cities_array as $city)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                    {{ $city }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            All Pakistan (Nationwide)
                                        </span>
                                    @endif
                                </td>

                                <!-- Status Toggle -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.shipping-rates.toggle-status', $rate) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="Click to toggle status" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition {{ $rate->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $rate->is_active ? 'bg-emerald-600' : 'bg-gray-400' }}"></span>
                                            {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(!$rate->is_default && $rate->is_active)
                                            <form action="{{ route('admin.shipping-rates.set-default', $rate) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 px-2 py-1 rounded border border-amber-200 font-medium transition" title="Make this the default rate">
                                                    Set Default
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.shipping-rates.edit', $rate) }}" class="text-gray-700 hover:text-black bg-gray-100 hover:bg-gray-200 px-2.5 py-1 rounded font-medium text-xs transition">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.shipping-rates.destroy', $rate) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this shipping rate?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded font-medium text-xs transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-3">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                            </svg>
                                        </div>
                                        <p class="text-base font-semibold text-gray-900">No shipping rates found</p>
                                        <p class="text-sm text-gray-500 mt-1 max-w-sm">Get started by creating your first shipping rate for customer deliveries.</p>
                                        <a href="{{ route('admin.shipping-rates.create') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-md bg-black px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-800">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                            </svg>
                                            Add First Rate
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($shippingRates->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $shippingRates->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
