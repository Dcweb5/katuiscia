<?php $__env->startSection('title', 'Catégories'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:var(--space-2xl);">
    <div>
      <h1 class="page-title">Gestion des Catégories</h1>
      <p class="page-subtitle">Organisez vos produits en catégories pour une navigation claire.</p>
    </div>
    <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn-primary" style="text-decoration:none;">+ Nouvelle Catégorie</a>
  </div>

  <?php if(session('success')): ?>
  <div style="padding:12px 16px; background:rgba(90,143,110,0.1); border:1px solid var(--color-success); border-radius:8px; color:var(--color-success); margin-bottom:var(--space-lg);">
    <?php echo e(session('success')); ?>

  </div>
  <?php endif; ?>
  <?php if(session('error')): ?>
  <div style="padding:12px 16px; background:rgba(199,80,80,0.1); border:1px solid var(--color-error); border-radius:8px; color:var(--color-error); margin-bottom:var(--space-lg);">
    <?php echo e(session('error')); ?>

  </div>
  <?php endif; ?>

  <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(340px, 1fr)); gap:var(--space-lg);">
    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card" style="position:relative; overflow:hidden;">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:var(--space-md);">
        <div>
          <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:4px;"><?php echo e($category->name); ?></h3>
          <span style="font-size:var(--text-xs); color:var(--color-text-muted); text-transform:uppercase; letter-spacing:var(--tracking-wider);">
            <?php echo e($category->slug); ?> • <?php echo e($category->products_count); ?> produit(s)
          </span>
        </div>
        <div style="display:flex; gap:8px;">
          <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="action-btn" title="Modifier">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:16px; height:16px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </a>
          <form method="POST" action="<?php echo e(route('admin.categories.destroy', $category)); ?>" onsubmit="return confirm('Supprimer cette catégorie ?')" style="display:inline;">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit" class="action-btn action-btn--danger" title="Supprimer">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:16px; height:16px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
          </form>
        </div>
      </div>
      <p style="font-size:var(--text-sm); color:var(--color-text-light); line-height:1.6;">
        <?php echo e($category->description ?? 'Aucune description.'); ?>

      </p>
      <?php if(!$category->is_active): ?>
      <span class="status-badge" style="position:absolute; top:12px; right:12px; background:var(--color-gray-medium); color:var(--color-text-muted); font-size:10px;">Inactive</span>
      <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div style="grid-column:1/-1; text-align:center; padding:4rem; color:var(--color-text-muted);">
      Aucune catégorie. <a href="<?php echo e(route('admin.categories.create')); ?>" style="color:var(--color-warm);">Créer la première</a>.
    </div>
    <?php endif; ?>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\categories\index.blade.php ENDPATH**/ ?>