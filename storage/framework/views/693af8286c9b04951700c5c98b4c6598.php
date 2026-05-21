<?php $__env->startSection('title', 'Modifier — ' . $user->firstname . ' ' . $user->lastname); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <a href="<?php echo e(route('admin.users.index')); ?>" style="display:inline-flex;align-items:center;gap:8px;color:var(--color-text-muted);font-size:14px;margin-bottom:var(--space-lg);text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:16px;height:16px;"><path d="m15 18-6-6 6-6"/></svg> Retour aux utilisateurs
  </a>

  <h1 class="page-title">Modifier — <?php echo e($user->firstname); ?> <?php echo e($user->lastname); ?></h1>

  <?php if(session('success')): ?>
  <div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);"><?php echo e(session('success')); ?></div>
  <?php endif; ?>
  <?php if($errors->any()): ?>
  <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:var(--space-lg);"><ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
  <?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-xl);align-items:start;">
    <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>">
      <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
      <div class="card">
        <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Informations</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-md);">
          <div class="admin-form-group"><label class="admin-label">Prénom</label><input type="text" name="firstname" class="admin-input" value="<?php echo e(old('firstname', $user->firstname)); ?>"></div>
          <div class="admin-form-group"><label class="admin-label">Nom</label><input type="text" name="lastname" class="admin-input" value="<?php echo e(old('lastname', $user->lastname)); ?>"></div>
          <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-label">Email *</label><input type="email" name="email" class="admin-input" value="<?php echo e(old('email', $user->email)); ?>" required></div>
          <div class="admin-form-group"><label class="admin-label">Téléphone</label><input type="text" name="phone" class="admin-input" value="<?php echo e(old('phone', $user->phone)); ?>"></div>
          <div class="admin-form-group"><label class="admin-label">Ville</label><input type="text" name="city" class="admin-input" value="<?php echo e(old('city', $user->city)); ?>"></div>
          <div class="admin-form-group"><label class="admin-label">Code postal</label><input type="text" name="postal_code" class="admin-input" value="<?php echo e(old('postal_code', $user->postal_code)); ?>"></div>
          <div class="admin-form-group"><label class="admin-label">Pays</label><input type="text" name="country" class="admin-input" value="<?php echo e(old('country', $user->country)); ?>"></div>
        </div>
        <div class="admin-form-group"><label class="admin-label">Points fidélité</label><input type="number" name="loyalty_points" class="admin-input" value="<?php echo e(old('loyalty_points', $user->loyalty_points)); ?>" min="0"></div>
        <div style="display:flex;gap:var(--space-lg);">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="checkbox" name="is_admin" value="1" <?php if(old('is_admin', $user->is_admin)): echo 'checked'; endif; ?> style="accent-color:var(--color-warm);"> Admin</label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="checkbox" name="is_active" value="1" <?php if(old('is_active', $user->is_active)): echo 'checked'; endif; ?> style="accent-color:var(--color-warm);"> Actif</label>
        </div>
        <div style="margin-top:var(--space-lg);">
          <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
      </div>
    </form>

    <div class="card">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Résumé</h3>
      <div style="display:flex;flex-direction:column;gap:var(--space-md);">
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">ID</span><span><?php echo e($user->id); ?></span></div>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">Inscrit le</span><span><?php echo e($user->created_at->format('d/m/Y H:i')); ?></span></div>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">Dernière mise à jour</span><span><?php echo e($user->updated_at->format('d/m/Y H:i')); ?></span></div>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">Newsletter</span><span><?php echo e($user->newsletter ? '✅ Oui' : 'Non'); ?></span></div>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">Anniversaire</span><span><?php echo e($user->birthday ? $user->birthday->format('d/m') : '—'); ?></span></div>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">Tokens API</span><span><?php echo e($user->tokens_count ?? 0); ?></span></div>

        <div style="display:flex;gap:var(--space-sm);margin-top:var(--space-md);flex-wrap:wrap;">
          <form method="POST" action="<?php echo e(route('admin.users.toggle-active', $user)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <button type="submit" class="btn-katuiscia <?php echo e($user->is_active ? '' : 'btn-katuiscia-filled'); ?>" style="font-size:12px;">
              <?php echo e($user->is_active ? '🔒 Désactiver' : '🔓 Activer'); ?>

            </button>
          </form>
          <form method="POST" action="<?php echo e(route('admin.users.toggle-admin', $user)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <button type="submit" class="btn-katuiscia" style="font-size:12px;">
              <?php echo e($user->is_admin ? '⬇ Retirer admin' : '⬆ Promouvoir admin'); ?>

            </button>
          </form>
          <?php if(auth()->id() !== $user->id): ?>
          <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn-katuiscia" style="color:var(--color-error);border-color:var(--color-error);font-size:12px;">Supprimer</button>
          </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\users\edit.blade.php ENDPATH**/ ?>