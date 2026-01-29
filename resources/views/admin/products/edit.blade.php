@extends('layouts.admin')

@section('header', 'Edit Product')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')
            
            <!-- Basic Info -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('name') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="base_price" class="block text-sm font-medium text-gray-700">Regular Price (PKR)</label>
                        <input type="number" name="base_price" id="base_price" value="{{ old('base_price', $product->base_price) }}" required min="0" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('base_price') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                        @error('base_price')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price (PKR - Optional)</label>
                        <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('sale_price') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                        @error('sale_price')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                    </div>

                    <div class="flex items-center mt-6">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">Featured Product</label>
                        
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded ml-6">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Short Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="col-span-2">
                        <label for="long_description" class="block text-sm font-medium text-gray-700">Detailed Description</label>
                        <textarea name="long_description" id="long_description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">{{ old('long_description', $product->long_description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Images</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    @foreach($product->images as $image)
                        <div class="relative group group-hover:shadow-md transition rounded overflow-hidden border border-gray-200">
                            <img src="{{ $image->url }}" class="h-32 w-full object-cover">
                            
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center space-x-2">
                                @if(!$image->is_primary)
                                    <button type="button" 
                                            onclick="event.preventDefault(); submitImageAction('{{ route('admin.products.images.primary', [$product, $image]) }}', 'primary')"
                                            class="p-2 bg-white rounded-full hover:bg-gray-100 text-green-600" title="Make Primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                @endif
                                
                                <button type="button" 
                                        onclick="event.preventDefault(); if(confirm('Delete this image?')) submitImageAction('{{ route('admin.products.images.destroy', [$product, $image]) }}', 'destroy')"
                                        class="p-2 bg-white rounded-full hover:bg-gray-100 text-red-600" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                            @if($image->is_primary)
                                <span class="absolute top-2 right-2 bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm uppercase tracking-wide">Primary</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <label class="block text-sm font-medium text-gray-700 mt-4">Add New Images</label>
                <input type="file" name="new_images[]" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            </div>

            <!-- Variants Management -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Manage Variants</h3>
                    <button type="button" onclick="addVariant()" class="text-sm bg-gray-200 px-3 py-1 rounded hover:bg-gray-300">+ Add Variant</button>
                </div>
                
                <div id="variants-container">
                    <div class="grid grid-cols-5 gap-4 mb-2">
                        <label class="text-xs font-semibold text-gray-500">Size</label>
                        <label class="text-xs font-semibold text-gray-500">Color</label>
                        <label class="text-xs font-semibold text-gray-500">Stock</label>
                        <label class="text-xs font-semibold text-gray-500">Price (Opt)</label>
                        <label class="text-xs font-semibold text-gray-500">Action</label>
                    </div>

                    @foreach($product->variants as $index => $variant)
                        <div class="variant-row grid grid-cols-5 gap-4 mb-2">
                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                            <input type="text" name="variants[{{ $index }}][size]" value="{{ old('variants.'.$index.'.size', $variant->size) }}" placeholder="Size" class="text-sm border-gray-300 rounded-md">
                            <input type="text" name="variants[{{ $index }}][color]" value="{{ old('variants.'.$index.'.color', $variant->color) }}" placeholder="Color" class="text-sm border-gray-300 rounded-md">
                            <input type="number" name="variants[{{ $index }}][stock]" value="{{ old('variants.'.$index.'.stock', $variant->stock_quantity) }}" class="text-sm border-gray-300 rounded-md @error('variants.'.$index.'.stock') border-red-500 @enderror">
                            <input type="number" name="variants[{{ $index }}][price]" value="{{ old('variants.'.$index.'.price', $variant->price) }}" placeholder="Price" class="text-sm border-gray-300 rounded-md">
                            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 text-sm">Remove</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <script>
                let variantCount = {{ $product->variants->count() }};
                function addVariant() {
                    const container = document.getElementById('variants-container');
                    const newRow = document.createElement('div');
                    newRow.className = 'variant-row grid grid-cols-5 gap-4 mb-2';
                    newRow.innerHTML = `
                        <input type="text" name="variants[${variantCount}][size]" placeholder="Size" class="text-sm border-gray-300 rounded-md">
                        <input type="text" name="variants[${variantCount}][color]" placeholder="Color" class="text-sm border-gray-300 rounded-md">
                        <input type="number" name="variants[${variantCount}][stock]" value="10" class="text-sm border-gray-300 rounded-md">
                        <input type="number" name="variants[${variantCount}][price]" placeholder="Price" class="text-sm border-gray-300 rounded-md">
                        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 text-sm">Remove</button>
                    `;
                    container.appendChild(newRow);
                    variantCount++;
                }
            </script>

            <div class="flex justify-end">
                <a href="{{ route('admin.products.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-300">Cancel</a>
                <button type="submit" onclick="console.log('Update button clicked');" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Update Product</button>
            </div>
        </form>

        {{-- Hidden Forms for Image Actions --}}
        <form id="primary-image-form" method="POST" class="hidden">
            @csrf
        </form>
        <form id="destroy-image-form" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <script>
            function submitImageAction(url, action) {
                const formId = action === 'primary' ? 'primary-image-form' : 'destroy-image-form';
                const form = document.getElementById(formId);
                form.action = url;
                form.submit();
            }
        </script>
    </div>
@endsection
