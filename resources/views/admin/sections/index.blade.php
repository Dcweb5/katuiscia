@extends('layouts.admin')

@section('title', 'Gestion des Sections')

@section('content')
<main class="dashboard-main">
    <header class="dashboard-header">
      <div style="display:flex; align-items:center; gap:16px;">
        <button class="mobile-toggle" id="mobileToggle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
        <h2 style="font-family:var(--font-heading); font-size:var(--text-lg);">Sections de la Page d'Accueil</h2>
      </div>
      <div class="header-actions">
        <button class="btn-primary">Publier les Changements</button>
        <div style="width:36px; height:36px; border-radius:50%; background:var(--color-dark); color:var(--color-white); display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:bold;">AD</div>
      </div>
    </header>
    <div class="dashboard-content">
      <h1 class="page-title">Gestion des Sections</h1>
      <p class="page-subtitle" style="margin-bottom:var(--space-2xl);">Choisissez les produits affichés dans chaque section de la page d'accueil.</p>

      <div class="admin-tabs">
        <button class="admin-tab active" onclick="showSection('hero')">Hero Section</button>
        <button class="admin-tab" onclick="showSection('bestsellers')">Best-Sellers & Nouveautés</button>
        <button class="admin-tab" onclick="showSection('boutique')">Notre Boutique</button>
        <button class="admin-tab" onclick="showSection('selection')">Sélection Organisée</button>
      </div>

      <!-- HERO SECTION -->
      <div class="section-panel" id="panel-hero">
        <div class="card" style="margin-bottom:var(--space-xl);">
          <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-md);">Slides du Carrousel Hero</h3>
          <p style="font-size:var(--text-sm); color:var(--color-text-muted); margin-bottom:var(--space-lg);">Sélectionnez jusqu'à 5 produits à mettre en avant dans le diaporama principal.</p>
          <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:var(--space-md);">
            <div style="border:2px solid var(--color-warm); border-radius:var(--radius-md); overflow:hidden; position:relative;">
              <img src="{{ asset('assets/images/product-1a.webp') }}" alt="" style="width:100%; height:160px; object-fit:cover;">
              <div style="padding:10px;"><strong style="font-size:var(--text-sm);">Nectar Lumineux</strong><br><small style="color:var(--color-text-muted);">Slide 1</small></div>
              <button class="action-btn action-btn--danger" style="position:absolute; top:8px; right:8px; width:24px; height:24px; background:white;">&times;</button>
            </div>
            <div style="border:2px solid var(--color-warm); border-radius:var(--radius-md); overflow:hidden; position:relative;">
              <img src="{{ asset('assets/images/product-6a.webp') }}" alt="" style="width:100%; height:160px; object-fit:cover;">
              <div style="padding:10px;"><strong style="font-size:var(--text-sm);">Sérum Éclat</strong><br><small style="color:var(--color-text-muted);">Slide 2</small></div>
              <button class="action-btn action-btn--danger" style="position:absolute; top:8px; right:8px; width:24px; height:24px; background:white;">&times;</button>
            </div>
            <div style="border:2px solid var(--color-warm); border-radius:var(--radius-md); overflow:hidden; position:relative;">
              <img src="{{ asset('assets/images/product-3a.webp') }}" alt="" style="width:100%; height:160px; object-fit:cover;">
              <div style="padding:10px;"><strong style="font-size:var(--text-sm);">Gommage Terracotta</strong><br><small style="color:var(--color-text-muted);">Slide 3</small></div>
              <button class="action-btn action-btn--danger" style="position:absolute; top:8px; right:8px; width:24px; height:24px; background:white;">&times;</button>
            </div>
            <div style="border:2px dashed var(--color-border); border-radius:var(--radius-md); display:flex; align-items:center; justify-content:center; min-height:200px; cursor:pointer;">
              <div style="text-align:center; color:var(--color-text-muted);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:28px; height:28px; margin:0 auto 8px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span style="font-size:var(--text-sm);">Ajouter un produit</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- BEST-SELLERS (hidden by default) -->
      <div class="section-panel" id="panel-bestsellers" style="display:none;">
        <div class="card">
          <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-md);">Nos Best-Sellers & Nouveautés</h3>
          <p style="font-size:var(--text-sm); color:var(--color-text-muted); margin-bottom:var(--space-lg);">Produits affichés dans la grille Best-Sellers de la page d'accueil.</p>
          <table class="admin-table">
            <thead><tr><th>Produit</th><th>Catégorie</th><th>Prix</th><th>Badge</th><th>Actions</th></tr></thead>
            <tbody>
              <tr><td style="display:flex; align-items:center; gap:12px;"><img src="{{ asset('assets/images/product-1a.webp') }}" style="width:40px; height:40px; border-radius:8px; object-fit:cover;">Nectar Lumineux</td><td>Soin</td><td>125 €</td><td><span class="status-badge status-badge--success">Best-seller</span></td><td><button class="action-btn action-btn--danger" title="Retirer">&times;</button></td></tr>
              <tr><td style="display:flex; align-items:center; gap:12px;"><img src="{{ asset('assets/images/product-6a.webp') }}" style="width:40px; height:40px; border-radius:8px; object-fit:cover;">Sérum Éclat</td><td>Soin</td><td>110 €</td><td><span class="status-badge status-badge--success">Best-seller</span></td><td><button class="action-btn action-btn--danger" title="Retirer">&times;</button></td></tr>
              <tr><td style="display:flex; align-items:center; gap:12px;"><img src="{{ asset('assets/images/product-3a.webp') }}" style="width:40px; height:40px; border-radius:8px; object-fit:cover;">Gommage Terracotta</td><td>Soin</td><td>85 €</td><td><span class="status-badge status-badge--info">Nouveau</span></td><td><button class="action-btn action-btn--danger" title="Retirer">&times;</button></td></tr>
            </tbody>
          </table>
          <button style="margin-top:var(--space-lg); padding:8px 16px; border:1px dashed var(--color-border); border-radius:var(--radius-sm); background:none; cursor:pointer; font-size:var(--text-sm); color:var(--color-text-muted);">+ Ajouter un produit</button>
        </div>
      </div>

      <!-- BOUTIQUE (hidden) -->
      <div class="section-panel" id="panel-boutique" style="display:none;">
        <div class="card">
          <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-lg);">Notre Boutique — Grille de produits</h3>
          <p style="font-size:var(--text-sm); color:var(--color-text-muted); margin-bottom:var(--space-lg);">Produits affichés dans la section « Notre Boutique » de la page d'accueil (grille 2×2).</p>
          <p style="font-size:var(--text-sm); color:var(--color-text-light);">Configuration identique à Best-Sellers. Sélectionnez 4 produits maximum.</p>
        </div>
      </div>

      <!-- SÉLECTION (hidden) -->
      <div class="section-panel" id="panel-selection" style="display:none;">
        <div class="card">
          <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-md);">Sélection Organisée — Collections</h3>
          <p style="font-size:var(--text-sm); color:var(--color-text-muted); margin-bottom:var(--space-lg);">Cette section affiche les collections de produits (packs). Gérez vos collections depuis la page <a href="{{ url('admin-collections') }}" style="color:var(--color-warm); font-weight:500;">Collections</a>.</p>
          <p style="font-size:var(--text-sm); color:var(--color-text-light);">Les collections actives s'afficheront automatiquement dans la section « Sélection Organisée » de la page d'accueil.</p>
        </div>
      </div>
    </div>
  </main>
  
  
@endsection

@section('scripts')
<script>
    document.getElementById('mobileToggle')?.addEventListener('click', () => document.getElementById('sidebar').classList.toggle('open'));
    document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-sections') l.classList.add('active'); });
    function showSection(name) {
      document.querySelectorAll('.section-panel').forEach(p => p.style.display = 'none');
      document.getElementById('panel-' + name).style.display = 'block';
      document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
      event.target.classList.add('active');
    }
  </script>
<script src="/js/account.js"></script>
@endsection
