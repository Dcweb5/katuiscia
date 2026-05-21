<?php $__env->startSection('title', 'Gestion des Commandes'); ?>
<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);">
    <div><h1 class="page-title">Gestion des Commandes</h1><p class="page-subtitle">Suivez et gérez toutes les commandes.</p></div>
    <span style="font-size:var(--text-xs);color:var(--color-text-muted);"><?php echo e($stats['total']); ?> commande(s)</span>
  </div>
  <?php if(session('success')): ?><div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);"><?php echo e(session('success')); ?></div><?php endif; ?>

  <!-- Stats -->
  <div class="stats-grid" style="grid-template-columns:repeat(6,1fr);margin-bottom:var(--space-xl);">
    <div class="card stat-card"><span class="stat-title">Total</span><span class="stat-value"><?php echo e($stats['total']); ?></span></div>
    <div class="card stat-card"><span class="stat-title">En attente</span><span class="stat-value" style="color:var(--color-warm);"><?php echo e($stats['pending']); ?></span></div>
    <div class="card stat-card"><span class="stat-title">Confirmées</span><span class="stat-value"><?php echo e($stats['confirmed']); ?></span></div>
    <div class="card stat-card"><span class="stat-title">En prépa</span><span class="stat-value"><?php echo e($stats['preparing']); ?></span></div>
    <div class="card stat-card"><span class="stat-title">Expédiées</span><span class="stat-value"><?php echo e($stats['shipped']); ?></span></div>
    <div class="card stat-card"><span class="stat-title">Livrées</span><span class="stat-value" style="color:var(--color-success);"><?php echo e($stats['delivered']); ?></span></div>
  </div>

  <!-- Filtres -->
  <form method="GET" style="display:flex;gap:var(--space-md);margin-bottom:var(--space-lg);flex-wrap:wrap;">
    <div class="header-search" style="flex:1;min-width:200px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><input type="text" name="search" placeholder="Rechercher (nº, nom, email)..." value="<?php echo e(request('search')); ?>" style="border:none;background:transparent;padding:8px 12px;font-size:var(--text-sm);outline:none;width:100%;"></div>
    <select name="status" class="admin-input" style="width:auto;padding:8px 16px;" onchange="this.form.submit()">
      <option value="">Tous statuts</option>
      <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>" <?php if(request('status')==$key): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <?php if(request()->anyFilled(['search','status'])): ?><a href="<?php echo e(url('admin/commandes')); ?>" class="text-xs text-text-muted hover:text-dark">Réinitialiser</a><?php endif; ?>
  </form>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead><tr><th>Nº</th><th>Client</th><th>Date</th><th>Articles</th><th>Total</th><th>Statut</th><th>Actions</th></tr></thead>
      <tbody>
        <?php $__empty_2 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
        <tr>
          <td><strong style="font-family:monospace;"><?php echo e($o->order_number); ?></strong></td>
          <td><?php echo e($o->firstname); ?> <?php echo e($o->lastname); ?><br><small style="color:var(--color-text-muted);"><?php echo e($o->email); ?></small></td>
          <td><?php echo e($o->created_at->format('d/m/Y H:i')); ?></td>
          <td><?php echo e($o->items->count()); ?></td>
          <td><strong><?php echo e(number_format($o->total, 2, ',', ' ')); ?> €</strong><?php if($o->discount > 0): ?><br><small style="color:var(--color-success);">-<?php echo e(number_format($o->discount, 0)); ?>€</small><?php endif; ?></td>
          <td>
            <?php $colors = ['pending'=>'var(--color-warm)','confirmed'=>'#3b82f6','preparing'=>'#f59e0b','shipped'=>'#8b5cf6','delivered'=>'var(--color-success)','cancelled'=>'var(--color-error)']; ?>
            <span class="status-badge" style="background:<?php echo e($colors[$o->status] ?? '#ccc'); ?>;color:white;font-size:10px;font-weight:600;"><?php echo e($statuses[$o->status] ?? $o->status); ?></span>
          </td>
          <td><a href="<?php echo e(route('admin.orders.show', $o)); ?>" class="action-btn" title="Voir"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
        <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucune commande.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php echo e($orders->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\orders\index.blade.php ENDPATH**/ ?>