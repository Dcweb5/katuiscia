<?php $__env->startSection('title','Avis clients'); ?>
<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);">
    <div><h1 class="page-title">Avis clients</h1><p class="page-subtitle">Modérez les avis produits.</p></div>
    <span style="font-size:var(--text-xs);color:var(--color-text-muted);"><?php echo e($pending); ?> en attente · <?php echo e($approved); ?> approuvés</span>
  </div>
  <?php if(session('success')): ?><div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);"><?php echo e(session('success')); ?></div><?php endif; ?>
  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead><tr><th>Produit</th><th>Client</th><th>Note</th><th>Titre</th><th>Date</th><th>Statut</th><th>Actions</th></tr></thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr style="cursor:pointer;" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'':'none'">
          <td style="font-size:var(--text-sm);"><?php echo e($r->product->name ?? '—'); ?></td>
          <td style="font-size:var(--text-sm);"><?php echo e($r->user->firstname ?? '—'); ?> <?php echo e($r->user->lastname ?? ''); ?></td>
          <td style="color:var(--color-warm);"><?php echo e(str_repeat('★',$r->rating)); ?></td>
          <td style="font-size:var(--text-sm);"><?php echo e($r->title); ?> <span style="font-size:10px;color:var(--color-warm);">🔍 cliquer pour lire</span></td>
          <td style="font-size:var(--text-xs);color:var(--color-text-muted);"><?php echo e($r->created_at->format('d/m/Y')); ?></td>
          <td><?php if($r->is_approved): ?><span class="status-badge status-badge--success">Approuvé</span><?php else: ?><span style="background:var(--color-warm);color:white;padding:2px 10px;border-radius:var(--radius-full);font-size:10px;">En attente</span><?php endif; ?></td>
          <td>
            <div style="display:flex;gap:4px;">
              <?php if(!$r->is_approved): ?>
              <form method="POST" action="<?php echo e(route('admin.reviews.approve', $r)); ?>" onclick="event.stopPropagation()"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?><button type="submit" class="action-btn" title="Approuver (+50 pts)" style="color:var(--color-success);">✓</button></form>
              <?php endif; ?>
              <form method="POST" action="<?php echo e(route('admin.reviews.destroy', $r)); ?>" onsubmit="return confirm('Supprimer ?')" onclick="event.stopPropagation()"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button type="submit" class="action-btn action-btn--danger">×</button></form>
            </div>
          </td>
        </tr>
        <tr style="display:none;background:rgba(196,150,122,0.04);">
          <td colspan="7" style="padding:var(--space-xl);">
            <div style="display:grid;grid-template-columns:auto 1fr;gap:var(--space-lg);">
              <div style="display:flex;align-items:center;gap:var(--space-md);">
                <div style="width:72px;height:72px;border-radius:var(--radius-md);overflow:hidden;background:var(--color-gray-k);flex-shrink:0;">
                  <?php $img = $r->product->images->first(); ?>
                  <?php if($img): ?><img src="<?php echo e(asset('storage/'.$img->path)); ?>" alt="" style="width:100%;height:100%;object-fit:cover;"><?php endif; ?>
                </div>
                <div>
                  <strong style="font-size:var(--text-md);display:block;"><?php echo e($r->product->name ?? '—'); ?></strong>
                  <span style="color:var(--color-warm);"><?php echo e(str_repeat('★',$r->rating)); ?><?php echo e(str_repeat('☆',5-$r->rating)); ?></span>
                  <p style="font-size:11px;color:var(--color-text-muted);">Par <?php echo e($r->user->firstname ?? ''); ?> <?php echo e($r->user->lastname ?? ''); ?> • <?php echo e($r->created_at->format('d/m/Y')); ?></p>
                </div>
              </div>
              <div>
                <h4 style="font-size:var(--text-lg);margin-bottom:var(--space-sm);"><?php echo e($r->title); ?></h4>
                <?php if($r->body): ?><p style="font-size:var(--text-sm);color:var(--color-text-light);line-height:1.7;white-space:pre-wrap;"><?php echo e($r->body); ?></p><?php else: ?><p style="color:var(--color-text-muted);font-style:italic;">Pas de commentaire</p><?php endif; ?>
              </div>
            </div>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun avis.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php echo e($reviews->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\reviews\index.blade.php ENDPATH**/ ?>