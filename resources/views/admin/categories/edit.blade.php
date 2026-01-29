@extends('layouts.admin')

@section('header', 'Edit Category')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('name') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Category</label>
                    <select name="parent_id" id="parent_id" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('parent_id') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                        <option value="">None (Top Level)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('description') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Category Image</label>
                    @if($category->image)
                        <div class="mb-2">
                             @php
                                $url = Str::startsWith($category->image, 'http') ? $category->image : asset('storage/' . $category->image);
                            @endphp
                            <img src="{{ $url }}" alt="Current Image" class="h-20 w-20 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 @error('image') border border-red-500 rounded-md @enderror">
                    @error('image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="display_order" class="block text-sm font-medium text-gray-700">Display Order</label>
                        <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $category->display_order) }}" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('display_order') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                        @error('display_order') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="is_active" id="is_active" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm @error('is_active') border-red-500 @else border-gray-300 focus:border-black focus:ring-black @enderror">
                            <option value="1" {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-300">Cancel</a>
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Update Category</button>
                </div>
            </form>
        </div>
    </div>
@endsection
