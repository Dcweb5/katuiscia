@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
  .stat-card-animated { transition: transform 0.3s, box-shadow 0.3s; cursor: default; }
  .stat-card-animated:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
  .chart-container { position: relative; width: 100%; min-height: 260px; }
  .quick-action-card { display:flex; align-items:center; gap:var(--space-md); padding:var(--space-lg); border-radius:var(--radius-lg); background:var(--color-white); border:1px solid var(--color-border); transition:all 0.3s; text-decoration:none; color:inherit; }
  .quick-action-card:hover { border-color:var(--color-warm); transform:translateY(-2px); box-shadow:var(--shadow-md); }
  .quick-action-icon { width:48px; height:48px; border-radius:var(--radius-md); display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
</style>
@endsection

@section('content')
<div class="dashboard-content">
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:var(--space-2xl);">
    <div>
      <h1 class="page-title" style="margin-bottom:4px;">Bonjour, {{ auth()->user()->firstname ?? 'Admin' }}</h1>
      <p class="page-subtitle" style="margin:0;">Vue d'ensemble de votre boutique KATUISCIA.</p>
    </div>
    <span style="font-size:var(--text-xs); color:var(--color-text-muted);">{{ now()->format('d F Y') }}</span>
  </div>

  <!-- STATS CARDS -->
  <div class="stats-grid" style="grid-template-columns:repeat(6, 1fr); margin-bottom:var(--space-xl);">
    <div class="card stat-card stat-card-animated">
      <div style="display:flex; align-items:center; gap:var(--space-md);">
        <div style="width:48px; height:48px; border-radius:var(--radius-md); background:var(--color-peach); display:flex; align-items:center; justify-content:center; font-size:24px;">📦</div>
        <div>
          <span class="stat-title" style="margin-bottom:2px;">Produits</span>
          <span class="stat-value count-up" data-target="{{ $stats['total_products'] }}" style="font-size:2rem;">0</span>
        </div>
      </div>
      <a href="{{ url('admin/produits') }}" style="font-size:10px; color:var(--color-warm); margin-top:4px; display:block;">Voir tous →</a>
    </div>
    <div class="card stat-card stat-card-animated">
      <div style="display:flex; align-items:center; gap:var(--space-md);">
        <div style="width:48px; height:48px; border-radius:var(--radius-md); background:var(--color-mint); display:flex; align-items:center; justify-content:center; font-size:24px;">🏷️</div>
        <div>
          <span class="stat-title" style="margin-bottom:2px;">Catégories</span>
          <span class="stat-value count-up" data-target="{{ $stats['total_categories'] }}" style="font-size:2rem;">0</span>
        </div>
      </div>
      <a href="{{ url('admin/categories') }}" style="font-size:10px; color:var(--color-warm); margin-top:4px; display:block;">Gérer →</a>
    </div>
    <div class="card stat-card stat-card-animated">
      <div style="display:flex; align-items:center; gap:var(--space-md);">
        <div style="width:48px; height:48px; border-radius:var(--radius-md); background:#e8f5e9; display:flex; align-items:center; justify-content:center; font-size:24px;">📦</div>
        <div>
          <span class="stat-title" style="margin-bottom:2px;">Collections</span>
          <span class="stat-value count-up" data-target="{{ $stats['total_collections'] }}" style="font-size:2rem;">0</span>
        </div>
      </div>
      <a href="{{ url('admin/collections') }}" style="font-size:10px; color:var(--color-warm); margin-top:4px; display:block;">Gérer →</a>
    </div>
    <div class="card stat-card stat-card-animated">
      <div style="display:flex; align-items:center; gap:var(--space-md);">
        <div style="width:48px; height:48px; border-radius:var(--radius-md); background:#e0d8ff; display:flex; align-items:center; justify-content:center; font-size:24px;">📋</div>
        <div>
          <span class="stat-title" style="margin-bottom:2px;">Commandes</span>
          <span class="stat-value count-up" data-target="{{ $stats['total_orders'] }}" style="font-size:2rem;">0</span>
        </div>
      </div>
      <a href="{{ url('admin/commandes') }}" style="font-size:10px; color:var(--color-warm); margin-top:4px; display:block;">Voir toutes →</a>
    </div>
    <div class="card stat-card stat-card-animated">
      <div style="display:flex; align-items:center; gap:var(--space-md);">
        <div style="width:48px; height:48px; border-radius:var(--radius-md); background:#ffe0cc; display:flex; align-items:center; justify-content:center; font-size:24px;">👥</div>
        <div>
          <span class="stat-title" style="margin-bottom:2px;">Utilisateurs</span>
          <span class="stat-value count-up" data-target="{{ $stats['total_users'] }}" style="font-size:2rem;">0</span>
        </div>
      </div>
      <a href="{{ url('admin/utilisateurs') }}" style="font-size:10px; color:var(--color-warm); margin-top:4px; display:block;">Voir tous →</a>
    </div>
    <div class="card stat-card stat-card-animated">
      <div style="display:flex; align-items:center; gap:var(--space-md);">
        <div style="width:48px; height:48px; border-radius:var(--radius-md); background:#ede4db; display:flex; align-items:center; justify-content:center; font-size:24px;">📊</div>
        <div>
          <span class="stat-title" style="margin-bottom:2px;">Aujourd'hui</span>
          <span class="stat-value count-up" data-target="{{ $visitorsToday }}" style="font-size:2rem;">0</span>
        </div>
      </div>
      <span style="font-size:10px; color:var(--color-text-muted); margin-top:4px; display:block;">Visiteurs</span>
    </div>
  </div>

  <!-- CHARTS ROW -->
  <div style="display:grid; grid-template-columns:2fr 1fr; gap:var(--space-xl); margin-bottom:var(--space-xl);">
    <!-- Revenue Chart -->
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-lg); display:flex; align-items:center; justify-content:space-between;">
        <span>📈 Revenus mensuels</span>
        <select id="revenueYear" style="padding:4px 8px; border:1px solid var(--color-border); border-radius:var(--radius-sm); font-size:12px; background:transparent;">
          <option>2026</option><option>2025</option>
        </select>
      </h3>
      <div class="chart-container"><canvas id="revenueChart"></canvas></div>
    </div>

    <!-- Top Products -->
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-lg);">🥇 Top Produits</h3>
      <div class="chart-container"><canvas id="topProductsChart"></canvas></div>
    </div>
  </div>

  <!-- QUICK ACTIONS + RECENT ACTIVITY -->
  <div style="display:grid; grid-template-columns:1fr 2fr; gap:var(--space-xl);">
    <!-- Quick Actions -->
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-lg);">⚡ Actions rapides</h3>
      <div style="display:flex; flex-direction:column; gap:var(--space-sm);">
        <a href="{{ route('admin.products.create') }}" class="quick-action-card">
          <div class="quick-action-icon" style="background:var(--color-peach);">📦</div>
          <div><strong style="font-size:var(--text-sm);">Nouveau produit</strong><br><span style="font-size:11px; color:var(--color-text-muted);">Ajouter à la boutique</span></div>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="quick-action-card">
          <div class="quick-action-icon" style="background:var(--color-mint);">🏷️</div>
          <div><strong style="font-size:var(--text-sm);">Catégories</strong><br><span style="font-size:11px; color:var(--color-text-muted);">Gérer les catégories</span></div>
        </a>
        <a href="{{ url('admin/commandes') }}" class="quick-action-card">
          <div class="quick-action-icon" style="background:#e0d8ff;">📋</div>
          <div><strong style="font-size:var(--text-sm);">Commandes</strong><br><span style="font-size:11px; color:var(--color-text-muted);">Voir les commandes</span></div>
        </a>
        <a href="{{ url('admin/utilisateurs') }}" class="quick-action-card">
          <div class="quick-action-icon" style="background:#ffe0cc;">👥</div>
          <div><strong style="font-size:var(--text-sm);">Utilisateurs</strong><br><span style="font-size:11px; color:var(--color-text-muted);">Gérer les comptes</span></div>
        </a>
        <a href="{{ url('/') }}" target="_blank" class="quick-action-card">
          <div class="quick-action-icon" style="background:var(--color-dark); color:white;">🌐</div>
          <div><strong style="font-size:var(--text-sm);">Voir le site</strong><br><span style="font-size:11px; color:var(--color-text-muted);">Ouvrir dans un nouvel onglet</span></div>
        </a>
      </div>
    </div>

    <!-- Recent Users list -->
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-lg);">🆕 Utilisateurs récents</h3>
      @php $recentUsers = $recentUsers ?? \App\Models\User::latest()->take(5)->get(); @endphp
      @if($recentUsers->count() > 0)
      <div style="display:flex; flex-direction:column; gap:var(--space-md);">
        @foreach($recentUsers as $u)
        <div style="display:flex; align-items:center; justify-content:space-between; padding:var(--space-sm) 0; border-bottom:1px solid var(--color-border);">
          <div style="display:flex; align-items:center; gap:var(--space-sm);">
            <div style="width:36px; height:36px; border-radius:50%; background:{{ $u->is_admin ? 'var(--color-dark)' : 'var(--color-warm)' }}; color:white; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:bold;">
              {{ substr($u->firstname ?? 'U', 0, 1) }}{{ substr($u->lastname ?? '', 0, 1) }}
            </div>
            <div>
              <strong style="font-size:var(--text-sm);">{{ $u->firstname }} {{ $u->lastname }}</strong>
              <br><span style="font-size:11px; color:var(--color-text-muted);">{{ $u->email }}</span>
            </div>
          </div>
          <span style="font-size:10px; text-transform:uppercase; letter-spacing:0.05em; {{ $u->is_admin ? 'color:var(--color-dark); font-weight:600;' : 'color:var(--color-text-muted);' }}">{{ $u->is_admin ? '👑 Admin' : '👤 Client' }}</span>
        </div>
        @endforeach
      </div>
      @else
      <p style="color:var(--color-text-muted); text-align:center; padding:var(--space-xl);">Aucun utilisateur.</p>
      @endif
    </div>
  </div>
