<!-- ADMIN SIDEBAR (Blade) -->
<aside class="dashboard-sidebar" id="sidebar">
  <div class="sidebar-header">
    <a href="{{ url('admin') }}" style="display:flex; align-items:center; gap:var(--space-sm); text-decoration:none;">
      <img src="{{ asset('assets/images/K ICONE.png') }}" alt="Katuiscia" class="sidebar-logo-icon sidebar-logo-mobile">
      <span class="sidebar-logo-text" style="font-family:var(--font-display); font-size:1.4rem; color:var(--color-dark); letter-spacing:2px;">KATUISCIA</span>
    </a>
  </div>
  
  <nav class="sidebar-nav">
    <a href="{{ url('admin') }}" class="sidebar-link" data-page="admin">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Vue d'ensemble
    </a>
    <a href="{{ url('admin/produits') }}" class="sidebar-link" data-page="admin-produits">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
      Produits
    </a>
    <a href="{{ url('admin/categories') }}" class="sidebar-link" data-page="admin-categories">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/></svg>
      Catégories
    </a>
    <a href="{{ url('admin/collections') }}" class="sidebar-link" data-page="admin-collections">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
      Collections
    </a>
    <a href="{{ url('admin/sections') }}" class="sidebar-link" data-page="admin-sections">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16"/><path d="M4 10h16"/><path d="M4 16h16"/><path d="M4 22h16"/></svg>
      Sections
    </a>
    <a href="{{ url('admin/blog') }}" class="sidebar-link" data-page="admin-blog">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
      Blog
    </a>
    <a href="{{ url('admin/commandes') }}" class="sidebar-link" data-page="admin-commandes">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M16 10a4 4 0 0 1-8 0"/><path d="M3 6h18"/></svg>
      Commandes
    </a>
    <a href="{{ url('admin/contacts') }}" class="sidebar-link" data-page="admin-contacts">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Messages
    </a>
    <a href="{{ url('admin/chat') }}" class="sidebar-link" data-page="admin-messages">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
      Chatbot
    </a>
    <a href="{{ url('admin/reviews') }}" class="sidebar-link" data-page="admin-reviews">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
      Avis
    </a>
    <a href="{{ url('admin/rendezvous') }}" class="sidebar-link" data-page="admin-rendezvous">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Rendez-vous
    </a>
    <a href="{{ url('admin/retours') }}" class="sidebar-link" data-page="admin-retours">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="3 9 12 2 21 9"/><path d="M5 7v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7"/></svg>
      Retours
    </a>
    <a href="{{ url('admin/utilisateurs') }}" class="sidebar-link" data-page="admin-utilisateurs">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Utilisateurs
    </a>
    <a href="{{ url('admin/coupons') }}" class="sidebar-link" data-page="admin-coupons">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 12H4"/><path d="M20 12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2 2 2 0 0 1 2-2h12a2 2 0 0 1 2 2z"/><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"/></svg>
      Coupons
    </a>
    <a href="{{ url('admin/funnels') }}" class="sidebar-link" data-page="admin-funnels">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 3 2 3 10 12.46V19l4 2v-8.54L22 3z"/></svg>
      Marketing
    </a>
    <a href="{{ url('admin/finances') }}" class="sidebar-link" data-page="admin-finances">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      Finances
    </a>
    <a href="{{ url('admin/analytics') }}" class="sidebar-link" data-page="admin-analytics">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
      Analytics
    </a>

    <!-- Déconnexion admin -->
    <form method="POST" action="{{ url('deconnexion') }}" style="margin-top:auto;">
      @csrf
      <button type="submit" class="sidebar-link" style="color:var(--color-error); font-size:12px; width:100%; text-align:left; border:none; background:none; cursor:pointer; padding:8px 24px; font-family:inherit; display:flex; align-items:center; gap:12px; border-radius:8px; transition:background 0.2s;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Déconnexion
      </button>
    </form>
  </nav>
  
  <div class="sidebar-footer">
    <a href="{{ url('/') }}" class="sidebar-link" target="_blank">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      Voir le site
    </a>
    <div class="sidebar-admin-badge">
      <div class="sidebar-admin-avatar" style="background:var(--color-warm);">
        {{ substr(Auth::user()->firstname ?? 'A', 0, 1) }}{{ substr(Auth::user()->lastname ?? 'D', 0, 1) }}
      </div>
      <div class="sidebar-admin-info">
        <span class="sidebar-admin-name">{{ Auth::user()->firstname ?? 'Admin' }} {{ Auth::user()->lastname ?? '' }}</span>
        <span class="sidebar-admin-role">Administrateur</span>
      </div>
    </div>
  </div>
</aside>
