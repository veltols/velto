@extends('layouts.admin')

@section('header', 'Add Product')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Basic Info -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('name') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id" required class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('category_id') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="base_price" class="block text-sm font-medium text-gray-700">Regular Price (PKR)</label>
                        <input type="number" name="base_price" id="base_price" value="{{ old('base_price') }}" required min="0" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('base_price') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                        @error('base_price') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price (PKR - Optional)</label>
                        <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" min="0" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('sale_price') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                        @error('sale_price') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700">SKU (Optional)</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm">
                        @error('sku') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center mt-6">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">Featured Product</label>
                        
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded ml-6">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Short Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="long_description" class="block text-sm font-medium text-gray-700">Detailed Description</label>
                        <textarea name="long_description" id="long_description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm @error('long_description') border-red-500 @enderror">{{ old('long_description') }}</textarea>
                        @error('long_description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Product Images</h3>
                <input type="file" name="images[]" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs text-gray-500 mt-2">Upload multiple images. First image will be primary.</p>
                @error('images.*') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Variants (Simple JS Implementation) -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Variants (Size & Color)</h3>
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

                    @php
                        $oldVariants = old('variants', [['size' => '', 'color' => '', 'stock' => 10, 'price' => '']]);
                    @endphp

                    @foreach($oldVariants as $index => $variant)
                        <div class="variant-row grid grid-cols-5 gap-4 mb-2">
                            <input type="text" name="variants[{{ $index }}][size]" value="{{ $variant['size'] ?? '' }}" placeholder="Size" class="text-sm border-gray-300 rounded-md">
                            <input type="text" name="variants[{{ $index }}][color]" value="{{ $variant['color'] ?? '' }}" placeholder="Color" class="text-sm border-gray-300 rounded-md">
                            <input type="number" name="variants[{{ $index }}][stock]" value="{{ $variant['stock'] ?? 0 }}" class="text-sm border-gray-300 rounded-md @error('variants.'.$index.'.stock') border-red-500 @enderror">
                            <input type="number" name="variants[{{ $index }}][price]" value="{{ $variant['price'] ?? '' }}" placeholder="Price" class="text-sm border-gray-300 rounded-md @error('variants.'.$index.'.price') border-red-500 @enderror">
                            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 text-sm">Remove</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.products.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-300">Cancel</a>
                <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Save Product</button>
            </div>
        </form>
    </div>

    <script>
        let variantCount = {{ count($oldVariants) }};
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

@endsection
