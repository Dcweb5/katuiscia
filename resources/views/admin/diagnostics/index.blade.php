@extends('layouts.admin')
@section('title', 'Diagnostics Peau IA')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Diagnostics Peau IA</h1><p class="page-subtitle">Consultez les analyses cutanées effectuées par l'IA.</p></div>
  </div>

  @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
  @endif

  {{-- Filters --}}
  <div class="filter-pills">
    <div class="search-bar" style="margin-right:auto;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="diag-search" placeholder="Rechercher un client ou une préoccupation..." onkeyup="filterTable(this.value)">
    </div>
  </div>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr><th>Selfie</th><th>Client</th><th>Type de peau</th><th>Préoccupation</th><th>Gravité IA</th><th>Date</th><th>Actions</th></tr>
      </thead>
      <tbody id="diagnostics-table-body">
        @forelse($diagnostics as $d)
        <tr data-name="{{ strtolower($d->name) }}" data-email="{{ strtolower($d->email) }}" data-concern="{{ strtolower($d->concern) }}">
          <td>
            <img src="{{ asset($d->image_path) }}" alt="Selfie" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:1px solid var(--color-border);cursor:pointer;" onclick="viewDetails({{ json_encode($d) }})">
          </td>
          <td>
            <strong>{{ $d->name }}</strong>
            <br><small style="color:var(--color-text-muted);">{{ $d->email }}</small>
            @if($d->phone)<br><small style="color:var(--color-text-muted);">{{ $d->phone }}</small>@endif
          </td>
          <td><span style="font-size:12px;font-weight:500;">{{ $d->skin_type }}</span></td>
          <td><span style="font-size:12px;font-weight:500;text-transform:capitalize;">{{ str_replace('/', ' / ', $d->concern) }}</span></td>
          <td>
            @php 
              $severity = $d->analysis_result['severity'] ?? 'low'; 
              $color = $severity === 'high' ? '#c62828' : ($severity === 'medium' ? '#f59e0b' : '#2e7d32');
              $label = $severity === 'high' ? 'Sévère' : ($severity === 'medium' ? 'Modérée' : 'Légère');
            @endphp
            <span style="font-size:11px;font-weight:600;color:#fff;padding:3px 10px;border-radius:var(--radius-full);background:{{ $color }};">
              {{ $label }}
            </span>
          </td>
          <td style="font-size:12px;color:var(--color-text-muted);">{{ $d->created_at->format('d/m/Y H:i') }}</td>
          <td>
            <div style="display:flex;gap:8px;align-items:center;">
              <button onclick="viewDetails({{ json_encode($d) }})" class="btn-katuiscia" style="padding:4px 8px;font-size:11px;height:28px;">Consulter</button>
              <form method="POST" action="{{ route('admin.diagnostics.destroy', $d) }}" onsubmit="return confirm('Supprimer ce diagnostic définitivement ?')">
                @csrf @method('DELETE')
                <button type="submit" style="background:transparent;border:none;color:var(--color-error);cursor:pointer;padding:4px;" title="Supprimer">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun diagnostic trouvé.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:1.5rem;">
    {{ $diagnostics->links() }}
  </div>
</div>