</div>

<script>
// Animated counter
document.querySelectorAll('.count-up').forEach(el => {
  const target = parseInt(el.dataset.target);
  const duration = 1500;
  const start = performance.now();
  function update(now) {
    const elapsed = now - start;
    const progress = Math.min(elapsed / duration, 1);
    const eased = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.floor(eased * target);
    if (progress < 1) requestAnimationFrame(update);
  }
  requestAnimationFrame(update);
});

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
  type: 'bar',
  data: {
    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
    datasets: [{
      label: 'Revenus (€)',
      data: @json(array_values($revenueData)),
      backgroundColor: ctx => {
        const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(196,150,122,0.8)');
        gradient.addColorStop(1, 'rgba(196,150,122,0.2)');
        return gradient;
      },
      borderRadius: 6,
      borderSkipped: false,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { callback: v => v + '€', font: { size: 11 } } },
      x: { grid: { display: false }, ticks: { font: { size: 10 } } }
    },
    animation: { duration: 1200, easing: 'easeOutQuart' }
  }
});

// Top Products Chart
const topCtx = document.getElementById('topProductsChart').getContext('2d');
new Chart(topCtx, {
  type: 'doughnut',
  data: {
    labels: @json($topProducts->pluck('product_name')->toArray()),
    datasets: [{
      data: @json($topProducts->pluck('qty')->toArray()),
      backgroundColor: ['rgba(196,150,122,0.9)', '#3D2B2B', '#C8DDD3', '#F2E4DA', '#E8E5E0'],
      borderWidth: 2,
      borderColor: 'white',
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyleWidth: 10, font: { size: 11 } } }
    },
    animation: { animateRotate: true, duration: 1500 }
  }
});
</script>
@endsection
