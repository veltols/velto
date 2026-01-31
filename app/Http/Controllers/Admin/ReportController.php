<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Determine Dates
        // If query parameters are missing entirely (initial visit), default to 30 days.
        // If present but empty (user cleared them), allow null for 'All Time'.
        if (!$request->has('start_date') && !$request->has('end_date')) {
            $startDate = Carbon::now()->subDays(30)->toDateString();
            $endDate = Carbon::now()->toDateString();
        } else {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
        }

        // Sales Data
        $salesQuery = Order::where('status', '!=', 'cancelled');
        if ($startDate) $salesQuery->whereDate('created_at', '>=', $startDate);
        if ($endDate) $salesQuery->whereDate('created_at', '<=', $endDate);
        
        $salesData = $salesQuery->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Selling Products
        $topProductsQuery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', '!=', 'cancelled');

        if ($startDate) $topProductsQuery->whereDate('orders.created_at', '>=', $startDate);
        if ($endDate) $topProductsQuery->whereDate('orders.created_at', '<=', $endDate);

        $topProducts = $topProductsQuery->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Order Status Distribution
        $statusQuery = Order::query();
        if ($startDate) $statusQuery->whereDate('created_at', '>=', $startDate);
        if ($endDate) $statusQuery->whereDate('created_at', '<=', $endDate);

        $orderStatus = $statusQuery->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $totalRevenue = $salesData->sum('revenue');
        $totalOrders = $salesData->sum('orders');

        // New Financial Metrics
        $financialQuery = Order::query()->where('status', '!=', 'cancelled');
        if ($startDate) $financialQuery->whereDate('created_at', '>=', $startDate);
        if ($endDate) $financialQuery->whereDate('created_at', '<=', $endDate);

        $totalFullyPaidRevenue = (clone $financialQuery)->where('payment_status', 'paid')->sum('total');
        $totalAdvanceRevenue = (clone $financialQuery)->sum('advance_amount');

        return view('admin.reports.index', compact('salesData', 'topProducts', 'orderStatus', 'startDate', 'endDate', 'totalRevenue', 'totalOrders', 'totalFullyPaidRevenue', 'totalAdvanceRevenue'));
    }
}
