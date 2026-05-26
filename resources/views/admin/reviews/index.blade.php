@extends('layouts.admin')
@section('title','Avis clients')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);">
    <div><h1 class="page-title">Avis clients</h1><p class="page-subtitle">Modérez les avis produits.</p></div>
    <span style="font-size:12px;color:var(--color-text-muted);">{{ $pending }} en attente · {{ $approved }} approuvés</span>
  </div>

  {{-- Filters --}}
  <div class="filter-pills">
    <div class="search-bar" style="margin-right:auto;max-width:280px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="search" form="reviews-filter" value="{{ request('search') }}" placeholder="Rechercher...">
    </div>
    @foreach([''=>'Tous','pending'=>'En attente','approved'=>'Approuvés'] as $k=>$l)
    <a href="?{{ http_build_query(array_merge(request()->except(['status','page']), $k ? ['status'=>$k] : [])) }}" class="filter-pill {{ request('status','') === $k ? 'active' : '' }}">{{ $l }}</a>
    @endforeach
    @foreach([''=>'Tout','today'=>'Aujourd\'hui','7'=>'7 jours','30'=>'30 jours'] as $k=>$l)
    <a href="?{{ http_build_query(array_merge(request()->except(['period','page']), $k ? ['period'=>$k] : [])) }}" class="filter-pill {{ request('period','') === $k ? 'active' : '' }}">{{ $l }}</a>
    @endforeach
    <select name="sort" class="admin-input" form="reviews-filter" style="width:auto;min-width:150px;padding:8px 14px;margin-left:0.5rem;">
      <option value="newest" @selected(!request('sort')||request('sort')==='newest')>Plus récent</option>
      <option value="oldest" @selected(request('sort')==='oldest')>Plus ancien</option>
      <option value="rating" @selected(request('sort')==='rating')>Meilleure note</option>
    </select>
  </div>
  <form id="reviews-filter" method="GET" style="display:none;"></form>

  @if(session('success'))<div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);">{{ session('success') }}</div>@endif

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead><tr><th>Produit</th><th>Client</th><th>Note</th><th>Titre</th><th>Date</th><th>Statut</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($reviews as $r)
        <tr style="cursor:pointer;" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'':'none'">
          <td style="font-size:var(--text-sm);">{{ $r->product->name ?? '—' }}</td>
          <td style="font-size:var(--text-sm);">{{ $r->user->firstname ?? '—' }} {{ $r->user->lastname ?? '' }}</td>
          <td style="color:var(--color-warm);">{{ str_repeat('★',$r->rating) }}</td>
          <td style="font-size:var(--text-sm);">{{ $r->title }} <span style="font-size:10px;color:var(--color-warm);">🔍 cliquer pour lire</span></td>
          <td style="font-size:var(--text-xs);color:var(--color-text-muted);">{{ $r->created_at->format('d/m/Y') }}</td>
          <td>@if($r->is_approved)<span class="status-badge status-badge--success">Approuvé</span>@else<span style="background:var(--color-warm);color:white;padding:2px 10px;border-radius:var(--radius-full);font-size:10px;">En attente</span>@endif</td>
          <td>
            <div style="display:flex;gap:4px;">
              @if(!$r->is_approved)
              <form method="POST" action="{{ route('admin.reviews.approve', $r) }}" onclick="event.stopPropagation()">@csrf @method('PUT')<button type="submit" class="action-btn" title="Approuver (+50 pts)" style="color:var(--color-success);">✓</button></form>
              @endif
              <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" onsubmit="return confirm('Supprimer ?')" onclick="event.stopPropagation()">@csrf @method('DELETE')<button type="submit" class="action-btn action-btn--danger">×</button></form>
            </div>
          </td>
        </tr>
        <tr style="display:none;"><td colspan="7" style="background:var(--color-bg);padding:1rem;font-size:14px;line-height:1.6;">{{ $r->body ?? 'Pas de commentaire.' }}</td></tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun avis.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $reviews->links() }}
</div>
<script>document.querySelectorAll('.sidebar-link[data-page]').forEach(l=>{if(l.dataset.page==='admin-reviews')l.classList.add('active');});</script>
@endsection
