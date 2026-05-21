<?php $__env->startSection('title', 'Nouveau Coupon'); ?>
<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <a href="<?php echo e(route('admin.coupons.index')); ?>" style="display:inline-flex;align-items:center;gap:8px;color:var(--color-text-muted);font-size:14px;margin-bottom:var(--space-lg);text-decoration:none;">← Retour</a>
  <h1 class="page-title">Nouveau Coupon</h1>
  <?php if($errors->any()): ?><div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:var(--space-lg);"><ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div><?php endif; ?>
  <form method="POST" action="<?php echo e(route('admin.coupons.store')); ?>" style="max-width:600px;">
    <?php echo csrf_field(); ?>
    <div class="card" style="margin-bottom:var(--space-xl);">
      <div class="admin-form-group"><label class="admin-label">Code *</label><input type="text" name="code" class="admin-input" value="<?php echo e(old('code')); ?>" placeholder="Ex: BIENVENUE10" required></div>
      <div class="admin-form-group"><label class="admin-label">Type *</label><select name="type" class="admin-input" id="coupon-type" onchange="document.getElementById('value-group').style.display=this.value==='free_shipping'?'none':''"><option value="percentage" <?php if(old('type')=='percentage'): echo 'selected'; endif; ?>>Pourcentage (%)</option><option value="fixed" <?php if(old('type')=='fixed'): echo 'selected'; endif; ?>>Montant fixe (€)</option><option value="free_shipping" <?php if(old('type')=='free_shipping'): echo 'selected'; endif; ?>>Livraison gratuite</option></select></div>
      <div class="admin-form-group" id="value-group"><label class="admin-label">Valeur</label><input type="number" name="value" class="admin-input" value="<?php echo e(old('value')); ?>" placeholder="10" step="0.01" min="0"></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-md);">
        <div class="admin-form-group"><label class="admin-label">Min. commande (€)</label><input type="number" name="min_order_amount" class="admin-input" value="<?php echo e(old('min_order_amount')); ?>" placeholder="50" step="0.01" min="0"></div>
        <div class="admin-form-group"><label class="admin-label">Max. utilisations</label><input type="number" name="max_uses" class="admin-input" value="<?php echo e(old('max_uses')); ?>" placeholder="100" min="1"></div>
        <div class="admin-form-group"><label class="admin-label">Début</label><input type="datetime-local" name="starts_at" class="admin-input" value="<?php echo e(old('starts_at')); ?>"></div>
        <div class="admin-form-group"><label class="admin-label">Expiration</label><input type="datetime-local" name="expires_at" class="admin-input" value="<?php echo e(old('expires_at')); ?>"></div>
      </div>
      <div class="admin-form-group"><label class="admin-label">Description</label><input type="text" name="description" class="admin-input" value="<?php echo e(old('description')); ?>" placeholder="Ex: 10% de réduction pour les nouveaux clients"></div>
      <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:var(--space-md);"><input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--color-warm);"> Actif</label>
    </div>
    <div style="display:flex;gap:var(--space-sm);">
      <a href="<?php echo e(route('admin.coupons.index')); ?>" class="btn-katuiscia">Annuler</a>
      <button type="submit" class="btn-primary">Créer le coupon</button>
    </div>
  </form>
</div>
<script>document.getElementById('coupon-type').value==='free_shipping'?document.getElementById('value-group').style.display='none':null;</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\coupons\create.blade.php ENDPATH**/ ?>