{{-- MODAL DETAILS --}}
<dialog id="modal-diagnostic-details" class="admin-modal" style="max-width:650px;">
  <div class="admin-modal__content">
    <div class="admin-modal__header">
      <h2 style="font-family:var(--font-heading);font-size:var(--text-xl);">Détail du Diagnostic IA</h2>
      <button onclick="document.getElementById('modal-diagnostic-details').close()" class="admin-modal__close">&times;</button>
    </div>
    <div class="admin-modal__body" style="padding:1.5rem;">
      <div style="display:grid;grid-template-columns:160px 1fr;gap:1.5rem;margin-bottom:1.5rem;align-items:start;">
        <div>
          <a id="modal-selfie-link" href="#" target="_blank">
            <img id="modal-selfie" src="" style="width:160px;height:160px;border-radius:12px;object-fit:cover;border:1px solid var(--color-border);background:#faf7f2;">
          </a>
          <p style="font-size:10px;color:var(--color-text-light);text-align:center;margin-top:4px;">(Cliquez pour agrandir)</p>
        </div>
        <div>
          <h3 style="font-family:var(--font-heading);font-size:1.15rem;margin:0 0 4px;" id="modal-name">Marie Dupont</h3>
          <p style="font-size:12px;color:var(--color-text-muted);margin-bottom:12px;" id="modal-contact">marie@exemple.com</p>

          <table style="width:100%;font-size:12px;border-collapse:collapse;">
            <tr style="border-bottom:1px solid #f3eade;"><td style="padding:4px 0;font-weight:600;color:var(--color-dark);">Type de peau :</td><td id="modal-skin-type" style="text-align:right;">Grasse</td></tr>
            <tr style="border-bottom:1px solid #f3eade;"><td style="padding:4px 0;font-weight:600;color:var(--color-dark);">Préoccupation :</td><td id="modal-concern" style="text-align:right;text-transform:capitalize;">Acné</td></tr>
            <tr style="border-bottom:1px solid #f3eade;"><td style="padding:4px 0;font-weight:600;color:var(--color-dark);">Gravité IA :</td><td id="modal-severity" style="text-align:right;">Low</td></tr>
            <tr style="border-bottom:1px solid #f3eade;"><td style="padding:4px 0;font-weight:600;color:var(--color-dark);">Date :</td><td id="modal-date" style="text-align:right;">30/05/2026</td></tr>
          </table>
        </div>
      </div>

      <div style="background:var(--color-cream);padding:1rem;border-radius:10px;margin-bottom:1.5rem;border:1px solid #ede4db;">
        <h4 style="font-size:12px;font-weight:700;color:var(--color-dark);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;">🧼 Produits actuellement utilisés</h4>
        <p id="modal-current-products" style="font-size:12px;color:var(--color-text-light);line-height:1.5;margin:0;"></p>
      </div>

      <div style="margin-bottom:1.5rem;">
        <h4 style="font-size:12px;font-weight:700;color:var(--color-dark);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid var(--color-border);padding-bottom:4px;">🔍 Analyse dermatologique IA</h4>
        <p id="modal-analysis-text" style="font-size:12px;color:var(--color-text);line-height:1.55;white-space:pre-wrap;"></p>
      </div>

      <div style="margin-bottom:1.5rem;">
        <h4 style="font-size:12px;font-weight:700;color:var(--color-dark);margin-bottom:8px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid var(--color-border);padding-bottom:4px;">🌿 Routine conseillée</h4>
        <div id="modal-recommended-products" style="display:flex;flex-direction:column;gap:8px;"></div>
      </div>

      <div>
        <h4 style="font-size:12px;font-weight:700;color:var(--color-dark);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid var(--color-border);padding-bottom:4px;">💡 Conseils & Rituels</h4>
        <p id="modal-advice" style="font-size:12px;color:var(--color-text);line-height:1.55;white-space:pre-wrap;background:var(--color-gray);padding:1rem;border-radius:8px;"></p>
      </div>
    </div>
  </div>
</dialog>

<script>
function filterTable(val) {
  var s = val.toLowerCase().trim();
  document.querySelectorAll('#diagnostics-table-body tr').forEach(row => {
    var name = row.getAttribute('data-name');
    var email = row.getAttribute('data-email');
    var concern = row.getAttribute('data-concern');
    if (name.includes(s) || email.includes(s) || concern.includes(s)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}

function viewDetails(diag) {
  document.getElementById('modal-selfie').src = '{{ asset('') }}' + diag.image_path;
  document.getElementById('modal-selfie-link').href = '{{ asset('') }}' + diag.image_path;
  document.getElementById('modal-name').textContent = diag.name;
  document.getElementById('modal-contact').textContent = diag.email + (diag.phone ? ' • ' + diag.phone : '');
  
  // Format Date
  var d = new Date(diag.created_at);
  var formattedDate = d.getDate().toString().padStart(2, '0') + '/' + (d.getMonth() + 1).toString().padStart(2, '0') + '/' + d.getFullYear() + ' à ' + d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
  document.getElementById('modal-date').textContent = formattedDate;

  document.getElementById('modal-skin-type').textContent = diag.skin_type;
  document.getElementById('modal-concern').textContent = diag.concern.replace('/', ' / ');

  // Severity
  var severity = diag.analysis_result.severity || 'low';
  var severityLabels = { low: 'Légère', medium: 'Modérée', high: 'Sévère' };
  document.getElementById('modal-severity').textContent = severityLabels[severity] || severity;
  
  document.getElementById('modal-current-products').textContent = diag.current_products || 'Aucun produit spécifié';
  document.getElementById('modal-analysis-text').textContent = diag.analysis_result.analysis_details;
  document.getElementById('modal-advice').textContent = diag.analysis_result.general_advice;

  // Recommended products list
  var productsContainer = document.getElementById('modal-recommended-products');
  productsContainer.innerHTML = '';
  diag.analysis_result.recommended_products.forEach(p => {
    productsContainer.innerHTML += `
      <div style="background:#fff;border:1px solid #ede4db;border-radius:8px;padding:8px 12px;font-size:12px;">
        <strong>ID: ${p.product_id} • ${p.product_name}</strong>
        <p style="color:var(--color-text-light);margin-top:2px;font-size:11px;line-height:1.45;">${p.reason}</p>
      </div>
    `;
  });

  document.getElementById('modal-diagnostic-details').showModal();
}

document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-diagnostics') l.classList.add('active'); });
</script>
@endsection
