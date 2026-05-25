@extends('layouts.admin')
@section('title', 'Marketing & Leads')
@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Marketing & Leads</h1><p class="page-subtitle">Leads captés via le quiz beauté — tunnels de vente.</p></div>
    <span style="font-size:12px;color:var(--color-text-muted);">🔄 MàJ auto toutes les 15 min</span>
  </div>

  <div class="stats-grid" style="display:grid;grid-template-columns:repeat(6,1fr);gap:1rem;margin-bottom:var(--space-xl);">
    <div class="card stat-card"><span class="stat-title">Leads</span><span class="stat-value">{{ $totalLeads }}</span></div>
    <div class="card stat-card"><span class="stat-title">Taux opt-in</span><span class="stat-value">{{ $optInRate }}%</span></div>
    <div class="card stat-card"><span class="stat-title">Conversion</span><span class="stat-value">{{ $conversionRate }}%</span></div>
    <div class="card stat-card"><span class="stat-title">CA total</span><span class="stat-value">{{ number_format($totalRevenue,0,',',' ') }} €</span></div>
    <div class="card stat-card"><span class="stat-title">Panier moyen</span><span class="stat-value">{{ $avgOrderValue }} €</span></div>
    <div class="card stat-card"><span class="stat-title">Paniers abandonnés</span><span class="stat-value">{{ $abandonedCarts }}</span></div>
  </div>

  <div style="display:grid;grid-template-columns:2fr 1fr;gap:var(--space-xl);margin-bottom:var(--space-xl);">
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 1rem;">📈 Leads & Revenus (6 mois)</h3>
      <div style="height:250px;"><canvas id="monthlyChart"></canvas></div>
    </div>
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 1rem;">🏆 Top Leads</h3>
      <table class="admin-table">
        <thead><tr><th>Lead</th><th>CA</th><th>cmd</th></tr></thead>
        <tbody>
          @forelse($topLeads as $l)
          <tr><td style="font-size:12px;">{{ $l->firstname }}<br><small style="color:var(--color-text-muted);">{{ $l->email }}</small></td><td>{{ number_format($l->total_revenue,0,',',' ') }} €</td><td>{{ $l->orders_count }}</td></tr>
          @empty
          <tr><td colspan="3" style="text-align:center;padding:1.5rem;color:var(--color-text-muted);font-size:12px;">Aucun achat via quiz</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-xl);margin-bottom:var(--space-xl);">
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 1rem;">📊 Sources</h3>
      <table class="admin-table">
        <thead><tr><th>Source</th><th>Leads</th><th>CA</th></tr></thead>
        <tbody>
          @forelse($bySource as $s)
          <tr><td>{{ $s->source }}</td><td>{{ $s->total }}</td><td>{{ number_format($s->revenue,0,',',' ') }} €</td></tr>
          @empty
          <tr><td colspan="3" style="text-align:center;padding:2rem;color:var(--color-text-muted);font-size:12px;">UTM non tracés.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 1rem;">🧬 Types de peau</h3>
      <table class="admin-table">
        <thead><tr><th>Type</th><th>Leads</th></tr></thead>
        <tbody>
          @forelse($bySkinType as $type => $count)
          @php $labels = ['seche'=>'🌵 Sèche','mixte'=>'⚖️ Mixte','grasse'=>'💧 Grasse','sensible'=>'🌸 Sensible','normale'=>'✨ Normale']; @endphp
          <tr><td>{{ $labels[$type] ?? $type }}</td><td>{{ $count }}</td></tr>
          @empty
          <tr><td colspan="2" style="text-align:center;padding:2rem;color:var(--color-text-muted);font-size:12px;">Aucune réponse quiz.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr><th>Prénom</th><th>Email</th><th>Type peau</th><th>Budget</th><th>Source</th><th>CA</th><th>Opt-in</th><th>Date</th></tr>
      </thead>
      <tbody>
        @forelse($leads as $lead)
        <tr>
          <td>{{ $lead->firstname }}</td>
          <td style="font-size:12px;">{{ $lead->email }}</td>
          <td style="font-size:11px;">{{ ['seche'=>'Sèche','mixte'=>'Mixte','grasse'=>'Grasse','sensible'=>'Sensible','normale'=>'Normale'][$lead->quiz_responses['skin_type'] ?? ''] ?? '—' }}</td>
          <td style="font-size:11px;">{{ $lead->quiz_responses['budget'] ?? '—' }}</td>
          <td style="font-size:11px;">{{ $lead->utm_source ?: 'Direct' }}</td>
          <td>{{ $lead->total_revenue > 0 ? number_format($lead->total_revenue,0,',',' ').' €' : '—' }}</td>
          <td>{{ $lead->opted_in ? '✅' : '—' }}</td>
          <td style="font-size:11px;color:var(--color-text-muted);">{{ $lead->created_at->format('d/m/Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun lead. Partagez le quiz !</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $leads->links() }}
</div>

<script>
new Chart(document.getElementById('monthlyChart'), {
  type: 'bar',
  data: {
    labels: @json($monthlyLeads->pluck('m')),
    datasets: [{
      label: 'Leads', yAxisID: 'y',
      data: @json($monthlyLeads->pluck('total')),
      backgroundColor: 'rgba(196,150,122,0.6)', borderRadius: 4,
    },{
      label: 'CA (€)', yAxisID: 'y1',
      data: @json($monthlyLeads->pluck('revenue')),
      type: 'line', borderColor: '#2d2117', backgroundColor: 'transparent',
      tension: 0.3, borderWidth: 2,
    }]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    scales: {
      y: { position:'left', grid:{color:'rgba(0,0,0,0.04)'} },
      y1: { position:'right', grid:{display:false}, ticks:{callback:v=>v+'€'} }
    },
    plugins: { legend: { labels: { usePointStyle: true, font: { size: 11 } } } }
  }
});
document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-funnels') l.classList.add('active'); });
</script>
@endsection
