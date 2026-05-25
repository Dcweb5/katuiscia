@extends('layouts.admin')
@section('title', 'Marketing & Tunnels de Vente')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Marketing & Leads</h1><p class="page-subtitle">Leads captés via le quiz beauté et tunnels de vente.</p></div>
  </div>

  <div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:var(--space-xl);">
    <div class="card stat-card"><span class="stat-title">Leads totaux</span><span class="stat-value">{{ $totalLeads }}</span></div>
    <div class="card stat-card"><span class="stat-title">Ce mois</span><span class="stat-value">{{ $thisMonth }}</span></div>
    <div class="card stat-card"><span class="stat-title">Taux opt-in</span><span class="stat-value">{{ $optInRate }}%</span></div>
    <div class="card stat-card"><span class="stat-title">Ayant acheté</span><span class="stat-value">{{ $purchased }}</span></div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-xl);margin-bottom:var(--space-xl);">
    <div class="card" style="padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 1rem;">📊 Sources</h3>
      <table class="admin-table">
        <thead><tr><th>Source</th><th>Leads</th></tr></thead>
        <tbody>
          @forelse($bySource as $s)
          <tr><td>{{ $s->utm_source ?: 'Direct' }}</td><td>{{ $s->total }}</td></tr>
          @empty
          <tr><td colspan="2" style="text-align:center;padding:2rem;color:var(--color-text-muted);">Aucune donnée.</td></tr>
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
          <tr><td colspan="2" style="text-align:center;padding:2rem;color:var(--color-text-muted);">Aucune réponse quiz.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr><th>Prénom</th><th>Email</th><th>Type peau</th><th>Budget</th><th>Source</th><th>Opt-in</th><th>Acheté</th><th>Date</th></tr>
      </thead>
      <tbody>
        @forelse($leads as $lead)
        <tr>
          <td>{{ $lead->firstname }}</td>
          <td style="font-size:13px;">{{ $lead->email }}</td>
          <td style="font-size:12px;">{{ ['seche'=>'Sèche','mixte'=>'Mixte','grasse'=>'Grasse','sensible'=>'Sensible','normale'=>'Normale'][$lead->quiz_responses['skin_type'] ?? ''] ?? '—' }}</td>
          <td style="font-size:12px;">{{ $lead->quiz_responses['budget'] ?? '—' }}</td>
          <td style="font-size:11px;">{{ $lead->utm_source ?: 'Direct' }}</td>
          <td>{{ $lead->opted_in ? '✅' : '—' }}</td>
          <td>{{ $lead->purchased ? '✅' : '—' }}</td>
          <td style="font-size:11px;color:var(--color-text-muted);">{{ $lead->created_at->format('d/m/Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun lead pour le moment. Partagez le lien du quiz !</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $leads->links() }}
</div>

<script>
  document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-funnels') l.classList.add('active'); });
</script>
@endsection
