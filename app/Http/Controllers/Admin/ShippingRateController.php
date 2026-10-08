<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    /**
     * Display a listing of shipping rates.
     */
    public function index(Request $request)
    {
        $query = ShippingRate::query()->latest('id');

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search in name, cities, notes
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('cities', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $shippingRates = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => ShippingRate::count(),
            'active' => ShippingRate::where('is_active', true)->count(),
            'free_shipping' => ShippingRate::where('rate', 0)->orWhereNotNull('min_order_amount')->count(),
            'default' => ShippingRate::where('is_default', true)->first(),
        ];

        return view('admin.shipping_rates.index', compact('shippingRates', 'stats'));
    }

    /**
     * Show the form for creating a new shipping rate.
     */
    public function create()
    {
        return view('admin.shipping_rates.create');
    }

    /**
     * Store a newly created shipping rate in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'rate'             => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'cities'           => 'nullable|string|max:2000',
            'is_active'        => 'nullable|boolean',
            'is_default'       => 'nullable|boolean',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $isDefault = $request->boolean('is_default', false);

        // If this is set as default, reset others
        if ($isDefault) {
            ShippingRate::query()->update(['is_default' => false]);
            $validated['is_default'] = true;
            $validated['is_active'] = true;
        } else {
            // If it's the very first shipping rate, make it default automatically
            if (ShippingRate::count() === 0) {
                $validated['is_default'] = true;
            } else {
                $validated['is_default'] = false;
            }
        }

        ShippingRate::create($validated);

        return redirect()->route('admin.shipping-rates.index')->with('success', 'Shipping rate created successfully.');
    }

    /**
     * Show the form for editing the specified shipping rate.
     */
    public function edit(ShippingRate $shippingRate)
    {
        return view('admin.shipping_rates.edit', compact('shippingRate'));
    }

    /**
     * Update the specified shipping rate in storage.
     */
    public function update(Request $request, ShippingRate $shippingRate)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'rate'             => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'cities'           => 'nullable|string|max:2000',
            'is_active'        => 'nullable|boolean',
            'is_default'       => 'nullable|boolean',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $isDefault = $request->boolean('is_default', false);

        if ($isDefault) {
            ShippingRate::where('id', '!=', $shippingRate->id)->update(['is_default' => false]);
            $validated['is_default'] = true;
            $validated['is_active'] = true; // Default must be active
        } else {
            $validated['is_default'] = false;
        }

        $shippingRate->update($validated);

        return redirect()->route('admin.shipping-rates.index')->with('success', 'Shipping rate updated successfully.');
    }

    /**
     * Remove the specified shipping rate from storage.
     */
    public function destroy(ShippingRate $shippingRate)
    {
        $wasDefault = $shippingRate->is_default;
        $shippingRate->delete();

        // If the deleted rate was default, assign default to another active rate if available
        if ($wasDefault) {
            $nextRate = ShippingRate::where('is_active', true)->first();
            if ($nextRate) {
                $nextRate->update(['is_default' => true]);
            }
        }

        return redirect()->route('admin.shipping-rates.index')->with('success', 'Shipping rate deleted successfully.');
    }

    /**
     * Toggle the active status of the specified shipping rate.
     */
    public function toggleStatus(Request $request, ShippingRate $shippingRate)
    {
        $newStatus = !$shippingRate->is_active;

        // If deactivating a default rate, check if there are others
        if (!$newStatus && $shippingRate->is_default) {
            $anotherActive = ShippingRate::where('id', '!=', $shippingRate->id)->where('is_active', true)->first();
            if ($anotherActive) {
                $anotherActive->update(['is_default' => true]);
                $shippingRate->update(['is_default' => false, 'is_active' => false]);
            } else {
                $shippingRate->update(['is_active' => false, 'is_default' => false]);
            }
        } else {
            $shippingRate->update(['is_active' => $newStatus]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $shippingRate->is_active,
                'message' => 'Status changed successfully.',
            ]);
        }

        return back()->with('success', 'Status updated successfully.');
    }

    /**
     * Set the specified shipping rate as the default.
     */
    public function setDefault(ShippingRate $shippingRate)
    {
        ShippingRate::query()->update(['is_default' => false]);
        $shippingRate->update([
            'is_default' => true,
            'is_active' => true,
        ]);

        return back()->with('success', "'{$shippingRate->name}' is now set as the default shipping rate.");
    }
}
