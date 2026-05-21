@extends('layouts.admin')

@section('title', 'Marketing & Funnels')

@section('content')
<main class="dashboard-main">
    <header class="dashboard-header">
      <div style="display:flex; align-items:center; gap:16px;">
        <button class="mobile-toggle" id="mobileToggle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
        <h2 style="font-family:var(--font-heading); font-size:var(--text-lg);">Marketing & Tunnels de Vente</h2>
      </div>
      <div class="header-actions">
        <button class="btn-primary" onclick="document.getElementById('modal-campagne').showModal()">+ Nouvelle Campagne</button>
        <div style="width:36px; height:36px; border-radius:50%; background:var(--color-dark); color:var(--color-white); display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:bold;">AD</div>
      </div>
    </header>

    <div class="dashboard-content">
      <h1 class="page-title">Tunnels de Vente & Campagnes</h1>
      <p class="page-subtitle">Créez vos séquences emails, relancez les paniers abandonnés et gérez vos campagnes.</p>

      <!-- Stats -->
      <div class="stats-grid" style="grid-template-columns:repeat(4, 1fr);">
        <div class="card stat-card"><span class="stat-title">Abonnés Newsletter</span><span class="stat-value">1 850</span><span class="stat-change positive">+120 ce mois</span></div>
        <div class="card stat-card"><span class="stat-title">Taux d'Ouverture</span><span class="stat-value">42%</span><span class="stat-change positive">+3% vs mois dernier</span></div>
        <div class="card stat-card"><span class="stat-title">Paniers Récupérés</span><span class="stat-value">28</span><span class="stat-change positive">3 450 € récupérés</span></div>
        <div class="card stat-card"><span class="stat-title">Campagnes Actives</span><span class="stat-value">3</span></div>
      </div>

      <!-- Segments -->
      <div class="card" style="margin-bottom:var(--space-xl);">
        <h3 style="font-family:var(--font-heading); font-size:var(--text-xl); margin-bottom:var(--space-lg);">Segments de Contacts</h3>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:var(--space-md);">
          <div style="padding:var(--space-lg); border:1px solid var(--color-border); border-radius:var(--radius-md); text-align:center;">
            <div style="font-size:24px; margin-bottom:8px;">👑</div>
            <strong style="font-size:var(--text-sm);">Clients VIP</strong>
            <div style="color:var(--color-text-muted); font-size:12px; margin-top:4px;">124 contacts</div>
          </div>
          <div style="padding:var(--space-lg); border:1px solid var(--color-border); border-radius:var(--radius-md); text-align:center;">
            <div style="font-size:24px; margin-bottom:8px;">🛒</div>
            <strong style="font-size:var(--text-sm);">Paniers Abandonnés</strong>
            <div style="color:var(--color-text-muted); font-size:12px; margin-top:4px;">45 contacts</div>
          </div>
          <div style="padding:var(--space-lg); border:1px solid var(--color-border); border-radius:var(--radius-md); text-align:center;">
            <div style="font-size:24px; margin-bottom:8px;">📧</div>
            <strong style="font-size:var(--text-sm);">Abonnés Newsletter</strong>
            <div style="color:var(--color-text-muted); font-size:12px; margin-top:4px;">1 850 contacts</div>
          </div>
          <div style="padding:var(--space-lg); border:1px solid var(--color-border); border-radius:var(--radius-md); text-align:center;">
            <div style="font-size:24px; margin-bottom:8px;">🆕</div>
            <strong style="font-size:var(--text-sm);">Nouveaux Inscrits</strong>
            <div style="color:var(--color-text-muted); font-size:12px; margin-top:4px;">89 contacts (30j)</div>
          </div>
          <div style="padding:var(--space-lg); border:2px dashed var(--color-border); border-radius:var(--radius-md); text-align:center; cursor:pointer; color:var(--color-text-muted);">
            <div style="font-size:24px; margin-bottom:8px;">+</div>
            <strong style="font-size:var(--text-sm);">Nouveau Segment</strong>
          </div>
        </div>
      </div>

      <!-- Séquences automatisées -->
      <div class="card" style="margin-bottom:var(--space-xl);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:var(--space-lg);">
          <h3 style="font-family:var(--font-heading); font-size:var(--text-xl);">Séquences Automatisées</h3>
        </div>
        <div class="card" style="padding:0; overflow:hidden; box-shadow:none;">
          <table class="admin-table">
            <thead><tr><th>Séquence</th><th>Emails</th><th>Segment</th><th>Taux Ouverture</th><th>Conversions</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody>
              <tr>
                <td><strong>Relance Panier Abandonné</strong><br><small style="color:var(--color-text-muted);">H+1, J+1, J+3 avec promo -10%</small></td>
                <td>3</td>
                <td>Paniers Abandonnés</td>
                <td>58%</td>
                <td><strong>28</strong> (62%)</td>
                <td><span class="status-badge status-badge--success">Actif</span></td>
                <td><div style="display:flex; gap:6px;"><button class="action-btn" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px; height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button><button class="action-btn action-btn--danger" title="Pause"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px; height:14px;"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg></button></div></td>
              </tr>
              <tr>
                <td><strong>Séquence de Bienvenue</strong><br><small style="color:var(--color-text-muted);">Email de bienvenue + présentation marque</small></td>
                <td>1</td>
                <td>Nouveaux Inscrits</td>
                <td>72%</td>
                <td><strong>45</strong> (51%)</td>
                <td><span class="status-badge status-badge--success">Actif</span></td>
                <td><div style="display:flex; gap:6px;"><button class="action-btn" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px; height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button><button class="action-btn action-btn--danger" title="Pause"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px; height:14px;"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg></button></div></td>
              </tr>
              <tr>
                <td><strong>Réactivation Clients Inactifs</strong><br><small style="color:var(--color-text-muted);">Offre -15% pour clients > 60j sans achat</small></td>
                <td>2</td>
                <td>—</td>
                <td>—</td>
                <td>—</td>
                <td><span class="status-badge status-badge--muted">Brouillon</span></td>
                <td><div style="display:flex; gap:6px;"><button class="action-btn" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px; height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button><button class="action-btn action-btn--danger" title="Supprimer"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px; height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></div></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Campagnes ponctuelles -->
      <div class="card">
        <h3 style="font-family:var(--font-heading); font-size:var(--text-xl); margin-bottom:var(--space-lg);">Campagnes Ponctuelles</h3>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:var(--space-lg);">
          <div style="border:1px solid var(--color-border); border-radius:var(--radius-md); padding:var(--space-lg);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:var(--space-sm);">
              <span class="status-badge status-badge--success">Envoyée</span>
              <small style="color:var(--color-text-muted);">28 Avr 2026</small>
            </div>
            <h4 style="font-family:var(--font-heading); margin-bottom:4px;">Offre Fête des Mères</h4>
            <p style="font-size:var(--text-sm); color:var(--color-text-muted); margin-bottom:var(--space-md);">-25% sur tous les coffrets cadeaux</p>
            <div style="display:flex; gap:var(--space-lg); font-size:12px; color:var(--color-text-muted);">
              <span>📨 1 245 envois</span>
              <span>👁 48% ouvert</span>
              <span>🛒 12% cliqué</span>
            </div>
          </div>
          <div style="border:1px solid var(--color-border); border-radius:var(--radius-md); padding:var(--space-lg);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:var(--space-sm);">
              <span class="status-badge status-badge--warning">Planifiée</span>
              <small style="color:var(--color-text-muted);">15 Mai 2026</small>
            </div>
            <h4 style="font-family:var(--font-heading); margin-bottom:4px;">Lancement Été 2026</h4>
            <p style="font-size:var(--text-sm); color:var(--color-text-muted); margin-bottom:var(--space-md);">Présentation de la collection estivale</p>
            <div style="display:flex; gap:var(--space-lg); font-size:12px; color:var(--color-text-muted);">
              <span>📨 1 850 destinataires</span>
              <span>⏰ Dans 9 jours</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal campagne -->
  <dialog id="modal-campagne" class="admin-modal" style="max-width:580px;">
    <div class="admin-modal__content">
      <div class="admin-modal__header">
        <h2 style="font-family:var(--font-heading); font-size:var(--text-xl);">Nouvelle Campagne</h2>
        <button onclick="document.getElementById('modal-campagne').close()" class="admin-modal__close">&times;</button>
      </div>
      <form class="admin-modal__body">
        <div class="admin-form-group">
          <label class="admin-label">Nom de la campagne *</label>
          <input type="text" class="admin-input" placeholder="Ex: Offre de Printemps" required>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Type</label>
          <select class="admin-input"><option>Email ponctuel</option><option>Séquence automatisée</option></select>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Segment cible</label>
          <select class="admin-input"><option>Tous les abonnés</option><option>Clients VIP</option><option>Paniers Abandonnés</option><option>Nouveaux Inscrits</option></select>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Objet de l'email</label>
          <input type="text" class="admin-input" placeholder="Ex: 🌸 Votre offre exclusive vous attend...">
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Date d'envoi</label>
          <input type="datetime-local" class="admin-input">
        </div>
        <div style="display:flex; gap:var(--space-sm); justify-content:flex-end; padding-top:var(--space-md);">
          <button type="button" onclick="document.getElementById('modal-campagne').close()" style="padding:10px 20px; border:1px solid var(--color-border); border-radius:var(--radius-sm); background:transparent; cursor:pointer; font-size:var(--text-sm);">Annuler</button>
          <button type="submit" class="btn-primary">Créer la campagne</button>
        </div>
      </form>
    </div>
  </dialog>

  
  
@endsection

@section('scripts')
<script>
    document.getElementById('mobileToggle')?.addEventListener('click', () => document.getElementById('sidebar').classList.toggle('open'));
    document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-funnels') l.classList.add('active'); });
  </script>
<script src="/js/account.js"></script>
@endsection
