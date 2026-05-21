<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Order;
use App\Models\PageView;
use App\Models\User;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_collections' => Collection::count(),
            'total_orders' => Order::count(),
            'total_users' => User::count(),
        ];

        // Monthly revenue
        $monthly = Order::whereIn('status', ['confirmed', 'preparing', 'shipped', 'delivered'])
            ->whereYear('created_at', now()->year)
            ->selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, sum(total) as total")
            ->groupBy('month')->orderBy('month')->get()
            ->pluck('total', 'month')->toArray();

        $labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $revenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenueData[] = $monthly[$i] ?? 0;
        }

        // Top products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['confirmed', 'preparing', 'shipped', 'delivered'])
            ->select('order_items.product_name', DB::raw('sum(order_items.quantity) as qty'))
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('qty')->take(5)->get();

        // Recent users
        $recentUsers = User::latest()->take(5)->get();

        // Orders by status
        $orderStatuses = [
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        // Recent orders
        $recentOrders = Order::with('items')->latest()->take(5)->get();

        // Visitors today
        $visitorsToday = PageView::whereDate('created_at', today())->distinct('session_id')->count('session_id');

        return view('admin.dashboard', compact(
            'stats', 'revenueData', 'labels', 'topProducts',
            'recentUsers', 'orderStatuses', 'recentOrders', 'visitorsToday'
        ));
    }
}
