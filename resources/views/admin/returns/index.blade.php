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

  <div style="display:flex;gap:0.5rem;margin-bottom:1rem;flex-wrap:wrap;">
    <a href="?" class="btn-primary" style="font-size:12px;text-decoration:none;{{ !request('status') ? '' : 'background:transparent;color:var(--color-text);border-color:var(--color-border);' }}">Tous</a>
    <a href="?status=pending" class="btn-primary" style="font-size:12px;text-decoration:none;{{ request('status')==='pending' ? '' : 'background:transparent;color:var(--color-text);border-color:var(--color-border);' }}">En attente</a>
    <a href="?status=approved" class="btn-primary" style="font-size:12px;text-decoration:none;{{ request('status')==='approved' ? '' : 'background:transparent;color:var(--color-text);border-color:var(--color-border);' }}">Approuvés</a>
    <a href="?status=completed" class="btn-primary" style="font-size:12px;text-decoration:none;{{ request('status')==='completed' ? '' : 'background:transparent;color:var(--color-text);border-color:var(--color-border);' }}">Terminés</a>
  </div>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr><th>N° Demande</th><th>Client</th><th>Commande</th><th>Produit</th><th>Type</th><th>Motif</th><th>Statut</th><th>Actions</th></tr>
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
          <td style="font-size:12px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $r->reason }}">{{ \Str::limit($r->reason, 60) }}</td>
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
        <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucune demande de retour.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $returns->links() }}
</div>

<form id="return-form" method="POST" style="display:none;">@csrf @method('PUT')</form>
<script>
function updateStatus(sel, id) {
  if (!sel.value) return;
  if (!confirm('Changer le statut en "' + sel.options[sel.selectedIndex].text + '" ?')) { sel.value = ''; return; }
  var f = document.getElementById('return-form');
  f.action = '{{ url('admin/retours') }}/' + id;
  var input = document.createElement('input');
  input.type = 'hidden'; input.name = 'status'; input.value = sel.value;
  f.appendChild(input);
  f.submit();
}
document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-retours') l.classList.add('active'); });
</script>
@endsection
