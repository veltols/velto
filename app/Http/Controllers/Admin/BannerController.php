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
        $banners = Banner::latest()->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'nullable|string',
            'button_text' => 'required|string|max:50',
            'button_link' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // 2MB Max
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }

        if ($request->has('is_active') && $request->is_active) {
            // Deactivate other banners if this one is active
            Banner::where('is_active', true)->update(['is_active' => false]);
            $validated['is_active'] = true;
        } else {
             $validated['is_active'] = false;
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
            'title' => 'required|string|max:255',
            'text' => 'nullable|string',
            'button_text' => 'required|string|max:50',
            'button_link' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }

        if ($request->has('is_active') && $request->is_active) {
            Banner::where('id', '!=', $banner->id)->where('is_active', true)->update(['is_active' => false]);
            $validated['is_active'] = true;
        } else {
             $validated['is_active'] = false;
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
