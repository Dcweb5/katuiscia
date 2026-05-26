<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FunnelsController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::orderBy('created_at', 'desc');

        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('firstname','like',"%{$search}%")->orWhere('email','like',"%{$search}%");
            });
        }
        if ($period = $request->query('period')) {
            $days = match($period) { 'today' => 1, '7d' => 7, '30d' => 30, default => null };
            if ($days) $query->where('created_at', '>=', now()->subDays($days));
        }

        $totalLeads = Lead::count();
        $optedIn = Lead::where('opted_in', true)->count();
        $optInRate = $totalLeads > 0 ? round($optedIn / $totalLeads * 100) : 0;
        $purchased = Lead::where('purchased', true)->count();
        $conversionRate = $optedIn > 0 ? round($purchased / $optedIn * 100) : 0;

        $thisMonth = Lead::whereMonth('created_at', now()->month)->count();
        $thisMonthRevenue = Lead::whereMonth('created_at', now()->month)->sum('total_revenue');
        $totalRevenue = Lead::sum('total_revenue');

        $avgOrderValue = $purchased > 0
            ? round(Lead::where('purchased', true)->avg('total_revenue'))
            : 0;

        // Abandoned carts
        $abandonedCarts = \App\Models\Cart::whereNotNull('abandoned_at')->count();
        $recoveredCarts = \App\Models\Cart::whereNotNull('abandoned_at')->whereNotNull('email_sent_at')->count();

        $leads = Lead::orderBy('created_at', 'desc')->paginate(20);

        $bySource = Lead::selectRaw('COALESCE(utm_source, \'Direct\') as source, count(*) as total, sum(total_revenue) as revenue')
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        $bySkinType = Lead::whereNotNull('quiz_responses')
            ->get()
            ->groupBy(fn($l) => $l->quiz_responses['skin_type'] ?? 'inconnu')
            ->map->count();

        // Revenue par mois (6 derniers mois)
        $monthlyLeads = Lead::selectRaw("strftime('%Y-%m', created_at) as m, count(*) as total, sum(total_revenue) as revenue")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('m')->orderBy('m')->get();

        // Top leads par revenue
        $topLeads = Lead::where('purchased', true)->where('total_revenue', '>', 0)
            ->orderByDesc('total_revenue')->take(5)->get();

        return view('admin.funnels.index', compact(
            'totalLeads', 'optedIn', 'optInRate', 'purchased', 'conversionRate',
            'thisMonth', 'thisMonthRevenue', 'totalRevenue', 'avgOrderValue',
            'abandonedCarts', 'recoveredCarts', 'monthlyLeads',
            'leads', 'bySource', 'bySkinType', 'topLeads'
        ));
    }
}
