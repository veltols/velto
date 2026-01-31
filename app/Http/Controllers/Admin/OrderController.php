<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded,partial',
            'admin_notes' => 'nullable|string',
            'advance_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validated['payment_status'] === 'partial' && (empty($request->advance_amount) || $request->advance_amount <= 0)) {
             return back()->withErrors(['advance_amount' => 'Advance amount is required for partial payment status.']);
        }

        if ($request->has('advance_amount') && $request->advance_amount > 0) {
            $validated['payment_status'] = 'partial';
            if ($order->status == 'pending') {
                $validated['status'] = 'processing';
            }
        }

        if (array_key_exists('advance_amount', $validated) && is_null($validated['advance_amount'])) {
            $validated['advance_amount'] = 0;
        }

        $order->update($validated);

        return back()->with('success', 'Order updated successfully.');
    }
}
