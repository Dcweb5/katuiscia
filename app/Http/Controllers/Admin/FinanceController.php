<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'month');

        $months = match ($period) {
            'quarter' => 3,
            'year' => 12,
            default => 1,
        };

        $since = now()->subMonths($months);

        $completed = Order::whereIn('status', ['confirmed', 'preparing', 'shipped', 'delivered'])
            ->where('created_at', '>=', $since);

        $allOrders = clone $completed;

        // Stats
        $totalRevenue = $completed->sum('total');
        $margin = round($totalRevenue * 0.6, 2); // 60% margin estimate
        $avgBasket = round($allOrders->count() > 0 ? $completed->avg('total') : 0, 2);
        $totalOrders = $allOrders->count();

        // Refunds (cancelled)
        $refunds = Order::where('status', 'cancelled')->where('created_at', '>=', $since)->sum('total');

        // Monthly evolution
        $monthly = Order::whereIn('status', ['confirmed', 'preparing', 'shipped', 'delivered'])
            ->where('created_at', '>=', now()->subYear())
            ->selectRaw("strftime('%Y-%m', created_at) as m, sum(total) as total, count(*) as orders")
            ->groupBy('m')->orderBy('m')->get();

        // Top selling products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['confirmed', 'preparing', 'shipped', 'delivered'])
            ->where('orders.created_at', '>=', $since)
            ->select('order_items.product_name', DB::raw('sum(order_items.quantity) as qty'), DB::raw('sum(order_items.price * order_items.quantity) as revenue'))
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('revenue')->take(5)->get();

        // Status distribution
        $byStatus = Order::where('created_at', '>=', $since)
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total) as total'))
            ->groupBy('status')->get();

        return view('admin.finances.index', compact(
            'period', 'totalRevenue', 'margin', 'avgBasket', 'totalOrders',
            'refunds', 'monthly', 'topProducts', 'byStatus'
        ));
    }
}
