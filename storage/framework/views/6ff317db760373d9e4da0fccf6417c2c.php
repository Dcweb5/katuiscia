<?php $__env->startSection('title','Rendez-vous'); ?>
<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div>
      <h1 class="page-title">Rendez-vous & Leads</h1>
      <p class="page-subtitle">Demandes de formation et partenariat grossiste.</p>
    </div>
  </div>

  <?php if(session('success')): ?>
    <div class="alert alert-success" style="margin-bottom:1rem;"><?php echo e(session('success')); ?></div>
  <?php endif; ?>

  <div style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
    <a href="?status=en_attente" class="btn-primary" style="text-decoration:none;font-size:var(--text-sm);">⏳ En attente (<?php echo e($pending); ?>)</a>
    <a href="?source=formation" class="btn-primary" style="text-decoration:none;font-size:var(--text-sm);background:transparent;color:var(--color-text);border-color:var(--color-border);">🎓 Formation (<?php echo e($countBySource['formation']); ?>)</a>
    <a href="?source=grossiste" class="btn-primary" style="text-decoration:none;font-size:var(--text-sm);background:transparent;color:var(--color-text);border-color:var(--color-border);">🤝 Grossiste (<?php echo e($countBySource['grossiste']); ?>)</a>
    <a href="?" class="btn-primary" style="text-decoration:none;font-size:var(--text-sm);background:transparent;color:var(--color-text);border-color:var(--color-border);">📋 Tous</a>
  </div>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Tél</th>
          <th>Type</th>
          <th>Source</th>
          <th>Date souhaitée</th>
          <th>Créneau</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td style="font-weight:500;"><?php echo e($a->name); ?></td>
          <td style="font-size:var(--text-sm);"><?php echo e($a->email); ?></td>
          <td style="font-size:var(--text-sm);"><?php echo e($a->phone ?? '—'); ?></td>
          <td style="font-size:var(--text-sm);"><?php echo e($a->type); ?><?php if($a->company_name): ?><br><small style="color:var(--color-text-muted);"><?php echo e($a->company_name); ?></small><?php endif; ?></td>
          <td>
            <span style="font-size:var(--text-xs);padding:2px 8px;border-radius:12px;<?php echo e($a->source === 'formation' ? 'background:rgba(196,150,122,0.15);color:var(--color-warm);' : 'background:rgba(76,175,80,0.1);color:#388e3c;'); ?>"><?php echo e($a->source === 'formation' ? '🎓 Formation' : '🤝 Grossiste'); ?></span>
          </td>
          <td style="font-size:var(--text-sm);"><?php echo e($a->preferred_date ? \Carbon\Carbon::parse($a->preferred_date)->format('d/m/Y') : '—'); ?></td>
          <td style="font-size:var(--text-sm);"><?php echo e($a->preferred_time ?? '—'); ?></td>
          <td>
            <span style="font-size:var(--text-xs);padding:2px 8px;border-radius:12px;<?php echo e($a->status === 'confirme' ? 'background:#e8f5e9;color:#2e7d32;' : ($a->status === 'refuse' ? 'background:#ffebee;color:#c62828;' : 'background:#fff8e1;color:#f57f17;')); ?>">
              <?php echo e($a->status === 'confirme' ? '✅ Confirmé' : ($a->status === 'refuse' ? '❌ Refusé' : '⏳ En attente')); ?>

            </span>
          </td>
          <td>
            <div style="display:flex;gap:4px;">
              <a href="<?php echo e(route('admin.appointments.show', $a)); ?>" class="action-btn" title="Détail">👁</a>
              <form method="POST" action="<?php echo e(route('admin.appointments.destroy', $a)); ?>" style="display:inline;" onsubmit="return confirm('Supprimer ce rendez-vous ?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="action-btn" title="Supprimer">🗑</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="9" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun rendez-vous.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php echo e($appointments->links()); ?>

</div>
<script>
  var link = document.querySelector('.sidebar-link[data-page="admin-rendezvous"]');
  if (link) link.classList.add('active');
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\appointments\index.blade.php ENDPATH**/ ?>