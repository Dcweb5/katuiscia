<?php $__env->startSection('title', 'Nouvelle Catégorie'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <a href="<?php echo e(route('admin.categories.index')); ?>" style="display:inline-flex; align-items:center; gap:8px; color:var(--color-text-muted); font-size:14px; margin-bottom:var(--space-lg); text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:16px; height:16px;"><path d="m15 18-6-6 6-6"/></svg>
    Retour aux catégories
  </a>

  <h1 class="page-title">Nouvelle Catégorie</h1>

  <?php if($errors->any()): ?>
  <div style="padding:12px 16px; background:rgba(199,80,80,0.1); border:1px solid var(--color-error); border-radius:8px; color:var(--color-error); margin-bottom:var(--space-lg);">
    <ul style="margin:0; padding-left:20px;"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
  </div>
  <?php endif; ?>

  <form method="POST" action="<?php echo e(route('admin.categories.store')); ?>" style="max-width:500px;">
    <?php echo csrf_field(); ?>
    <div class="card">
      <div class="admin-form-group">
        <label class="admin-label">Nom *</label>
        <input type="text" name="name" class="admin-input" value="<?php echo e(old('name')); ?>" required>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Slug</label>
        <input type="text" name="slug" class="admin-input" value="<?php echo e(old('slug')); ?>" placeholder="Auto-généré" style="color:var(--color-text-muted);">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Description</label>
        <textarea name="description" class="admin-input" rows="3" style="resize:vertical;"><?php echo e(old('description')); ?></textarea>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Catégorie parente</label>
        <select name="parent_id" class="admin-input">
          <option value="">Aucune (catégorie racine)</option>
          <?php $__currentLoopData = $parentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($parent->id); ?>" <?php if(old('parent_id')==$parent->id): echo 'selected'; endif; ?>><?php echo e($parent->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="admin-form-group">
        <label style="display:flex; align-items:center; gap:8px; font-size:var(--text-sm); cursor:pointer;">
          <input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--color-warm);"> Active (visible en boutique)
        </label>
      </div>
    </div>
    <div style="display:flex; gap:var(--space-sm); justify-content:flex-end; margin-top:var(--space-lg);">
      <a href="<?php echo e(route('admin.categories.index')); ?>" style="padding:10px 20px; border:1px solid var(--color-border); border-radius:var(--radius-sm); background:transparent; font-size:var(--text-sm); text-decoration:none; color:inherit;">Annuler</a>
      <button type="submit" class="btn-primary">Créer la catégorie</button>
    </div>
  </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\categories\create.blade.php ENDPATH**/ ?>