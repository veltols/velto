@extends('layouts.admin')

@section('header', 'Edit Shipping Rate')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Breadcrumb & Back -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('admin.shipping-rates.index') }}" class="hover:text-black">Shipping Rates</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Edit #{{ $shippingRate->id }}</span>
            </div>
            <a href="{{ route('admin.shipping-rates.index') }}" class="text-sm font-medium text-gray-600 hover:text-black flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Rates
            </a>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Edit Shipping Rate</h3>
                    <p class="text-sm text-gray-500 mt-1">Update rates, city restrictions, or free delivery conditions.</p>
                </div>
                @if($shippingRate->is_default)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                        ★ Current Default Rate
                    </span>
                @endif
            </div>

            <form action="{{ route('admin.shipping-rates.update', $shippingRate) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700">
                        Shipping Rate Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $shippingRate->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm @error('name') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">This name will be displayed to customers during checkout.</p>
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Rate & Min Order Amount -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="rate" class="block text-sm font-semibold text-gray-700">
                            Shipping Fee (PKR) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="rate" id="rate" value="{{ old('rate', $shippingRate->rate) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm @error('rate') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Set to 0 for free delivery.</p>
                        @error('rate') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="min_order_amount" class="block text-sm font-semibold text-gray-700">
                            Free Shipping Threshold (PKR - Optional)
                        </label>
                        <input type="number" step="0.01" min="0" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount', $shippingRate->min_order_amount) }}" placeholder="e.g. 3000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm @error('min_order_amount') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Optional. Orders equal to or above this amount get free shipping.</p>
                        @error('min_order_amount') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Covered Cities -->
                <div>
                    <label for="cities" class="block text-sm font-semibold text-gray-700">
                        Covered Cities / Zones
                    </label>
                    <textarea name="cities" id="cities" rows="2" placeholder="e.g., Karachi, Lahore, Islamabad (Leave empty for All Pakistan / Nationwide)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm @error('cities') border-red-500 @enderror">{{ old('cities', $shippingRate->cities) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Comma-separated list of cities. Leave empty to apply nationwide to all locations.</p>
                    @error('cities') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Delivery Notes / Time -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700">
                        Delivery Notes / Estimated Delivery Time
                    </label>
                    <input type="text" name="notes" id="notes" value="{{ old('notes', $shippingRate->notes) }}" placeholder="e.g., Delivered within 2-4 business days" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm @error('notes') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Optional helpful description displayed to the customer or store manager.</p>
                    @error('notes') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status & Default Switches -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-gray-100">
                    <div>
                        <label for="is_active" class="block text-sm font-semibold text-gray-700">Status</label>
                        <select name="is_active" id="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                            <option value="1" {{ old('is_active', $shippingRate->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Active (Available at Checkout)</option>
                            <option value="0" {{ old('is_active', $shippingRate->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Inactive (Disabled)</option>
                        </select>
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="relative flex items-start cursor-pointer">
                            <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default', $shippingRate->is_default) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black mt-1">
                            <div class="ml-3 text-sm">
                                <span class="font-semibold text-gray-800">Set as Default Rate</span>
                                <p class="text-xs text-gray-500">Use as standard shipping when no city-specific rate matches.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <button type="button" onclick="if(confirm('Are you sure you want to delete this shipping rate?')) { document.getElementById('delete-form').submit(); }" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        Delete Rate
                    </button>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.shipping-rates.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2 bg-black hover:bg-gray-800 text-white rounded-md text-sm font-semibold shadow-sm transition">
                            Update Shipping Rate
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-form" action="{{ route('admin.shipping-rates.destroy', $shippingRate) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection
