@extends('layouts.admin')

@section('header', 'Add Review')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb / Back link -->
    <div class="mb-6">
        <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-black">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Reviews
        </a>
    </div>

    <div class="bg-white shadow-xs rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-900">Add Customer Review</h2>
            <p class="mt-1 text-xs text-gray-500">Create a verified review for a product or general customer testimonial.</p>
        </div>

        <form action="{{ route('admin.reviews.store') }}" method="POST" class="p-6 space-y-6" x-data="{ currentRating: {{ old('rating', 5) }} }">
            @csrf

            <!-- Product Selection -->
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-900">Associated Product (Optional)</label>
                <select name="product_id" id="product_id" class="mt-1.5 block w-full rounded-md border-gray-300 text-sm shadow-xs focus:border-black focus:ring-black">
                    <option value="">None (General Store Review)</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rating Stars Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-1.5">Rating Stars <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-3">
                    <div class="flex items-center space-x-1 cursor-pointer">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    @click="currentRating = {{ $i }}" 
                                    class="p-1 hover:scale-110 transition-transform focus:outline-none"
                                    :title="'{{ $i }} Stars'">
                                <svg class="w-8 h-8 text-black transition-colors" 
                                     :fill="currentRating >= {{ $i }} ? 'currentColor' : 'none'" 
                                     stroke="currentColor" 
                                     stroke-width="1.8" 
                                     stroke-linecap="round" 
                                     stroke-linejoin="round" 
                                     viewBox="0 0 24 24">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <span class="text-sm font-bold text-gray-800" x-text="currentRating + ' / 5 Stars'"></span>
                    <input type="hidden" name="rating" :value="currentRating">
                </div>
                @error('rating')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Customer Details Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Customer Name -->
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-900">Customer Name <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required placeholder="e.g. Asad Khan" class="mt-1.5 block w-full rounded-md border-gray-300 text-sm shadow-xs focus:border-black focus:ring-black">
                    @error('customer_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Customer Email -->
                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-900">Customer Email (Optional)</label>
                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" placeholder="e.g. asad@example.com" class="mt-1.5 block w-full rounded-md border-gray-300 text-sm shadow-xs focus:border-black focus:ring-black">
                    @error('customer_email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Review Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-900">Review Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="e.g. Exceptional leather quality and perfect fit" class="mt-1.5 block w-full rounded-md border-gray-300 text-sm shadow-xs focus:border-black focus:ring-black">
                @error('title')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Review Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-900">Review Description <span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="4" required placeholder="Write detailed customer feedback..." class="mt-1.5 block w-full rounded-md border-gray-300 text-sm shadow-xs focus:border-black focus:ring-black">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status & Badges -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 pt-2 border-t border-gray-100">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-900">Publication Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="mt-1.5 block w-full rounded-md border-gray-300 text-sm shadow-xs focus:border-black focus:ring-black">
                        <option value="approved" {{ old('status', 'approved') === 'approved' ? 'selected' : '' }}>Approved (Visible on site)</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending (Needs review)</option>
                        <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected (Hidden)</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Verified Purchase Toggle -->
                <div class="flex items-center pt-6">
                    <label class="relative flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="verified_purchase" value="1" {{ old('verified_purchase', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black">
                        <span class="text-sm font-medium text-gray-900">Verified Purchase</span>
                    </label>
                </div>

                <!-- Featured Toggle -->
                <div class="flex items-center pt-6">
                    <label class="relative flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black">
                        <span class="text-sm font-medium text-gray-900">Feature on Home Page</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.reviews.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="rounded-md bg-black px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 transition">Save Review</button>
            </div>
        </form>
    </div>
</div>
@endsection
