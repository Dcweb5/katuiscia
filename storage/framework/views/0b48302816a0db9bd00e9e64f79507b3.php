<?php $__env->startSection('title', 'Coupons & Promotions'); ?>
<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);">
    <div>
      <h1 class="page-title">Coupons & Promotions</h1>
      <p class="page-subtitle">Gérez vos codes promo et offres spéciales.</p>
    </div>
    <a href="<?php echo e(route('admin.coupons.create')); ?>" class="btn-primary" style="text-decoration:none;">+ Nouveau coupon</a>
  </div>

  <?php if(session('success')): ?>
  <div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);"><?php echo e(session('success')); ?></div>
  <?php endif; ?>

  <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:var(--space-xl);">
    <div class="card stat-card"><span class="stat-title">Total</span><span class="stat-value"><?php echo e($stats['total']); ?></span></div>
    <div class="card stat-card"><span class="stat-title">Actifs</span><span class="stat-value"><?php echo e($stats['active']); ?></span></div>
    <div class="card stat-card"><span class="stat-title">Utilisations</span><span class="stat-value"><?php echo e($stats['used']); ?></span></div>
  </div>

  <form method="GET" style="display:flex;gap:var(--space-md);margin-bottom:var(--space-lg);flex-wrap:wrap;">
    <div class="header-search" style="flex:1;min-width:200px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="search" placeholder="Rechercher un code..." value="<?php echo e(request('search')); ?>" style="border:none;background:transparent;padding:8px 12px;font-size:var(--text-sm);outline:none;width:100%;">
    </div>
    <select name="type" class="admin-input" style="width:auto;padding:8px 16px;" onchange="this.form.submit()">
      <option value="">Tous types</option><option value="percentage" <?php if(request('type')=='percentage'): echo 'selected'; endif; ?>>Pourcentage</option><option value="fixed" <?php if(request('type')=='fixed'): echo 'selected'; endif; ?>>Fixe</option><option value="free_shipping" <?php if(request('type')=='free_shipping'): echo 'selected'; endif; ?>>Livraison gratuite</option>
    </select>
    <?php if(request()->anyFilled(['search','type'])): ?>
    <a href="<?php echo e(url('admin/coupons')); ?>" class="text-xs text-text-muted hover:text-dark" style="white-space:nowrap;">Réinitialiser</a>
    <?php endif; ?>
  </form>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead><tr><th>Code</th><th>Type</th><th>Valeur</th><th>Min. commande</th><th>Utilisations</th><th>Dernière util.</th><th>Expire le</th><th>Statut</th><th>Actions</th></tr></thead>
      <tbody>
        <?php $__empty_2 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <tr>
          <td><strong style="font-family:monospace;font-size:var(--text-md);"><?php echo e($c->code); ?></strong></td>
          <td><?php echo e($c->type === 'percentage' ? '%' : ($c->type === 'fixed' ? '€' : '🚚')); ?></td>
          <td><?php echo e($c->type === 'free_shipping' ? '—' : $c->value); ?></td>
          <td><?php echo e($c->min_order_amount ? number_format($c->min_order_amount,0,',',' ') . ' €' : '—'); ?></td>
          <td><?php echo e($c->used_count); ?> / <?php echo e($c->max_uses ?? '∞'); ?></td>
          <td><?php echo e($c->last_used_at ? $c->last_used_at->format('d/m/Y H:i') : '—'); ?></td>
          <td><?php echo e($c->expires_at ? $c->expires_at->format('d/m/Y') : '—'); ?></td>
          <td><?php if($c->is_active): ?><span class="status-badge status-badge--success">Actif</span><?php else: ?><span style="background:var(--color-gray-medium);color:var(--color-text-muted);padding:2px 10px;border-radius:var(--radius-full);font-size:10px;">Inactif</span><?php endif; ?></td>
          <td>
            <div style="display:flex;gap:4px;">
              <a href="<?php echo e(route('admin.coupons.edit', $c)); ?>" class="action-btn" title="Modifier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
              <form method="POST" action="<?php echo e(route('admin.coupons.destroy', $c)); ?>" onsubmit="return confirm('Supprimer ce coupon ?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="action-btn action-btn--danger" title="Supprimer"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <tr><td colspan="9" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun coupon. <a href="<?php echo e(route('admin.coupons.create')); ?>" style="color:var(--color-warm);">Créer le premier</a>.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php echo e($coupons->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\coupons\index.blade.php ENDPATH**/ ?>