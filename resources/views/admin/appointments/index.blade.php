@extends('layouts.admin')
@section('title','Rendez-vous')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div>
      <h1 class="page-title">Rendez-vous & Leads</h1>
      <p class="page-subtitle">Demandes de formation et partenariat grossiste.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
  @endif

  {{-- Filters --}}
  <div class="filter-pills">
    <div class="search-bar" style="margin-right:auto;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="search" form="appt-filter" value="{{ request('search') }}" placeholder="Rechercher (nom, email)...">
    </div>
    <a href="?{{ http_build_query(array_merge(request()->except(['source','status','page']))) }}" class="filter-pill {{ !request('source') && !request('status') ? 'active' : '' }}">📋 Tous</a>
    <a href="?status=en_attente" class="filter-pill {{ request('status') === 'en_attente' ? 'active' : '' }}">⏳ En attente ({{ $pending }})</a>
    <a href="?source=formation" class="filter-pill {{ request('source') === 'formation' ? 'active' : '' }}">🎓 Formation ({{ $countBySource['formation'] }})</a>
    <a href="?source=grossiste" class="filter-pill {{ request('source') === 'grossiste' ? 'active' : '' }}">🤝 Grossiste ({{ $countBySource['grossiste'] }})</a>
  </div>
  <form id="appt-filter" method="GET" style="display:none;"></form>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Tél</th>
          <th>Type</th>
          <th>Source</th>
          <th>Date souhaitée</th>
          <th>Créneau</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($appointments as $a)
        <tr>
          <td style="font-weight:500;">{{ $a->name }}</td>
          <td style="font-size:var(--text-sm);">{{ $a->email }}</td>
          <td style="font-size:var(--text-sm);">{{ $a->phone ?? '—' }}</td>
          <td style="font-size:var(--text-sm);">{{ $a->type }}@if($a->company_name)<br><small style="color:var(--color-text-muted);">{{ $a->company_name }}</small>@endif</td>
          <td>
            <span style="font-size:var(--text-xs);padding:2px 8px;border-radius:12px;{{ $a->source === 'formation' ? 'background:rgba(196,150,122,0.15);color:var(--color-warm);' : 'background:rgba(76,175,80,0.1);color:#388e3c;' }}">{{ $a->source === 'formation' ? '🎓 Formation' : '🤝 Grossiste' }}</span>
          </td>
          <td style="font-size:var(--text-sm);">{{ $a->preferred_date ? \Carbon\Carbon::parse($a->preferred_date)->format('d/m/Y') : '—' }}</td>
          <td style="font-size:var(--text-sm);">{{ $a->preferred_time ?? '—' }}</td>
          <td>
            <span style="font-size:var(--text-xs);padding:2px 8px;border-radius:12px;{{ $a->status === 'confirme' ? 'background:#e8f5e9;color:#2e7d32;' : ($a->status === 'refuse' ? 'background:#ffebee;color:#c62828;' : 'background:#fff8e1;color:#f57f17;') }}">
              {{ $a->status === 'confirme' ? '✅ Confirmé' : ($a->status === 'refuse' ? '❌ Refusé' : '⏳ En attente') }}
            </span>
          </td>
          <td>
            <div style="display:flex;gap:4px;">
              <a href="{{ route('admin.appointments.show', $a) }}" class="action-btn" title="Détail">👁</a>
              <form method="POST" action="{{ route('admin.appointments.destroy', $a) }}" style="display:inline;" onsubmit="return confirm('Supprimer ce rendez-vous ?')">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn" title="Supprimer">🗑</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun rendez-vous.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $appointments->links() }}
</div>
<script>
  var link = document.querySelector('.sidebar-link[data-page="admin-rendezvous"]');
  if (link) link.classList.add('active');
</script>
@endsection
