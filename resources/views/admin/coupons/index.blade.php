@extends('layouts.admin')
@section('title', 'Coupons & Promotions')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);">
    <div>
      <h1 class="page-title">Coupons & Promotions</h1>
      <p class="page-subtitle">Gérez vos codes promo et offres spéciales.</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn-primary" style="text-decoration:none;">+ Nouveau coupon</a>
  </div>

  @if(session('success'))
  <div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);">{{ session('success') }}</div>
  @endif

  <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:var(--space-xl);">
    <div class="card stat-card"><span class="stat-title">Total</span><span class="stat-value">{{ $stats['total'] }}</span></div>
    <div class="card stat-card"><span class="stat-title">Actifs</span><span class="stat-value">{{ $stats['active'] }}</span></div>
    <div class="card stat-card"><span class="stat-title">Utilisations</span><span class="stat-value">{{ $stats['used'] }}</span></div>
  </div>

  {{-- Filters --}}
  <div class="filter-pills">
    <div class="search-bar" style="margin-right:auto;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="search" form="coupons-filter" value="{{ request('search') }}" placeholder="Rechercher un code...">
    </div>
    @foreach([''=>'Tous types','percentage'=>'Pourcentage','fixed'=>'Fixe','free_shipping'=>'Livraison gratuite'] as $k=>$l)
    <a href="?{{ http_build_query(array_merge(request()->except(['type','page']), $k ? ['type'=>$k] : [])) }}" class="filter-pill {{ request('type','') === $k ? 'active' : '' }}">{{ $l }}</a>
    @endforeach
    @include('components.date-filter', ['formId' => 'coupons-filter'])
  </div>
  <form id="coupons-filter" method="GET" style="display:none;"></form>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead><tr><th>Code</th><th>Type</th><th>Valeur</th><th>Min. commande</th><th>Utilisations</th><th>Dernière util.</th><th>Expire le</th><th>Statut</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($coupons as $c)
        <tr>
          <td><strong style="font-family:monospace;font-size:var(--text-md);">{{ $c->code }}</strong></td>
          <td>{{ $c->type === 'percentage' ? '%' : ($c->type === 'fixed' ? '€' : '🚚') }}</td>
          <td>{{ $c->type === 'free_shipping' ? '—' : $c->value }}</td>
          <td>{{ $c->min_order_amount ? number_format($c->min_order_amount,0,',',' ') . ' €' : '—' }}</td>
          <td>{{ $c->used_count }} / {{ $c->max_uses ?? '∞' }}</td>
          <td>{{ $c->last_used_at ? $c->last_used_at->format('d/m/Y H:i') : '—' }}</td>
          <td>{{ $c->expires_at ? $c->expires_at->format('d/m/Y') : '—' }}</td>
          <td>@if($c->is_active)<span class="status-badge status-badge--success">Actif</span>@else<span style="background:var(--color-gray-medium);color:var(--color-text-muted);padding:2px 10px;border-radius:var(--radius-full);font-size:10px;">Inactif</span>@endif</td>
          <td>
            <div style="display:flex;gap:4px;">
              <a href="{{ route('admin.coupons.edit', $c) }}" class="action-btn" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
              <form method="POST" action="{{ route('admin.coupons.destroy', $c) }}" onsubmit="event.preventDefault();showConfirm('Supprimer ce coupon ?',()=>this.submit())">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn action-btn--danger" title="Supprimer"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun coupon. <a href="{{ route('admin.coupons.create') }}" style="color:var(--color-warm);">Créer le premier</a>.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $coupons->links() }}
</div>
@endsection
