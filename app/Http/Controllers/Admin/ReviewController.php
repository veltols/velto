<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('product')->latest();

        // Filter by status
        if ($request->filled('status') && in_array($request->status, ['approved', 'pending', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Filter by rating
        if ($request->filled('rating') && is_numeric($request->rating)) {
            $query->where('rating', (int)$request->rating);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Search in customer name, title, or description
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $reviews = $query->paginate(15)->withQueryString();
        $products = Product::orderBy('name')->get(['id', 'name']);

        // Stats
        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('status', 'approved')->count(),
            'pending' => Review::where('status', 'pending')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
            'average_rating' => round((float)(Review::where('status', 'approved')->avg('rating') ?: 5.0), 1),
        ];

        return view('admin.reviews.index', compact('reviews', 'products', 'stats'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        return view('admin.reviews.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_email'    => 'nullable|email|max:255',
            'rating'            => 'required|integer|min:1|max:5',
            'title'             => 'required|string|max:255',
            'description'       => 'required|string|max:3000',
            'status'            => 'required|in:approved,pending,rejected',
            'product_id'        => 'nullable|exists:products,id',
            'is_featured'       => 'nullable|boolean',
            'verified_purchase' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['verified_purchase'] = $request->boolean('verified_purchase', true);

        Review::create($validated);

        return redirect()->route('admin.reviews.index')->with('success', 'Review added successfully.');
    }

    public function edit(Review $review)
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        return view('admin.reviews.edit', compact('review', 'products'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_email'    => 'nullable|email|max:255',
            'rating'            => 'required|integer|min:1|max:5',
            'title'             => 'required|string|max:255',
            'description'       => 'required|string|max:3000',
            'status'            => 'required|in:approved,pending,rejected',
            'product_id'        => 'nullable|exists:products,id',
            'is_featured'       => 'nullable|boolean',
            'verified_purchase' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['verified_purchase'] = $request->boolean('verified_purchase');

        $review->update($validated);

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }

    public function toggleStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $review->update(['status' => $validated['status']]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $review->status,
                'message' => 'Review status changed to ' . ucfirst($review->status),
            ]);
        }

        return back()->with('success', 'Review status updated to ' . ucfirst($review->status));
    }
}
