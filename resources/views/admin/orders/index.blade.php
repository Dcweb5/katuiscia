@extends('layouts.admin')
@section('title', 'Gestion des Commandes')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);">
    <div><h1 class="page-title">Gestion des Commandes</h1><p class="page-subtitle">Suivez et gérez toutes les commandes.</p></div>
    <span style="font-size:var(--text-xs);color:var(--color-text-muted);">{{ $stats['total'] }} commande(s)</span>
  </div>
  @if(session('success'))<div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);">{{ session('success') }}</div>@endif

  <!-- Stats -->
  <div class="stats-grid" style="grid-template-columns:repeat(6,1fr);margin-bottom:var(--space-xl);">
    <div class="card stat-card"><span class="stat-title">Total</span><span class="stat-value">{{ $stats['total'] }}</span></div>
    <div class="card stat-card"><span class="stat-title">En attente</span><span class="stat-value" style="color:var(--color-warm);">{{ $stats['pending'] }}</span></div>
    <div class="card stat-card"><span class="stat-title">Confirmées</span><span class="stat-value">{{ $stats['confirmed'] }}</span></div>
    <div class="card stat-card"><span class="stat-title">En prépa</span><span class="stat-value">{{ $stats['preparing'] }}</span></div>
    <div class="card stat-card"><span class="stat-title">Expédiées</span><span class="stat-value">{{ $stats['shipped'] }}</span></div>
    <div class="card stat-card"><span class="stat-title">Livrées</span><span class="stat-value" style="color:var(--color-success);">{{ $stats['delivered'] }}</span></div>
  </div>

  {{-- Filters --}}
  <div class="filter-pills">
    <div class="search-bar" style="margin-right:auto;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="search" form="orders-filter" value="{{ request('search') }}" placeholder="Rechercher (nº, nom, email)...">
    </div>
    @foreach([''=>'Tous statuts','pending'=>'En attente','confirmed'=>'Confirmées','preparing'=>'En prépa','shipped'=>'Expédiées','delivered'=>'Livrées','cancelled'=>'Annulées'] as $k=>$l)
    <a href="?{{ http_build_query(array_merge(request()->except('status','page'), $k ? ['status'=>$k] : [])) }}" class="filter-pill {{ request('status','') === $k ? 'active' : '' }}">{{ $l }}</a>
    @endforeach
    @include('components.date-filter', ['formId' => 'orders-filter'])
    <select name="sort" class="admin-input" form="orders-filter" style="width:auto;min-width:140px;padding:8px 14px;margin-left:0.5rem;">
      <option value="newest" @selected(!request('sort')||request('sort')==='newest')>Plus récent</option>
      <option value="oldest" @selected(request('sort')==='oldest')>Plus ancien</option>
    </select>
  </div>
  <form id="orders-filter" method="GET" style="display:none;"></form>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead><tr><th>Nº</th><th>Client</th><th>Date</th><th>Articles</th><th>Total</th><th>Statut</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($orders as $o)
        <tr>
          <td><strong style="font-family:monospace;">{{ $o->order_number }}</strong></td>
          <td>{{ $o->firstname }} {{ $o->lastname }}<br><small style="color:var(--color-text-muted);">{{ $o->email }}</small></td>
          <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
          <td>{{ $o->items->count() }}</td>
          <td><strong>{{ number_format($o->total, 2, ',', ' ') }} €</strong>@if($o->discount > 0)<br><small style="color:var(--color-success);">-{{ number_format($o->discount, 2, ',', ' ') }} €</small>@endif</td>
          <td>
            @php $colors = ['pending'=>'var(--color-warm)','confirmed'=>'#3b82f6','preparing'=>'#f59e0b','shipped'=>'#8b5cf6','delivered'=>'var(--color-success)','cancelled'=>'var(--color-error)']; @endphp
            <span class="status-badge" style="background:{{ $colors[$o->status] ?? '#ccc' }};color:white;font-size:10px;font-weight:600;">{{ $statuses[$o->status] ?? $o->status }}</span>
          </td>
          <td><a href="{{ route('admin.orders.show', $o) }}" class="action-btn" title="Voir"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a></td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucune commande.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $orders->links() }}
</div>
@endsection
