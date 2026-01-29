<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CheckoutController extends Controller
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
        $userId = auth()->id();
        $sessionId = $this->getSessionId();

        $cartItems = Cart::with(['product', 'variant'])
            ->where(function ($query) use ($sessionId, $userId) {
                $query->where('session_id', $sessionId);
                if ($userId) {
                     $query->orWhere('user_id', $userId);
                }
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->variant ? $item->variant->final_price : $item->product->price;
            return $price * $item->quantity;
        });
        
        // Simple shipping calculation (flat rate for now or fetch default)
        $shipping = 200; // Default flat rate
        $total = $subtotal + $shipping;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'sometimes|nullable|string|max:20',
        ]);

        // Determine user (Guest or Auth)
        $user = auth()->user();
        
        if (!$user) {
            $email = $validated['email'];
            $user = User::where('email', $email)->first();

            if (!$user) {
                // Auto-register new user
                $password = Str::random(10);
                $user = User::create([
                    'name' => $validated['customer_name'],
                    'email' => $email,
                    'password' => Hash::make($password),
                    'phone' => $validated['phone'],
                    'address' => $validated['shipping_address'],
                    'city' => $validated['city'],
                    'postal_code' => $validated['postal_code'] ?? null,
                ]);
                // TODO: Send email with password
            }
            
            // Log the user in
            auth()->login($user);
        }

        $userId = $user->id;
        $sessionId = $this->getSessionId();

        $cartItems = Cart::with(['product', 'variant'])
            ->where(function ($query) use ($sessionId, $userId) {
                $query->where('session_id', $sessionId);
                // Since we just logged them in or found them, we should check their ID too
                 $query->orWhere('user_id', $userId);
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->variant ? $item->variant->final_price : $item->product->price;
            return $price * $item->quantity;
        });

        // Determine shipping cost based on city/zone if logic existed, using flat rate for now
        $shippingCost = 200; 
        
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'customer_name' => $validated['customer_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'] ?? null,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $subtotal + $shippingCost,
                'status' => 'pending',
                'payment_method' => 'cod',
                'payment_status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $price = $item->variant ? $item->variant->final_price : $item->product->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'variant_info' => $item->variant ? ($item->variant->size . ' / ' . $item->variant->color) : null,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'subtotal' => $price * $item->quantity,
                ]);

                // Reduce Stock
                if ($item->variant) {
                    $item->variant->decrement('stock_quantity', $item->quantity);
                }
            }

            // Clear Cart
            Cart::where('session_id', $sessionId)->orWhere('user_id', $userId)->delete();

            DB::commit();

            // Redirect to success page with order details
             return redirect()->route('checkout.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        // Ensure the authenticated user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items');

        return view('checkout.success', compact('order'));
    }
}
