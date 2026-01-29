<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%');
            });
        }

        $products = $query->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:base_price',
            'description' => 'required',
            'long_description' => 'nullable',
            'sku' => 'nullable|unique:products,sku',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants' => 'nullable|array',
            'variants.*.size' => 'nullable|string',
            'variants.*.color' => 'nullable|string',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'category_id' => $validated['category_id'],
                'base_price' => $validated['base_price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'description' => $validated['description'],
                'long_description' => $validated['long_description'],
                'sku' => $validated['sku'],
                'is_featured' => $request->has('is_featured'),
                'is_active' => $request->has('is_active'),
            ]);

            // Handle Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'display_order' => $index,
                    ]);
                }
            }

            // Handle Variants
            if ($request->has('variants')) {
                foreach ($validated['variants'] as $variantData) {
                    if ($variantData['size'] || $variantData['color']) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'size' => $variantData['size'] ?? null,
                            'color' => $variantData['color'] ?? null,
                            'stock_quantity' => $variantData['stock'],
                            'price' => $variantData['price'] ?? null,
                            'sku' => ($product->sku ?: 'SKU') . '-' . Str::slug($variantData['size'] ?? 'default') . '-' . Str::slug($variantData['color'] ?? 'default'),
                            'is_available' => true,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product->load(['images', 'variants']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        \Log::info('Product update request received', ['id' => $product->id, 'data' => $request->except(['new_images'])]);
        
        $validated = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:base_price',
            'description' => 'required',
            'long_description' => 'nullable',
            'sku' => 'nullable|unique:products,sku,' . $product->id,
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size' => 'nullable|string',
            'variants.*.color' => 'nullable|string',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Explicitly handle updates
            $product->name = $validated['name'];
            $product->category_id = $validated['category_id'];
            $product->base_price = $validated['base_price'];
            $product->sale_price = $validated['sale_price'] ?? null;
            $product->description = $validated['description'];
            $product->long_description = $validated['long_description'];
            $product->sku = $validated['sku'];
            $product->is_featured = $request->boolean('is_featured');
            $product->is_active = $request->boolean('is_active');

            // Force slug regeneration ONLY if name changed
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($validated['name']);
            }

            $product->save();

            // Handle New Images
            if ($request->hasFile('new_images')) {
                $currentOrder = $product->images()->max('display_order') ?? 0;
                foreach ($request->file('new_images') as $image) {
                    $currentOrder++;
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => false,
                        'display_order' => $currentOrder,
                    ]);
                }
            }

            // Handle Variants Syncing
            $keptVariantIds = [];
            if ($request->has('variants')) {
                foreach ($validated['variants'] as $variantData) {
                    if (isset($variantData['id']) && $variantData['id']) {
                        // Update existing
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant && $variant->product_id == $product->id) {
                            $variant->update([
                                'size' => $variantData['size'] ?? null,
                                'color' => $variantData['color'] ?? null,
                                'stock_quantity' => $variantData['stock'],
                                'price' => $variantData['price'] ?? null,
                            ]);
                            $keptVariantIds[] = $variant->id;
                        }
                    } else {
                        // Create new
                        if ($variantData['size'] || $variantData['color']) {
                            $newVariant = ProductVariant::create([
                                'product_id' => $product->id,
                                'size' => $variantData['size'] ?? null,
                                'color' => $variantData['color'] ?? null,
                                'stock_quantity' => $variantData['stock'],
                                'price' => $variantData['price'] ?? null,
                                'sku' => ($product->sku ?: 'SKU') . '-' . Str::slug($variantData['size'] ?? 'default') . '-' . Str::slug($variantData['color'] ?? 'default'),
                                'is_available' => true,
                            ]);
                            $keptVariantIds[] = $newVariant->id;
                        }
                    }
                }
            }
            // Delete variants not in the list
            $product->variants()->whereNotIn('id', $keptVariantIds)->delete();
            
            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Update Error: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        // Security check
        if ($image->product_id !== $product->id) {
            abort(403);
        }

        // Delete file
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'Image deleted.');
    }

    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        // Security check
        if ($image->product_id !== $product->id) {
            abort(403);
        }

        // Reset all others
        $product->images()->update(['is_primary' => false]);
        
        // Set new primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated.');
    }
}
