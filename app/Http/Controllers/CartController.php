<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getSessionId()
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', Str::uuid()->toString());
        }
        return Session::get('cart_session_id');
    }

    public function index()
    {
        $sessionId = $this->getSessionId();
        $userId = auth()->id();

        $cartItems = Cart::with(['product', 'product.primaryImage', 'variant'])
            ->where(function ($query) use ($sessionId, $userId) {
                $query->where('session_id', $sessionId);
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            })
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->variant ? $item->variant->final_price : $item->product->price;
            return $price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $sessionId = $this->getSessionId();
        $userId = auth()->id();

        // Check availability
        $cartItem = Cart::where('product_id', $request->product_id)
            ->where('product_variant_id', $request->variant_id)
            ->where(function ($query) use ($sessionId, $userId) {
                $query->where('session_id', $sessionId);
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            })
            ->first();

        $newQuantity = $request->quantity;
        if ($cartItem) {
            $newQuantity += $cartItem->quantity;
        }

        // Check availability with total quantity
        if ($request->variant_id) {
            $variant = ProductVariant::find($request->variant_id);
            if ($variant->stock_quantity < $newQuantity) {
                return response()->json(['success' => false, 'message' => 'Not enough stock. You already have ' . ($cartItem ? $cartItem->quantity : 0) . ' in cart.'], 422);
            }
        } else {
             // Optional: check simple product stock if needed
             $product = Product::find($request->product_id);
             if ($product->stock_quantity < $newQuantity) {
                 return response()->json(['success' => false, 'message' => 'Not enough stock available.'], 422);
             }
        }

        if ($cartItem) {
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            Cart::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        // Recalculate totals for response
        $currentCart = Cart::where('session_id', $sessionId)
            ->when($userId, function($q) use ($userId) {
                $q->orWhere('user_id', $userId);
            })->get();
            
        $count = $currentCart->sum('quantity');
        $total = $currentCart->sum(function($item) {
             $price = $item->variant ? $item->variant->final_price : $item->product->price;
             return $price * $item->quantity;
        });

        return response()->json([
            'success' => true, 
            'message' => 'Item added to cart!',
            'cart_count' => $count,
            'cart_total' => 'PKR ' . number_format($total)
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::findOrFail($id);
        
        // Simple permission check
        if ($cartItem->session_id !== $this->getSessionId() && $cartItem->user_id !== auth()->id()) {
            return response()->json(['success' => false], 403);
        }

        // Check stock
        if ($cartItem->product_variant_id) {
            $variant = ProductVariant::find($cartItem->product_variant_id);
            if ($variant->stock_quantity < $request->quantity) {
                 return response()->json(['success' => false, 'message' => 'Stock limit reached.']);
            }
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Recalculate totals
        $sessionId = $this->getSessionId();
        $userId = auth()->id();

        $currentCart = Cart::where('session_id', $sessionId)
            ->when($userId, function($q) use ($userId) {
                $q->orWhere('user_id', $userId);
            })->get();
            
        $count = $currentCart->sum('quantity');
        $total = $currentCart->sum(function($item) {
             $price = $item->variant ? $item->variant->final_price : $item->product->price;
             return $price * $item->quantity;
        });

        // Item subtotal
        $itemSubtotal = ($cartItem->variant ? $cartItem->variant->final_price : $cartItem->product->price) * $cartItem->quantity;

        return response()->json([
            'success' => true,
            'cart_count' => $count,
            'cart_total' => 'PKR ' . number_format($total),
            'item_subtotal' => 'PKR ' . number_format($itemSubtotal)
        ]);
    }

    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);
        
        if ($cartItem->session_id !== $this->getSessionId() && $cartItem->user_id !== auth()->id()) {
             abort(403);
        }

        $cartItem->delete();

        if (request()->wantsJson()) {
            $sessionId = $this->getSessionId();
            $userId = auth()->id();
            
            $currentCart = Cart::where('session_id', $sessionId)
            ->when($userId, function($q) use ($userId) {
                $q->orWhere('user_id', $userId);
            })->get();

            $count = $currentCart->sum('quantity');
            $total = $currentCart->sum(function($item) {
                 $price = $item->variant ? $item->variant->final_price : $item->product->price;
                 return $price * $item->quantity;
            });

            return response()->json([
                'success' => true,
                'message' => 'Item removed',
                'cart_count' => $count,
                'cart_total' => 'PKR ' . number_format($total)
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $sessionId = $this->getSessionId();
        $userId = auth()->id();

        Cart::where('session_id', $sessionId)
            ->when($userId, function($q) use ($userId) {
                $q->orWhere('user_id', $userId);
            })->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared',
                'cart_count' => 0,
                'cart_total' => 'PKR 0'
            ]);
        }

        return back()->with('success', 'Cart cleared.');
    }
}
