@extends('layouts.admin')

@section('title', 'Gestion Fidélité & Récompenses')

@section('content')
<div class="dashboard-content">
  <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:var(--space-2xl);">
    <div>
      <h1 class="page-title">Fidélité & Récompenses</h1>
      <p class="page-subtitle">Configurez le programme de fidélité et suivez les échanges de points des clients.</p>
    </div>
    <span style="font-size:12px; color:var(--color-text-muted);">
      Statut global : 
      @if($settings['loyalty_enabled'])
        <span style="color:var(--color-success); font-weight:600;">● Actif</span>
      @else
        <span style="color:var(--color-error); font-weight:600;">● Suspendu</span>
      @endif
    </span>
  </div>

  @if(session('success'))
    <div style="padding:12px 16px; background:rgba(90,143,110,0.1); border:1px solid var(--color-success); border-radius:8px; color:var(--color-success); margin-bottom:var(--space-lg);">
      {{ session('success') }}
    </div>
  @endif

  <!-- Tabs Navigation -->
  <div class="filter-pills" style="margin-bottom:var(--space-xl); border-bottom:1px solid var(--color-border); padding-bottom:var(--space-sm);">
    <button onclick="switchTab('settings-tab')" class="filter-pill tab-btn active" id="btn-settings-tab">⚙️ Réglages généraux</button>
    <button onclick="switchTab('rules-tab')" class="filter-pill tab-btn" id="btn-rules-tab">🎁 Seuils & Récompenses</button>
    <button onclick="switchTab('exchanges-tab')" class="filter-pill tab-btn" id="btn-exchanges-tab">📋 Historique des Échanges</button>
  </div>

  <!-- Tab 1: Settings -->
  <div id="settings-tab" class="tab-content">
    <div class="card" style="max-width:800px; padding:var(--space-2xl);">
      <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-xl); color:var(--color-dark); border-bottom:1px solid var(--color-border); padding-bottom:var(--space-sm);">Paramètres Généraux du Programme</h3>
      
      <form action="{{ route('admin.rewards.update-settings') }}" method="POST">
        @csrf
        
        <!-- Enable Toggle -->
        <div style="margin-bottom:var(--space-xl);">
          <label style="display:block; font-weight:600; font-size:var(--text-sm); margin-bottom:var(--space-xs); color:var(--color-dark);">Statut du programme</label>
          <div style="display:flex; gap:var(--space-md); align-items:center;">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
              <input type="radio" name="loyalty_enabled" value="1" @checked($settings['loyalty_enabled']) style="accent-color:var(--color-warm);">
              <span>Activé (Visible par les clients)</span>
            </label>
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
              <input type="radio" name="loyalty_enabled" value="0" @checked(!$settings['loyalty_enabled']) style="accent-color:var(--color-warm);">
              <span>Suspendu (Affiche le message de maintenance)</span>
            </label>
          </div>
        </div>

        <!-- Point Configuration Fields -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:var(--space-lg); margin-bottom:var(--space-2xl);">
          <div>
            <label for="loyalty_points_per_euro" style="display:block; font-weight:600; font-size:var(--text-sm); margin-bottom:var(--space-xs); color:var(--color-dark);">Points par euro (€)</label>
            <input type="number" id="loyalty_points_per_euro" name="loyalty_points_per_euro" class="admin-input" value="{{ $settings['loyalty_points_per_euro'] }}" min="0" required style="width:100%;">
            <small style="color:var(--color-text-muted); display:block; margin-top:4px;">Ex: 1 = 1 point pour 1 € dépensé.</small>
          </div>

          <div>
            <label for="loyalty_points_per_review" style="display:block; font-weight:600; font-size:var(--text-sm); margin-bottom:var(--space-xs); color:var(--color-dark);">Points par avis validé</label>
            <input type="number" id="loyalty_points_per_review" name="loyalty_points_per_review" class="admin-input" value="{{ $settings['loyalty_points_per_review'] }}" min="0" required style="width:100%;">
            <small style="color:var(--color-text-muted); display:block; margin-top:4px;">Crédités lors de l'approbation d'un avis.</small>
          </div>

          <div>
            <label for="loyalty_points_for_signup" style="display:block; font-weight:600; font-size:var(--text-sm); margin-bottom:var(--space-xs); color:var(--color-dark);">Points de bienvenue</label>
            <input type="number" id="loyalty_points_for_signup" name="loyalty_points_for_signup" class="admin-input" value="{{ $settings['loyalty_points_for_signup'] }}" min="0" required style="width:100%;">
            <small style="color:var(--color-text-muted); display:block; margin-top:4px;">Offerts lors de la création d'un compte.</small>
          </div>
        </div>

        <button type="submit" class="btn-primary" style="padding:10px 24px;">Enregistrer les modifications</button>
      </form>
    </div>
  </div>

  <!-- Tab 2: Reward Rules -->
  <div id="rules-tab" class="tab-content" style="display:none;">
    <div style="display:grid; grid-template-columns:1fr 320px; gap:var(--space-2xl); align-items:start;">
      <!-- Existing Rules Table -->
      <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:var(--space-lg); border-bottom:1px solid var(--color-border); display:flex; justify-content:space-between; align-items:center;">
          <h3 style="font-family:var(--font-heading); font-size:var(--text-md); color:var(--color-dark); margin:0;">Seuils & Récompenses Configurés</h3>
        </div>
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom de la récompense</th>
              <th>Points requis</th>
              <th>Type de réduction</th>
              <th>Valeur</th>
              <th style="width:80px; text-align:center;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rules as $rule)
              <tr>
                <td>#{{ $rule['id'] ?? '—' }}</td>
                <td><strong style="color:var(--color-dark);">{{ $rule['name'] ?? 'Sans nom' }}</strong></td>
                <td><span style="font-weight:600; color:var(--color-warm);">{{ number_format($rule['points'] ?? 0, 0, ',', ' ') }} pts</span></td>
                <td>
                  @php
                    $typeLabels = [
                      'fixed' => 'Réduction fixe (€)',
                      'percent' => 'Pourcentage (%)',
                      'free_shipping' => 'Livraison offerte',
                    ];
                  @endphp
                  {{ $typeLabels[$rule['type'] ?? ''] ?? $rule['type'] ?? '—' }}
                </td>
                <td>
                  @if(($rule['type'] ?? '') === 'free_shipping')
                    —
                  @elseif(($rule['type'] ?? '') === 'percent')
                    {{ $rule['value'] ?? 0 }}%
                  @else
                    {{ number_format($rule['value'] ?? 0, 2, ',', ' ') }} €
                  @endif
                </td>
                <td style="text-align:center;">
                  <form action="{{ route('admin.rewards.delete-rule', $rule['id'] ?? 0) }}" method="POST" onsubmit="event.preventDefault(); showConfirm('Voulez-vous vraiment supprimer cette règle ? Les coupons déjà générés resteront valides.', () => this.submit());">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn action-btn--danger">×</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align:center; padding:var(--space-3xl); color:var(--color-text-muted);">
                  Aucun palier de récompense défini. Créez-en un à droite.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Add Rule Card -->
      <div class="card" style="padding:var(--space-xl);">
        <h3 style="font-family:var(--font-heading); font-size:var(--text-md); margin-bottom:var(--space-lg); color:var(--color-dark); border-bottom:1px solid var(--color-border); padding-bottom:var(--space-xs);">Nouvelle Règle</h3>
        
        <form action="{{ route('admin.rewards.add-rule') }}" method="POST">
          @csrf
          
          <div style="margin-bottom:var(--space-md);">
            <label for="rule-name" style="display:block; font-weight:600; font-size:var(--text-xs); margin-bottom:var(--space-xs); text-transform:uppercase; color:var(--color-dark);">Nom de la récompense</label>
            <input type="text" id="rule-name" name="name" class="admin-input" placeholder="Ex: 10 € de réduction" required style="width:100%;">
          </div>

          <div style="margin-bottom:var(--space-md);">
            <label for="rule-points" style="display:block; font-weight:600; font-size:var(--text-xs); margin-bottom:var(--space-xs); text-transform:uppercase; color:var(--color-dark);">Points requis</label>
            <input type="number" id="rule-points" name="points" class="admin-input" placeholder="1000" min="1" required style="width:100%;">
          </div>

          <div style="margin-bottom:var(--space-md);">
            <label for="rule-type" style="display:block; font-weight:600; font-size:var(--text-xs); margin-bottom:var(--space-xs); text-transform:uppercase; color:var(--color-dark);">Type de réduction</label>
            <select id="rule-type" name="type" class="admin-input" required style="width:100%;" onchange="adjustValueInput(this.value)">
              <option value="fixed">Réduction fixe (€)</option>
              <option value="percent">Pourcentage (%)</option>
              <option value="free_shipping">Livraison offerte</option>
            </select>
          </div>

          <div style="margin-bottom:var(--space-lg);" id="rule-value-container">
            <label for="rule-value" style="display:block; font-weight:600; font-size:var(--text-xs); margin-bottom:var(--space-xs); text-transform:uppercase; color:var(--color-dark);">Valeur</label>
            <input type="number" id="rule-value" name="value" class="admin-input" placeholder="10" min="0" step="0.01" required style="width:100%;">
          </div>

          <button type="submit" class="btn-primary" style="width:100%; padding:10px 0;">Créer la règle</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Tab 3: Exchange History -->
  <div id="exchanges-tab" class="tab-content" style="display:none;">
    <div class="card" style="padding:0; overflow:hidden;">
      <div style="padding:var(--space-lg); border-bottom:1px solid var(--color-border);">
        <h3 style="font-family:var(--font-heading); font-size:var(--text-md); color:var(--color-dark); margin:0;">Journal des Échanges et Utilisation des Coupons</h3>
      </div>
      
      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Date de l'échange</th>
              <th>Utilisateur</th>
              <th>Points dépensés</th>
              <th>Coupon généré</th>
              <th>Statut d'utilisation</th>
              <th>Date d'utilisation</th>
              <th>Commande associée</th>
            </tr>
          </thead>
          <tbody>
            @forelse($exchanges as $ex)
              @php
                $coupon = $ex->coupon;
                $linkedOrder = $coupon ? $orders->get($coupon->code) : null;
              @endphp
              <tr>
                <td style="color:var(--color-text-muted); font-size:var(--text-sm);">
                  {{ $ex->created_at->format('d/m/Y H:i') }}
                </td>
                <td style="font-size:var(--text-sm);">
                  @if($ex->user)
                    <strong style="color:var(--color-dark);">{{ $ex->user->firstname }} {{ $ex->user->lastname }}</strong>
                    <br><span style="font-size:11px; color:var(--color-text-muted);">{{ $ex->user->email }}</span>
                  @else
                    <span style="color:var(--color-text-muted);">Utilisateur supprimé</span>
                  @endif
                </td>
                <td>
                  <span style="color:var(--color-error); font-weight:600; font-family:var(--font-display);">
                    {{ $ex->points }} pts
                  </span>
                </td>
                <td>
                  @if($coupon)
                    <code style="background:var(--color-bg); border:1px solid var(--color-border); padding:3px 6px; border-radius:4px; font-family:monospace; font-weight:600; color:var(--color-dark);">
                      {{ $coupon->code }}
                    </code>
                    <br><span style="font-size:10px; color:var(--color-text-muted);">{{ $coupon->description }}</span>
                  @else
                    <span style="color:var(--color-text-muted); font-style:italic;">Coupon supprimé</span>
                  @endif
                </td>
                <td>
                  @if($coupon)
                    @if($coupon->used_count > 0)
                      <span class="status-badge status-badge--success">Utilisé</span>
                    @elseif($coupon->expires_at && now()->gt($coupon->expires_at))
                      <span class="status-badge status-badge--danger" style="background:#fce8e6; color:#a81e1e;">Expiré</span>
                    @else
                      <span class="status-badge status-badge--warning" style="background:#fff3cd; color:#856404;">Disponible</span>
                    @endif
                  @else
                    —
                  @endif
                </td>
                <td style="font-size:var(--text-sm); color:var(--color-text-muted);">
                  @if($coupon && $coupon->used_count > 0)
                    {{ $coupon->last_used_at ? $coupon->last_used_at->format('d/m/Y H:i') : ($linkedOrder ? $linkedOrder->created_at->format('d/m/Y H:i') : '—') }}
                  @else
                    —
                  @endif
                </td>
                <td>
                  @if($linkedOrder)
                    <a href="{{ route('admin.orders.show', $linkedOrder) }}" style="color:var(--color-warm); text-decoration:underline; font-weight:500; font-size:var(--text-sm);">
                      {{ $linkedOrder->order_number }}
                    </a>
                    <br><span style="font-size:10px; color:var(--color-text-muted);">Total : {{ number_format($linkedOrder->total, 2, ',', ' ') }} €</span>
                  @else
                    —
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align:center; padding:var(--space-3xl); color:var(--color-text-muted);">
                  Aucun échange enregistré dans l'historique.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      
      @if($exchanges->hasPages())
        <div style="padding:var(--space-md); border-top:1px solid var(--color-border);">
          {{ $exchanges->links() }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
// Sidebar active class setting
document.querySelectorAll('.sidebar-link[data-page]').forEach(l => {
    if(l.dataset.page === 'admin-rewards') l.classList.add('active');
});

// Tab Switch Functionality
function switchTab(tabId) {
    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(el => {
        el.style.display = 'none';
    });
    // Remove active class from buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show active tab
    document.getElementById(tabId).style.display = 'block';
    document.getElementById('btn-' + tabId).classList.add('active');
}

// Adjust value field display depending on Coupon Type selected
function adjustValueInput(type) {
    const container = document.getElementById('rule-value-container');
    const input = document.getElementById('rule-value');
    
    if (type === 'free_shipping') {
        container.style.display = 'none';
        input.value = '0';
        input.required = false;
    } else {
        container.style.display = 'block';
        input.required = true;
        if (type === 'percent') {
            input.placeholder = '15';
            input.max = '100';
        } else {
            input.placeholder = '10';
            input.removeAttribute('max');
        }
    }
}
</script>
@endsection
