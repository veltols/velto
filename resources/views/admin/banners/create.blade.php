@extends('layouts.admin')

@section('header', 'Add Banner')

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div>
            <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
            <div class="mt-2">
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
            </div>
        </div>

        <div>
            <label for="text" class="block text-sm font-medium leading-6 text-gray-900">Description (Optional)</label>
            <div class="mt-2">
                <textarea name="text" id="text" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">{{ old('text') }}</textarea>
            </div>
        </div>

        <div>
            <label for="button_text" class="block text-sm font-medium leading-6 text-gray-900">Button Text</label>
            <div class="mt-2">
                <input type="text" name="button_text" id="button_text" value="{{ old('button_text', 'Shop Now') }}" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
            </div>
        </div>

        <div>
            <label for="button_link" class="block text-sm font-medium leading-6 text-gray-900">Button Link</label>
            <div class="mt-2">
                <input type="text" name="button_link" id="button_link" value="{{ old('button_link', route('shop.index', ['on_sale' => 1])) }}" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
                <p class="mt-1 text-xs text-gray-500">Default links to "On Sale" products.</p>
            </div>
        </div>

        <div>
            <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Banner Image (Optional)</label>
            <div class="mt-2">
                <input type="file" name="image" id="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
            </div>
        </div>

        <div class="relative flex gap-x-3">
            <div class="flex h-6 items-center">
                <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black">
            </div>
            <div class="text-sm leading-6">
                <label for="is_active" class="font-medium text-gray-900">Active</label>
                <p class="text-gray-500">Show this banner on the homepage?</p>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.banners.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-300">Cancel</a>
            <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Save Banner</button>
        </div>
    </form>
</div>
@endsection
