<?php $__env->startSection('title', 'Commande ' . $order->order_number); ?>
<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <a href="<?php echo e(route('admin.orders.index')); ?>" style="display:inline-flex;align-items:center;gap:8px;color:var(--color-text-muted);font-size:14px;margin-bottom:var(--space-lg);text-decoration:none;">← Retour</a>
  <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:var(--space-xl);">
    <div><h1 class="page-title">Commande <?php echo e($order->order_number); ?></h1><p class="page-subtitle"><?php echo e($order->firstname); ?> <?php echo e($order->lastname); ?> — <?php echo e($order->created_at->format('d/m/Y H:i')); ?></p></div>
    <?php $colors=['pending'=>'var(--color-warm)','confirmed'=>'#3b82f6','preparing'=>'#f59e0b','shipped'=>'#8b5cf6','delivered'=>'var(--color-success)','cancelled'=>'var(--color-error)']; ?>
    <span class="status-badge" style="background:<?php echo e($colors[$order->status] ?? '#ccc'); ?>;color:white;font-size:12px;font-weight:600;padding:8px 20px;border-radius:var(--radius-full);"><?php echo e(ucfirst($order->status)); ?></span>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-xl);">
    <div>
      <div class="card" style="margin-bottom:var(--space-xl);">
        <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Client</h3>
        <div style="display:flex;flex-direction:column;gap:var(--space-sm);">
          <p><strong><?php echo e($order->firstname); ?> <?php echo e($order->lastname); ?></strong></p>
          <p style="font-size:var(--text-sm);color:var(--color-text-muted);"><?php echo e($order->email); ?></p>
          <p style="font-size:var(--text-sm);color:var(--color-text-muted);"><?php echo e($order->phone ?? '—'); ?></p>
        </div>
      </div>
      <div class="card" style="margin-bottom:var(--space-xl);">
        <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Livraison</h3>
        <p style="font-size:var(--text-sm);line-height:1.6;"><?php echo e($order->address); ?><?php if($order->address2): ?>, <?php echo e($order->address2); ?><?php endif; ?><br><?php echo e($order->postal_code); ?> <?php echo e($order->city); ?><br><?php echo e($order->country); ?></p>
      </div>
      <div class="card">
        <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Statut</h3>
        <form method="POST" action="<?php echo e(route('admin.orders.update-status', $order)); ?>">
          <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
          <select name="status" class="admin-input" style="margin-bottom:var(--space-sm);">
            <?php $__currentLoopData = ['pending'=>'En attente','confirmed'=>'Confirmée','preparing'=>'En préparation','shipped'=>'Expédiée','delivered'=>'Livrée','cancelled'=>'Annulée']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($k); ?>" <?php if($order->status==$k): echo 'selected'; endif; ?>><?php echo e($l); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <input type="text" name="tracking_number" class="admin-input" style="margin-bottom:var(--space-sm);" placeholder="N° de suivi (optionnel)" value="<?php echo e($order->tracking_number); ?>">
          <button type="submit" class="btn-primary" style="width:100%;">Mettre à jour</button>
        </form>
      </div>
    </div>
    <div>
      <div class="card" style="margin-bottom:var(--space-xl);">
        <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Articles (<?php echo e($order->items->count()); ?>)</h3>
        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);">
          <span style="font-size:var(--text-sm);"><?php echo e($item->product_name); ?> x<?php echo e($item->quantity); ?></span>
          <span style="font-size:var(--text-sm);"><?php echo e(number_format($item->subtotal, 2, ',', ' ')); ?> €</span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <hr style="margin-top:var(--space-md);">
        <div style="display:flex;justify-content:space-between;padding:var(--space-xs) 0;font-size:var(--text-sm);"><span class="text-text-muted">Sous-total</span><span><?php echo e(number_format($order->subtotal, 2, ',', ' ')); ?> €</span></div>
        <?php if($order->discount > 0): ?>
        <div style="display:flex;justify-content:space-between;padding:var(--space-xs) 0;font-size:var(--text-sm);"><span class="text-text-muted">Réduction <?php if($order->coupon_code): ?>(<?php echo e($order->coupon_code); ?>)<?php endif; ?></span><span style="color:var(--color-success);">-<?php echo e(number_format($order->discount, 2, ',', ' ')); ?> €</span></div>
        <?php endif; ?>
        <div style="display:flex;justify-content:space-between;padding:var(--space-xs) 0;font-size:var(--text-sm);"><span class="text-text-muted">Livraison</span><span><?php echo e($order->shipping > 0 ? number_format($order->shipping, 2, ',', ' ') . ' €' : 'OFFERTE'); ?></span></div>
        <div style="display:flex;justify-content:space-between;font-weight:700;font-family:var(--font-heading);font-size:var(--text-lg);margin-top:var(--space-sm);"><span>Total</span><span><?php echo e(number_format($order->total, 2, ',', ' ')); ?> €</span></div>
      </div>
      <div class="card"><h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Paiement</h3><p style="font-size:var(--text-sm);"><?php echo e($order->payment_method === 'cod' ? 'Paiement à la livraison' : 'Carte bancaire'); ?></p></div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\orders\show.blade.php ENDPATH**/ ?>