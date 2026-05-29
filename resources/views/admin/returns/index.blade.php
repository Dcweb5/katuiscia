@extends('layouts.admin')
@section('title', 'Gestion des Retours')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Retours & Échanges</h1><p class="page-subtitle">Gérez les demandes de retour des clients.</p></div>
  </div>

  @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
  @endif

  <div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:var(--space-xl);">
    <div class="card stat-card"><span class="stat-title">En attente</span><span class="stat-value">{{ $stats['pending'] }}</span></div>
    <div class="card stat-card"><span class="stat-title">Approuvés</span><span class="stat-value">{{ $stats['approved'] }}</span></div>
    <div class="card stat-card"><span class="stat-title">Reçus</span><span class="stat-value">{{ $stats['received'] }}</span></div>
    <div class="card stat-card"><span class="stat-title">Terminés</span><span class="stat-value">{{ $stats['completed'] }}</span></div>
  </div>

  {{-- Filters --}}
  <div class="filter-pills">
    <div class="search-bar" style="margin-right:auto;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" form="ret-filter" placeholder="Rechercher (n°, client)...">
    </div>
    <a href="?{{ http_build_query(array_merge(request()->except(['status','page']))) }}" class="filter-pill {{ !request('status') ? 'active' : '' }}">Tous</a>
    <a href="?status=pending" class="filter-pill {{ request('status')==='pending' ? 'active' : '' }}">En attente</a>
    <a href="?status=approved" class="filter-pill {{ request('status')==='approved' ? 'active' : '' }}">Approuvés</a>
    <a href="?status=completed" class="filter-pill {{ request('status')==='completed' ? 'active' : '' }}">Terminés</a>
    @include('components.date-filter', ['formId' => 'ret-filter'])
  </div>
  <form id="ret-filter" method="GET" style="display:none;"></form>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr><th>N° Demande</th><th>Client</th><th>Commande</th><th>Produit</th><th>Type</th><th>Motif</th><th>Photos</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($returns as $r)
        <tr>
          <td style="font-family:monospace;font-size:13px;">{{ $r->request_number }}</td>
          <td>
            <strong>{{ $r->user->full_name ?? $r->user->name ?? 'N/A' }}</strong>
            <br><small style="color:var(--color-text-muted);">{{ $r->user->email ?? '' }}</small>
          </td>
          <td style="font-size:13px;">{{ $r->order->order_number ?? '—' }}</td>
          <td style="font-size:13px;">{{ $r->item->product_name ?? 'Toute la commande' }}</td>
          <td><span style="font-size:12px;">{{ $r->type === 'return' ? '↩ Retour' : '🔄 Échange' }}</span></td>
          <td style="font-size:12px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;cursor:pointer;" title="Cliquez pour lire" onclick="this.classList.toggle('expanded')">{{ $r->reason }}</td>
          <td>
            @if($r->images->isNotEmpty())
            <div style="display:flex;gap:3px;flex-wrap:wrap;max-width:100px;">
              @foreach($r->images->take(3) as $img)
              <a href="{{ asset('storage/'.$img->path) }}" target="_blank"><img src="{{ asset('storage/'.$img->path) }}" style="width:28px;height:28px;border-radius:4px;object-fit:cover;"></a>
              @endforeach
              @if($r->images->count() > 3)<span style="font-size:10px;color:var(--color-text-muted);">+{{ $r->images->count() - 3 }}</span>@endif
            </div>
            @else <span style="font-size:11px;color:var(--color-text-muted);">—</span>
            @endif
          </td>
          <td>
            @php $colors = ['pending'=>'#f59e0b','approved'=>'#3b82f6','received'=>'#8b5cf6','completed'=>'#2e7d32','rejected'=>'#c62828','cancelled'=>'#999']; @endphp
            <span style="font-size:11px;font-weight:600;color:#fff;padding:3px 10px;border-radius:var(--radius-full);background:{{ $colors[$r->status] ?? '#ccc' }};">
              {{ ['pending'=>'En attente','approved'=>'Approuvé','received'=>'Reçu','completed'=>'Terminé','rejected'=>'Refusé','cancelled'=>'Annulé'][$r->status] ?? $r->status }}
            </span>
          </td>
          <td>
            <div style="display:flex;gap:4px;align-items:center;">
              <select onchange="updateStatus(this, {{ $r->id }})" style="padding:4px 8px;border:1px solid var(--color-border);border-radius:6px;font-size:11px;">
                <option value="">Action...</option>
                <option value="approved">✅ Approuver</option>
                <option value="received">📦 Colis reçu</option>
                <option value="completed">💵 Rembourser</option>
                <option value="rejected">❌ Refuser</option>
              </select>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucune demande de retour.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $returns->links() }}
</div>

<form id="return-form" method="POST" style="display:none;">@csrf @method('PUT')</form>
<style>
.expanded { white-space:normal !important; overflow:visible !important; text-overflow:unset !important; max-width:none !important; background:#faf7f2;padding:8px 12px;border-radius:6px; }
</style>
<script>
function updateStatus(sel, id) {
  if (!sel.value) return;
  var label = sel.options[sel.selectedIndex].text;
  showConfirm('Changer le statut en "' + label + '" ?', function(){
    var f = document.getElementById('return-form');
    f.action = '{{ url('admin/retours') }}/' + id;
    var input = document.createElement('input');
    input.type = 'hidden'; input.name = 'status'; input.value = sel.value;
    f.appendChild(input);
    f.submit();
  });
}
document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-retours') l.classList.add('active'); });
</script>
@endsection
