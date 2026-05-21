<!-- USER SIDEBAR (Blade) -->
<aside class="dashboard-sidebar" id="sidebar">
  <div class="sidebar-header">
    <a href="<?php echo e(url('compte')); ?>" style="display:flex; align-items:center; text-decoration:none;">
      <img src="<?php echo e(asset('assets/images/K LOGO.png')); ?>" alt="Katuiscia" class="sidebar-logo-full">
      <img src="<?php echo e(asset('assets/images/K ICONE.png')); ?>" alt="K" class="sidebar-logo-icon-mobile">
    </a>
  </div>
  
  <nav class="sidebar-nav">
    <a href="<?php echo e(url('compte')); ?>" class="sidebar-link" data-page="compte">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      Vue d'ensemble
    </a>
    <a href="<?php echo e(url('compte/commandes')); ?>" class="sidebar-link" data-page="compte-commandes">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      Mes Commandes
    </a>
    <a href="<?php echo e(url('compte/retours')); ?>" class="sidebar-link" data-page="compte-retours">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
      Retours & Échanges
    </a>
    <a href="<?php echo e(url('compte/recompenses')); ?>" class="sidebar-link" data-page="compte-recompenses">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
      Mes Récompenses
    </a>
    <a href="<?php echo e(url('compte/avis')); ?>" class="sidebar-link" data-page="compte-avis">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
      Mes Avis
    </a>
  </nav>
  
  <div class="sidebar-footer">
    <a href="<?php echo e(url('/')); ?>" class="sidebar-link">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      Retour à la boutique
    </a>
    <form method="POST" action="<?php echo e(url('deconnexion')); ?>">
      <?php echo csrf_field(); ?>
      <button type="submit" class="sidebar-link" style="color:var(--color-error); font-size:12px; width:100%; text-align:left; border:none; background:none; cursor:pointer; padding:0; font-family:inherit; display:flex; align-items:center; gap:12px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Déconnexion
      </button>
    </form>
    <div class="sidebar-admin-badge">
      <div class="sidebar-admin-avatar" style="background:var(--color-warm);">
        <?php echo e(substr(Auth::user()->firstname ?? 'U', 0, 1)); ?><?php echo e(substr(Auth::user()->lastname ?? '', 0, 1)); ?>

      </div>
      <div class="sidebar-admin-info">
        <span class="sidebar-admin-name"><?php echo e(Auth::user()->firstname ?? ''); ?> <?php echo e(Auth::user()->lastname ?? ''); ?></span>
        <span class="sidebar-admin-role">✨ <?php echo e(number_format(Auth::user()->loyalty_points ?? 0, 0, ',', ' ')); ?> pts</span>
      </div>
    </div>
  </div>
</aside>
<?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\components\user-sidebar.blade.php ENDPATH**/ ?>