<?php
namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;

class TrackPageView
{
    public function handle(Request $request, Closure $next)
    {
        // Only track GET requests on web routes, skip assets, API, AJAX
        if ($request->isMethod('GET')
            && !$request->ajax()
            && !$request->expectsJson()
            && !$this->isAsset($request->path())) {
            try {
                PageView::create([
                    'url' => $request->fullUrl(),
                    'session_id' => session()->getId(),
                    'user_id' => auth()->id(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'referer' => $request->header('referer'),
                ]);
            } catch (\Exception $e) {
                // Silent fail - never break the app for analytics
            }
        }

        return $next($request);
    }

    private function isAsset($path): bool
    {
        $patterns = [
            '^admin/', '^_debugbar', '^storage/', '^dist/',
            '^css/', '^js/', '^assets/', '^favicon',
            '\.css$', '\.js$', '\.png$', '\.jpg$', '\.jpeg$',
            '\.gif$', '\.svg$', '\.woff', '\.ttf$', '\.ico$',
            '\.map$', '^livewire', '^api/', '^sanctum',
        ];
        foreach ($patterns as $p) {
            if (preg_match('#' . $p . '#i', $path)) return true;
        }
        return false;
    }
}
