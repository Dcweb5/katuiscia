@extends('layouts.admin')
@section('title', 'Analytics')
@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Analytics</h1><p class="page-subtitle">Trafic et comportement visiteurs.</p></div>
    <div style="display:flex;gap:0.5rem;">
      <a href="?period=7" class="btn-primary" style="font-size:12px;text-decoration:none;{{ $days == 7 ? '' : 'background:transparent;color:var(--color-text);border-color:var(--color-border);' }}">7 jours</a>
      <a href="?period=30" class="btn-primary" style="font-size:12px;text-decoration:none;{{ $days == 30 ? '' : 'background:transparent;color:var(--color-text);border-color:var(--color-border);' }}">30 jours</a>
      <a href="?period=90" class="btn-primary" style="font-size:12px;text-decoration:none;{{ $days == 90 ? '' : 'background:transparent;color:var(--color-text);border-color:var(--color-border);' }}">{{ $days == 90 ? '90 jours' : '90 jours' }}</a>
    </div>
  </div>

  <div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:var(--space-xl);">
    <div class="card stat-card" style="padding:1.25rem;">
      <span class="stat-title">Visiteurs uniques</span>
      <span class="stat-value">{{ number_format($uniqueVisitors, 0, ',', ' ') }}</span>
    </div>
    <div class="card stat-card" style="padding:1.25rem;">
      <span class="stat-title">Pages vues</span>
      <span class="stat-value">{{ number_format($totalViews, 0, ',', ' ') }}</span>
    </div>
    <div class="card stat-card" style="padding:1.25rem;">
      <span class="stat-title">Pages / session</span>
      <span class="stat-value">{{ $avgPages }}</span>
    </div>
    <div class="card stat-card" style="padding:1.25rem;">
      <span class="stat-title">Taux de conversion</span>
      <span class="stat-value">{{ $uniqueVisitors > 0 ? round((\App\Models\Order::where('created_at', '>=', now()->subDays($days))->count() / $uniqueVisitors) * 100, 1) : 0 }}%</span>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:2fr 1fr;gap:var(--space-xl);margin-bottom:var(--space-xl);">
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);margin:0 0 1rem;">📊 Trafic journalier</h3>
      <div style="height:250px;"><canvas id="dailyChart"></canvas></div>
    </div>
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);margin:0 0 1rem;">📱 Appareils</h3>
      <div style="height:250px;"><canvas id="deviceChart"></canvas></div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-xl);">
    <div class="card" style="padding:0;overflow:hidden;">
      <h3 style="font-family:var(--font-heading);padding:1.25rem;margin:0;border-bottom:1px solid var(--color-border);">📄 Pages les plus visitées</h3>
      <table class="admin-table">
        <thead><tr><th>Page</th><th>Vues</th><th>%</th></tr></thead>
        <tbody>
          @forelse($topPages as $p)
          <tr><td style="font-size:13px;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $p->url }}">{{ $p->short }}</td><td>{{ $p->total }}</td><td>{{ $p->pct }}%</td></tr>
          @empty
          <tr><td colspan="3" style="text-align:center;padding:2rem;color:var(--color-text-muted);">Aucune donnée.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card" style="padding:0;overflow:hidden;">
      <h3 style="font-family:var(--font-heading);padding:1.25rem;margin:0;border-bottom:1px solid var(--color-border);">🔗 Sources de trafic</h3>
      <table class="admin-table">
        <thead><tr><th>Source</th><th>Visites</th></tr></thead>
        <tbody>
          @forelse($referers as $r)
          @php
            $domain = parse_url($r->referer, PHP_URL_HOST) ?? $r->referer;
            if (str_contains($domain, 'google')) $label = '🔍 Google';
            elseif (str_contains($domain, 'facebook') || str_contains($domain, 'instagram')) $label = '📱 ' . ucfirst(parse_url($r->referer, PHP_URL_HOST));
            elseif (str_contains($domain, 't.co') || str_contains($domain, 'twitter')) $label = '🐦 Twitter';
            else $label = '🌐 ' . $domain;
          @endphp
          <tr><td style="font-size:13px;">{{ $label }}</td><td>{{ $r->total }}</td></tr>
          @empty
          <tr><td colspan="2" style="text-align:center;padding:2rem;color:var(--color-text-muted);">Aucune donnée — le trafic direct n'est pas compté comme source.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
new Chart(document.getElementById('dailyChart'), {
  type: 'bar',
  data: {
    labels: @json($daily->pluck('d')->toArray()),
    datasets: [{
      label: 'Visites',
      data: @json($daily->pluck('count')->toArray()),
      backgroundColor: 'rgba(196,150,122,0.7)', borderRadius: 6,
    }]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{legend:{display:false}},
    scales:{y:{grid:{color:'rgba(0,0,0,0.04)'}},x:{grid:{display:false}}}
  }
});

new Chart(document.getElementById('deviceChart'), {
  type: 'doughnut',
  data: {
    labels: ['Mobile', 'Desktop', 'Tablette'],
    datasets: [{
      data: [{{ $devices['Mobile'] }}, {{ $devices['Desktop'] }}, {{ $devices['Tablette'] }}],
      backgroundColor: ['#c4967a', '#3D2B2B', '#C8DDD3'],
      borderWidth:2, borderColor:'white'
    }]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{legend:{position:'bottom',labels:{padding:16,usePointStyle:true,font:{size:11}}}}
  }
});
document.querySelectorAll('.sidebar-link[data-page]').forEach(l=>{if(l.dataset.page==='admin-analytics')l.classList.add('active');});
</script>
@endsection
