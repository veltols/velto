<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $recentOrders = Order::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('account.index', compact('recentOrders'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'items.variant']);

        return view('account.orders.show', compact('order'));
    }
}
