<?php $__env->startSection('title', 'Gestion des Utilisateurs'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);">
    <div>
      <h1 class="page-title">Gestion des Utilisateurs</h1>
      <p class="page-subtitle">Consultez, filtrez et gérez tous les comptes utilisateurs.</p>
    </div>
    <span style="font-size:var(--text-xs);color:var(--color-text-muted);"><?php echo e($stats['total']); ?> utilisateur(s)</span>
  </div>

  <?php if(session('success')): ?>
  <div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);"><?php echo e(session('success')); ?></div>
  <?php endif; ?>
  <?php if(session('error')): ?>
  <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:var(--space-lg);"><?php echo e(session('error')); ?></div>
  <?php endif; ?>

  <!-- Stats -->
  <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:var(--space-xl);">
    <div class="card stat-card">
      <span class="stat-title">Total</span>
      <span class="stat-value"><?php echo e($stats['total']); ?></span>
    </div>
    <div class="card stat-card">
      <span class="stat-title">Admins</span>
      <span class="stat-value"><?php echo e($stats['admins']); ?></span>
    </div>
    <div class="card stat-card">
      <span class="stat-title">Actifs</span>
      <span class="stat-value"><?php echo e($stats['active']); ?></span>
    </div>
    <div class="card stat-card">
      <span class="stat-title">Newsletter</span>
      <span class="stat-value"><?php echo e($stats['newsletter']); ?></span>
    </div>
  </div>

  <!-- Filtres -->
  <form method="GET" style="display:flex;gap:var(--space-md);align-items:center;margin-bottom:var(--space-lg);flex-wrap:wrap;">
    <div class="header-search" style="flex:1;min-width:200px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="search" placeholder="Rechercher (nom, email)..." value="<?php echo e(request('search')); ?>" style="border:none;background:transparent;padding:8px 12px;font-size:var(--text-sm);outline:none;width:100%;">
    </div>
    <select name="role" class="admin-input" style="width:auto;padding:8px 16px;" onchange="this.form.submit()">
      <option value="">Tous les rôles</option>
      <option value="admin" <?php if(request('role')=='admin'): echo 'selected'; endif; ?>>Admin</option>
      <option value="client" <?php if(request('role')=='client'): echo 'selected'; endif; ?>>Client</option>
    </select>
    <select name="status" class="admin-input" style="width:auto;padding:8px 16px;" onchange="this.form.submit()">
      <option value="">Tous les statuts</option>
      <option value="active" <?php if(request('status')=='active'): echo 'selected'; endif; ?>>Actif</option>
      <option value="inactive" <?php if(request('status')=='inactive'): echo 'selected'; endif; ?>>Inactif</option>
    </select>
    <select name="country" class="admin-input" style="width:auto;padding:8px 16px;" onchange="this.form.submit()">
      <option value="">Tous les pays</option>
      <?php $__currentLoopData = $stats['countries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($code); ?>" <?php if(request('country')==$code): echo 'selected'; endif; ?>><?php echo e($code); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <?php if(request()->anyFilled(['search','role','status','country'])): ?>
    <a href="<?php echo e(url('admin/utilisateurs')); ?>" class="text-xs text-text-muted hover:text-dark" style="white-space:nowrap;">Réinitialiser</a>
    <?php endif; ?>
  </form>

  <!-- Table -->
  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th style="width:80px;">Pays</th>
          <th style="width:80px;">Points</th>
          <th style="width:90px;">Rôle</th>
          <th style="width:70px;">Statut</th>
          <th style="width:120px;">Inscrit le</th>
          <th style="width:120px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:12px;">
              <div style="width:36px;height:36px;border-radius:50%;background:<?php echo e($user->is_admin ? 'var(--color-dark)' : 'var(--color-warm)'); ?>;color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:bold;flex-shrink:0;">
                <?php echo e(substr($user->firstname ?? substr($user->email,0,1), 0, 1)); ?><?php echo e(substr($user->lastname ?? '', 0, 1)); ?>

              </div>
              <div>
                <strong style="font-size:var(--text-sm);"><?php echo e($user->firstname); ?> <?php echo e($user->lastname); ?></strong>
              </div>
            </div>
          </td>
          <td style="font-size:var(--text-sm);color:var(--color-text-muted);"><?php echo e($user->email); ?></td>
          <td style="font-size:var(--text-sm);"><?php echo e($user->country ?? '—'); ?></td>
          <td style="font-size:var(--text-sm);"><?php echo e(number_format($user->loyalty_points ?? 0, 0, ',', ' ')); ?></td>
          <td>
            <?php if($user->is_admin): ?>
            <span style="display:inline-block;padding:2px 10px;border-radius:var(--radius-full);font-size:10px;font-weight:600;background:rgba(61,43,43,0.1);color:var(--color-dark);">👑 Admin</span>
            <?php else: ?>
            <span style="display:inline-block;padding:2px 10px;border-radius:var(--radius-full);font-size:10px;background:var(--color-gray-k);color:var(--color-text-muted);">👤 Client</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if($user->is_active): ?>
            <span class="status-badge status-badge--success">Actif</span>
            <?php else: ?>
            <span class="status-badge" style="background:var(--color-gray-medium);color:var(--color-text-muted);">Inactif</span>
            <?php endif; ?>
          </td>
          <td style="font-size:var(--text-xs);color:var(--color-text-muted);"><?php echo e($user->created_at->format('d/m/Y')); ?></td>
          <td>
            <div style="display:flex;gap:4px;flex-wrap:wrap;">
              <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="action-btn" title="Modifier">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>

              <form method="POST" action="<?php echo e(route('admin.users.toggle-active', $user)); ?>" style="display:inline;">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <button type="submit" class="action-btn" title="<?php echo e($user->is_active ? 'Désactiver' : 'Activer'); ?>" style="font-size:11px;">
                  <?php echo e($user->is_active ? '🔒' : '🔓'); ?>

                </button>
              </form>

              <?php if(!$user->is_admin || auth()->id() !== $user->id): ?>
              <form method="POST" action="<?php echo e(route('admin.users.toggle-admin', $user)); ?>" style="display:inline;">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <button type="submit" class="action-btn" title="<?php echo e($user->is_admin ? 'Retirer admin' : 'Promouvoir admin'); ?>">
                  <?php echo e($user->is_admin ? '⬇' : '⬆'); ?>

                </button>
              </form>
              <?php endif; ?>

              <?php if(auth()->id() !== $user->id): ?>
              <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" onsubmit="return confirm('Supprimer cet utilisateur définitivement ?')" style="display:inline;">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="action-btn action-btn--danger" title="Supprimer">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun utilisateur trouvé.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div style="margin-top:var(--space-xl);">
    <?php echo e($users->links()); ?>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\users\index.blade.php ENDPATH**/ ?>