@extends('layouts.admin')
@section('title','Avis clients')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);">
    <div><h1 class="page-title">Avis clients</h1><p class="page-subtitle">Modérez les avis produits.</p></div>
    <span style="font-size:var(--text-xs);color:var(--color-text-muted);">{{ $pending }} en attente · {{ $approved }} approuvés</span>
  </div>
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
        <tr style="display:none;background:rgba(196,150,122,0.04);">
          <td colspan="7" style="padding:var(--space-xl);">
            <div style="display:grid;grid-template-columns:auto 1fr;gap:var(--space-lg);">
              <div style="display:flex;align-items:center;gap:var(--space-md);">
                <div style="width:72px;height:72px;border-radius:var(--radius-md);overflow:hidden;background:var(--color-gray-k);flex-shrink:0;">
                  @php $img = $r->product->images->first(); @endphp
                  @if($img)<img src="{{ asset('storage/'.$img->path) }}" alt="" style="width:100%;height:100%;object-fit:cover;">@endif
                </div>
                <div>
                  <strong style="font-size:var(--text-md);display:block;">{{ $r->product->name ?? '—' }}</strong>
                  <span style="color:var(--color-warm);">{{ str_repeat('★',$r->rating) }}{{ str_repeat('☆',5-$r->rating) }}</span>
                  <p style="font-size:11px;color:var(--color-text-muted);">Par {{ $r->user->firstname ?? '' }} {{ $r->user->lastname ?? '' }} • {{ $r->created_at->format('d/m/Y') }}</p>
                </div>
              </div>
              <div>
                <h4 style="font-size:var(--text-lg);margin-bottom:var(--space-sm);">{{ $r->title }}</h4>
                @if($r->body)<p style="font-size:var(--text-sm);color:var(--color-text-light);line-height:1.7;white-space:pre-wrap;">{{ $r->body }}</p>@else<p style="color:var(--color-text-muted);font-style:italic;">Pas de commentaire</p>@endif
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun avis.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $reviews->links() }}
</div>
@endsection
