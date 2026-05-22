<?php $__env->startSection('title', 'Produits'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:var(--space-2xl);">
    <div>
      <h1 class="page-title">Gestion des Produits</h1>
      <p class="page-subtitle">Ajoutez, modifiez et organisez tous les produits de la boutique.</p>
    </div>
    <a href="<?php echo e(route('admin.products.create')); ?>" class="btn-primary" style="text-decoration:none;">+ Nouveau Produit</a>
  </div>

  <?php if(session('success')): ?>
  <div style="padding:12px 16px; background:rgba(90,143,110,0.1); border:1px solid var(--color-success); border-radius:8px; color:var(--color-success); margin-bottom:var(--space-lg); font-size:14px;">
    <?php echo e(session('success')); ?>

  </div>
  <?php endif; ?>
  <?php if(session('error')): ?>
  <div style="padding:12px 16px; background:rgba(199,80,80,0.1); border:1px solid var(--color-error); border-radius:8px; color:var(--color-error); margin-bottom:var(--space-lg); font-size:14px;">
    <?php echo e(session('error')); ?>

  </div>
  <?php endif; ?>

  <div class="card" style="padding:0; overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width:40px;"><input type="checkbox"></th>
          <th>Produit</th>
          <th>SKU</th>
          <th>Catégorie</th>
          <th>Prix</th>
          <th>Stock</th>
          <th>Images</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr data-id="<?php echo e($product->id); ?>">
          <td><input type="checkbox"></td>
          <td>
            <div style="display:flex; align-items:center; gap:12px;">
              <?php if($product->images->where('is_primary', true)->first()): ?>
              <img src="<?php echo e(asset('storage/' . $product->images->where('is_primary', true)->first()->path)); ?>" style="width:44px; height:44px; border-radius:8px; object-fit:cover;">
              <?php elseif($product->image_primary): ?>
              <img src="<?php echo e(asset($product->image_primary)); ?>" style="width:44px; height:44px; border-radius:8px; object-fit:cover;" onerror="this.src='<?php echo e(asset('assets/images/K ICONE.png')); ?>'">
              <?php else: ?>
              <div style="width:44px; height:44px; border-radius:8px; background:var(--color-gray-medium); display:flex; align-items:center; justify-content:center; font-size:10px; color:var(--color-text-muted);">N/A</div>
              <?php endif; ?>
              <div>
                <strong><?php echo e($product->name); ?></strong><br>
                <small style="color:var(--color-text-muted);"><?php echo e($product->description ?? ''); ?> <?php echo e($product->size ? '• ' . $product->size : ''); ?></small>
              </div>
            </div>
          </td>
          <td style="color:var(--color-text-muted);"><?php echo e($product->sku ?? '—'); ?></td>
          <td><?php echo e($product->categories->pluck('name')->join(', ') ?: '—'); ?></td>
          <td><strong><?php echo e(number_format($product->price, 2, ',', ' ')); ?> €</strong></td>
          <td style="<?php echo e($product->stock <= 10 ? 'color:var(--color-error);font-weight:600;' : ''); ?>"><?php echo e($product->stock); ?></td>
          <td><?php echo e($product->images->count()); ?>/5</td>
          <td>
            <?php if($product->is_active): ?>
            <span class="status-badge status-badge--success">Actif</span>
            <?php else: ?>
            <span class="status-badge" style="background:var(--color-gray-medium);color:var(--color-text-muted);">Inactif</span>
            <?php endif; ?>
          </td>
          <td>
            <div style="display:flex; gap:6px;">
              <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="action-btn" title="Modifier">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px; height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <form method="POST" action="<?php echo e(route('admin.products.destroy', $product)); ?>" onsubmit="return confirm('Supprimer ce produit définitivement ?')" style="display:inline;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="action-btn action-btn--danger" title="Supprimer">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px; height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="9" style="text-align:center; padding:2rem; color:var(--color-text-muted);">Aucun produit trouvé.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div style="margin-top:var(--space-xl);">
    <?php echo e($products->links()); ?>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\products\index.blade.php ENDPATH**/ ?>