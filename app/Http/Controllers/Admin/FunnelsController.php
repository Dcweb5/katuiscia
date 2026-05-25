<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;

class FunnelsController extends Controller
{
    public function index()
    {
        $totalLeads = Lead::count();
        $optInRate = $totalLeads > 0 ? round(Lead::where('opted_in', true)->count() / $totalLeads * 100) : 0;
        $purchased = Lead::where('purchased', true)->count();
        $thisMonth = Lead::whereMonth('created_at', now()->month)->count();

        $leads = Lead::orderBy('created_at', 'desc')->paginate(20);

        $bySource = Lead::selectRaw('utm_source, count(*) as total')
            ->whereNotNull('utm_source')
            ->groupBy('utm_source')
            ->orderByDesc('total')
            ->get();

        $bySkinType = Lead::whereNotNull('quiz_responses')
            ->get()
            ->groupBy(fn($l) => $l->quiz_responses['skin_type'] ?? 'inconnu')
            ->map->count();

        return view('admin.funnels.index', compact(
            'totalLeads', 'optInRate', 'purchased', 'thisMonth',
            'leads', 'bySource', 'bySkinType'
        ));
    }
}
