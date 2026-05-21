<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $days = match ($request->query('period', '7')) {
            '30' => 30,
            '90' => 90,
            default => 7,
        };

        $since = now()->subDays($days);

        $totalViews = PageView::where('created_at', '>=', $since)->count();
        $uniqueVisitors = PageView::where('created_at', '>=', $since)->distinct('session_id')->count('session_id');
        $avgPages = $uniqueVisitors > 0 ? round($totalViews / $uniqueVisitors, 1) : 0;

        // Daily chart
        $daily = PageView::where('created_at', '>=', $since)
            ->selectRaw('date(created_at) as d, count(*) as count')
            ->groupBy('d')->orderBy('d')->get();

        // Top pages
        $topPages = PageView::where('created_at', '>=', $since)
            ->select('url', DB::raw('count(*) as total'))
            ->groupBy('url')->orderByDesc('total')->take(10)->get()
            ->map(function ($r) use ($totalViews) {
                $r->pct = $totalViews > 0 ? round(($r->total / $totalViews) * 100) : 0;
                $r->short = strlen($r->url) > 60 ? '/' . last(explode('/', $r->url)) : str_replace(url('/'), '', $r->url);
                return $r;
            });

        // Referers
        $referers = PageView::where('created_at', '>=', $since)->whereNotNull('referer')
            ->select('referer', DB::raw('count(*) as total'))
            ->groupBy('referer')->orderByDesc('total')->take(5)->get();

        // Devices
        $devices = PageView::where('created_at', '>=', $since)
            ->select('user_agent')->get()
            ->reduce(function ($carry, $r) {
                $ua = strtolower($r->user_agent ?? '');
                if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) $carry['Mobile']++;
                elseif (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) $carry['Tablette']++;
                else $carry['Desktop']++;
                return $carry;
            }, ['Mobile' => 0, 'Desktop' => 0, 'Tablette' => 0]);

        return view('admin.analytics.index', compact(
            'days', 'totalViews', 'uniqueVisitors', 'avgPages',
            'daily', 'topPages', 'referers', 'devices'
        ));
    }
}
