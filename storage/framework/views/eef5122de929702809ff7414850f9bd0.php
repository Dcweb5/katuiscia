<?php $__env->startSection('title', 'Suivi de commande'); ?>
<?php $__env->startSection('content'); ?>
<main id="main-content">
  <section class="page-header page-header--with-bg">
    <img src="<?php echo e(asset('assets/images/hero-slide-2.jpg')); ?>" alt="" class="page-header__bg" aria-hidden="true">
    <div class="container reveal-k">
      <h1 class="page-header__title">Suivi de commande</h1>
      <p class="page-header__description">Suivez l'état de votre commande en temps réel avec votre email et votre numéro de commande.</p>
    </div>
  </section>

  <section style="padding:4rem 0;">
    <div class="container" style="max-width:700px;margin:0 auto;">

      
      <div class="card" style="padding:var(--space-xl);margin-bottom:2rem;">
        <form method="POST" action="<?php echo e(route('track.find')); ?>">
          <?php echo csrf_field(); ?>
          <div style="display:flex;flex-direction:column;gap:var(--space-md);">
            <div class="form-group">
              <label class="form-label">Adresse Email *</label>
              <input type="email" name="email" class="form-input" value="<?php echo e(old('email', $order->email ?? request('email'))); ?>" placeholder="votre@email.com" required>
              <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error" style="color:var(--color-error);font-size:var(--text-xs);"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
              <label class="form-label">Numéro de commande *</label>
              <input type="text" name="order_number" class="form-input" value="<?php echo e(old('order_number', $order->order_number ?? request('order_number'))); ?>" placeholder="Ex: KAT-ABC123" required>
              <?php $__errorArgs = ['order_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error" style="color:var(--color-error);font-size:var(--text-xs);"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <button type="submit" class="btn-katuiscia-filled" style="width:100%;padding:1rem;font-size:var(--text-md);">🔍 Suivre ma commande</button>
          </div>
        </form>
      </div>

      
      <?php if(isset($order)): ?>
      <div class="card" style="padding:var(--space-xl);">
        <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
          <div>
            <strong style="font-family:monospace;font-size:var(--text-lg);"><?php echo e($order->order_number); ?></strong>
            <span style="font-size:var(--text-xs);color:var(--color-text-muted);margin-left:1rem;"><?php echo e($order->created_at->format('d/m/Y à H:i')); ?></span>
          </div>
          <span style="font-size:var(--text-xs);font-weight:600;color:<?php echo e($statusLabels[$order->status]['color'] ?? '#000'); ?>;background:<?php echo e($statusLabels[$order->status]['bg'] ?? '#f0f0f0'); ?>;padding:6px 16px;border-radius:var(--radius-full);white-space:nowrap;">
            <?php echo e($statusLabels[$order->status]['label'] ?? $order->status); ?>

          </span>
        </div>

        <?php if($order->status !== 'cancelled'): ?>
        <div style="display:flex;align-items:center;gap:0;margin-bottom:1.5rem;">
          <?php $__currentLoopData = ['confirmed'=>'✓','preparing'=>'📦','shipped'=>'🚚','delivered'=>'✅']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s => $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php $idx = array_search($s, $steps); $done = $idx !== false && $idx <= $currentStepIndex; ?>
          <div style="flex:1;text-align:center;position:relative;">
            <div style="width:32px;height:32px;border-radius:50%;background:<?php echo e($done ? 'var(--color-dark)' : 'var(--color-gray-medium)'); ?>;color:<?php echo e($done ? 'white' : 'var(--color-text-muted)'); ?>;display:inline-flex;align-items:center;justify-content:center;font-size:14px;position:relative;z-index:1;"><?php echo e($icon); ?></div>
            <div style="font-size:10px;color:<?php echo e($done ? 'var(--color-dark)' : 'var(--color-text-muted)'); ?>;margin-top:6px;">
              <?php $labels = ['confirmed'=>'Confirmée','preparing'=>'Préparée','shipped'=>'Expédiée','delivered'=>'Livrée']; ?>
              <?php echo e($labels[$s] ?? $s); ?>

            </div>
          </div>
          <?php if(!$loop->last): ?>
          <div style="flex:0.5;height:2px;background:<?php echo e($idx < $currentStepIndex ? 'var(--color-dark)' : 'var(--color-gray-medium)'); ?>;min-width:20px;"></div>
          <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <?php if($order->tracking_number): ?>
        <div style="background:var(--color-bg);padding:1rem;border-radius:8px;margin-bottom:1.5rem;">
          <strong style="font-size:var(--text-sm);">Numéro de suivi :</strong>
          <span style="font-family:monospace;font-size:var(--text-md);margin-left:0.5rem;"><?php echo e($order->tracking_number); ?></span>
          <p style="font-size:var(--text-xs);color:var(--color-text-muted);margin-top:0.25rem;">Vous pouvez suivre votre colis sur le site du transporteur avec ce numéro.</p>
        </div>
        <?php endif; ?>

        <div style="margin-bottom:1.5rem;">
          <strong style="display:block;margin-bottom:0.75rem;font-size:var(--text-sm);">Articles commandés</strong>
          <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="display:flex;align-items:center;gap:0.5rem;background:var(--color-gray-k);padding:8px 14px;border-radius:var(--radius-sm);">
              <span style="font-size:var(--text-sm);"><?php echo e($item->product_name); ?></span>
              <span style="font-size:11px;color:var(--color-text-muted);">×<?php echo e($item->quantity); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>

        <div style="border-top:1px solid var(--color-border);padding-top:1rem;display:flex;justify-content:space-between;align-items:center;">
          <div>
            <span style="font-size:var(--text-sm);color:var(--color-text-muted);">Total</span>
            <strong style="font-family:var(--font-display);font-size:var(--text-xl);margin-left:0.5rem;"><?php echo e(number_format($order->total, 0, ',', ' ')); ?> €</strong>
          </div>
          <div style="font-size:var(--text-xs);color:var(--color-text-muted);">
            <?php if($order->payment_method): ?>
            Paiement : <?php echo e($order->payment_method === 'card' ? 'Carte bancaire' : ($order->payment_method === 'paypal' ? 'PayPal' : $order->payment_method)); ?>

            <?php endif; ?>
          </div>
        </div>

        <?php if($order->coupon_code): ?>
        <div style="margin-top:0.5rem;font-size:var(--text-xs);color:var(--color-warm);">
          Code promo appliqué : <strong><?php echo e($order->coupon_code); ?></strong>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>

    </div>
  </section>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\pages\suivi-commande.blade.php ENDPATH**/ ?>