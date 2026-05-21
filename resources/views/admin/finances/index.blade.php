@extends('layouts.admin')
@section('title', 'Finances')
@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Finances</h1><p class="page-subtitle">Performance financière de votre boutique.</p></div>
    <div style="display:flex;gap:0.5rem;">
      <a href="?period=month" class="btn-primary" style="font-size:12px;text-decoration:none;{{ $period=='month'?'':'background:transparent;color:var(--color-text);border-color:var(--color-border);' }}">Ce mois</a>
      <a href="?period=quarter" class="btn-primary" style="font-size:12px;text-decoration:none;{{ $period=='quarter'?'':'background:transparent;color:var(--color-text);border-color:var(--color-border);' }}">Trimestre</a>
      <a href="?period=year" class="btn-primary" style="font-size:12px;text-decoration:none;{{ $period=='year'?'':'background:transparent;color:var(--color-text);border-color:var(--color-border);' }}">Année</a>
    </div>
  </div>

  <div class="stats-grid" style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:var(--space-xl);">
    <div class="card stat-card" style="padding:1.25rem;">
      <span class="stat-title">Chiffre d'affaires</span>
      <span class="stat-value" style="font-family:var(--font-display);">{{ number_format($totalRevenue, 0, ',', ' ') }} €</span>
    </div>
    <div class="card stat-card" style="padding:1.25rem;">
      <span class="stat-title">Marge brute</span>
      <span class="stat-value">{{ number_format($margin, 0, ',', ' ') }} €</span>
      <span style="font-size:11px;color:var(--color-success);">~60%</span>
    </div>
    <div class="card stat-card" style="padding:1.25rem;">
      <span class="stat-title">Panier moyen</span>
      <span class="stat-value">{{ number_format($avgBasket, 0, ',', ' ') }} €</span>
    </div>
    <div class="card stat-card" style="padding:1.25rem;">
      <span class="stat-title">Commandes</span>
      <span class="stat-value">{{ $totalOrders }}</span>
    </div>
    <div class="card stat-card" style="padding:1.25rem;">
      <span class="stat-title">Remboursements</span>
      <span class="stat-value" style="color:var(--color-error);">{{ number_format($refunds, 0, ',', ' ') }} €</span>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:2fr 1fr;gap:var(--space-xl);margin-bottom:var(--space-xl);">
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);margin:0 0 1rem;">📈 Évolution mensuelle (12 mois)</h3>
      <div style="height:280px;"><canvas id="monthlyChart"></canvas></div>
    </div>
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);margin:0 0 1rem;">📦 Commandes par statut</h3>
      <div style="height:280px;"><canvas id="statusChart"></canvas></div>
    </div>
  </div>

  <div class="card" style="padding:0;overflow:hidden;margin-bottom:var(--space-xl);">
    <h3 style="font-family:var(--font-heading);padding:1.25rem;margin:0;border-bottom:1px solid var(--color-border);">🥇 Top produits vendeurs</h3>
    <table class="admin-table">
      <thead><tr><th>Produit</th><th>Quantité vendue</th><th>Revenu généré</th></tr></thead>
      <tbody>
        @forelse($topProducts as $p)
        <tr>
          <td style="font-weight:500;">{{ $p->product_name }}</td>
          <td>{{ $p->qty }}</td>
          <td style="font-family:var(--font-display);">{{ number_format($p->revenue, 0, ',', ' ') }} €</td>
        </tr>
        @empty
        <tr><td colspan="3" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucune vente sur cette période.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<script>
new Chart(document.getElementById('monthlyChart'), {
  type: 'bar',
  data: {
    labels: @json($monthly->pluck('m')->toArray()),
    datasets: [{
      label: 'CA (€)',
      data: @json($monthly->pluck('total')->toArray()),
      backgroundColor: ctx => { var g=ctx.chart.ctx.createLinearGradient(0,0,0,280);g.addColorStop(0,'rgba(196,150,122,0.8)');g.addColorStop(1,'rgba(196,150,122,0.1)');return g;},
      borderRadius: 6, borderSkipped: false,
    }]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{legend:{display:false}},
    scales:{y:{grid:{color:'rgba(0,0,0,0.04)'},ticks:{callback:v=>v+'€'}},x:{grid:{display:false}}}
  }
});

var statusData = @json($byStatus->pluck('count','status'));
var labels = {pending:'En attente',confirmed:'Confirmée',preparing:'Préparation',shipped:'Expédiée',delivered:'Livrée',cancelled:'Annulée'};
var statusLabels = Object.keys(statusData).map(k => labels[k] || k);
var counts = Object.values(statusData);
new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: {
    labels: statusLabels,
    datasets: [{data: counts, backgroundColor: ['#f59e0b','#3b82f6','#e65100','#8b5cf6','#2e7d32','#c62828'],borderWidth:2,borderColor:'white'}]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins:{legend:{position:'bottom',labels:{padding:12,usePointStyle:true,font:{size:11}}}}
  }
});

document.querySelectorAll('.sidebar-link[data-page]').forEach(l=>{if(l.dataset.page==='admin-finances')l.classList.add('active');});
</script>
@endsection
