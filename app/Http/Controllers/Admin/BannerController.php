<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('is_slider', 'desc')->orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'text'        => 'nullable|string',
            'button_text' => 'required|string|max:50',
            'button_link' => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:8192',
            'is_active'   => 'boolean',
            'is_slider'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_slider'] = $request->boolean('is_slider');
        $validated['sort_order'] = $request->input('sort_order', 0);

        // If NOT a slider banner, only one mid-page banner can be active at a time
        if ($validated['is_active'] && !$validated['is_slider']) {
            Banner::where('is_active', true)->where('is_slider', false)->update(['is_active' => false]);
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'text'        => 'nullable|string',
            'button_text' => 'required|string|max:50',
            'button_link' => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:8192',
            'is_active'   => 'boolean',
            'is_slider'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_slider'] = $request->boolean('is_slider');
        $validated['sort_order'] = $request->input('sort_order', 0);

        // If NOT a slider banner, only one mid-page banner can be active at a time
        if ($validated['is_active'] && !$validated['is_slider']) {
            Banner::where('id', '!=', $banner->id)->where('is_active', true)->where('is_slider', false)->update(['is_active' => false]);
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
