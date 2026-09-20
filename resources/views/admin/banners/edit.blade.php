@extends('layouts.admin')

@section('header', 'Edit Banner')

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
            <div class="mt-2">
                <input type="text" name="title" id="title" value="{{ old('title', $banner->title) }}" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
            </div>
        </div>

        <div>
            <label for="text" class="block text-sm font-medium leading-6 text-gray-900">Subtitle / Description (Optional)</label>
            <div class="mt-2">
                <input type="text" name="text" id="text" value="{{ old('text', $banner->text) }}" placeholder="e.g. Exquisite Craftsmanship" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
            </div>
        </div>

        <div>
            <label for="button_text" class="block text-sm font-medium leading-6 text-gray-900">Button Text</label>
            <div class="mt-2">
                <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $banner->button_text) }}" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
            </div>
        </div>

        <div>
            <label for="button_link" class="block text-sm font-medium leading-6 text-gray-900">Button Link</label>
            <div class="mt-2">
                <input type="text" name="button_link" id="button_link" value="{{ old('button_link', $banner->button_link) }}" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
            </div>
        </div>

        <div>
            <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Banner Image</label>
            <p class="text-xs text-gray-500 mb-1">For hero slider: recommended size 1920x800px (landscape). Max 4MB.</p>
            <div class="mt-2">
                @if($banner->image_path)
                    <div class="mb-3">
                        <img src="{{ Storage::url($banner->image_path) }}" alt="Current banner" class="h-32 w-auto rounded shadow-sm object-cover">
                        <p class="text-xs text-gray-400 mt-1">Current image. Upload a new one to replace it.</p>
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
            </div>
        </div>

        <div>
            <label for="sort_order" class="block text-sm font-medium leading-6 text-gray-900">Sort Order (for Hero Slider)</label>
            <p class="text-xs text-gray-500 mb-1">Lower numbers appear first. E.g. 0, 1, 2...</p>
            <div class="mt-2">
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="0" class="block w-32 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-3">
            <p class="text-sm font-semibold text-blue-800">Banner Type</p>

            <div class="relative flex gap-x-3">
                <div class="flex h-6 items-center">
                    <input id="is_slider" name="is_slider" type="checkbox" value="1" {{ old('is_slider', $banner->is_slider) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </div>
                <div class="text-sm leading-6">
                    <label for="is_slider" class="font-medium text-gray-900">Hero Slider Banner</label>
                    <p class="text-gray-500">Check this to show as a slide in the main hero slider at the top of homepage. You can have multiple slider banners active at once.</p>
                </div>
            </div>

            <div class="relative flex gap-x-3">
                <div class="flex h-6 items-center">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black">
                </div>
                <div class="text-sm leading-6">
                    <label for="is_active" class="font-medium text-gray-900">Active</label>
                    <p class="text-gray-500">Show this banner on the homepage. If not a slider banner, only one mid-page banner can be active.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.banners.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-300">Cancel</a>
            <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Update Banner</button>
        </div>
    </form>
</div>
@endsection
