<?php $__env->startSection('title', 'Commande confirmée — KATUISCIA'); ?>
<?php $__env->startSection('content'); ?>
<div class="pt-[calc(190px+4rem)] pb-20 max-w-site mx-auto px-4 lg:px-8 text-center">
  <div class="reveal-k-k">
    <div style="font-size:64px;margin-bottom:var(--space-md);">✅</div>
    <h1 class="font-heading text-4xl font-light text-dark mb-4">Commande confirmée !</h1>
    <p class="text-text-light text-lg mb-2">Merci pour votre commande, <?php echo e($order->firstname); ?>.</p>
    <p class="text-text-muted mb-8">Numéro de commande : <strong><?php echo e($order->order_number); ?></strong></p>

    <div class="max-w-lg mx-auto bg-white rounded-xl p-8 shadow-card text-left mb-8">
      <h3 class="font-heading text-lg mb-4">Récapitulatif</h3>
      <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="flex justify-between text-sm py-2 border-b border-border-k"><span><?php echo e($item->product_name); ?> x<?php echo e($item->quantity); ?></span><span><?php echo e(number_format($item->subtotal, 0, ',', ' ')); ?> €</span></div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php if($order->discount > 0): ?>
      <div class="flex justify-between text-sm py-2 border-b border-border-k"><span class="text-text-muted">Réduction (<?php echo e($order->coupon_code); ?>)</span><span style="color:var(--color-success);">-<?php echo e(number_format($order->discount, 0, ',', ' ')); ?> €</span></div>
      <?php endif; ?>
      <div class="flex justify-between text-sm py-2"><span class="text-text-muted">Livraison</span><span class="text-success">OFFERTE</span></div>
      <div class="flex justify-between font-heading text-lg mt-4 pt-4 border-t border-dark"><span>Total</span><span><?php echo e(number_format($order->total, 0, ',', ' ')); ?> €</span></div>
      <div class="mt-4 text-sm text-text-muted">
        <p>📧 <?php echo e($order->email); ?></p>
        <p>📍 <?php echo e($order->address); ?>, <?php echo e($order->postal_code); ?> <?php echo e($order->city); ?></p>
        <p>💳 <?php echo e($order->payment_method === 'cod' ? 'Paiement à la livraison' : 'Carte bancaire'); ?></p>
      </div>
    </div>

    <div style="display:flex;gap:var(--space-md);justify-content:center;flex-wrap:wrap;margin-bottom:var(--space-2xl);">
      <?php if(auth()->guard()->check()): ?><a href="<?php echo e(url('compte/commandes')); ?>" class="btn-katuiscia">Suivre ma commande</a><?php endif; ?>
      <a href="<?php echo e(route('track')); ?>?order_number=<?php echo e($order->order_number); ?>&email=<?php echo e(urlencode($order->email)); ?>" class="btn-katuiscia">📦 Suivi de commande</a>
      <a href="<?php echo e(url('boutique')); ?>" class="btn-katuiscia-filled">Continuer mes achats</a>
    </div>

    <!-- Map -->
    <div class="max-w-2xl mx-auto rounded-xl overflow-hidden shadow-lg mb-8 reveal-k-k delay-1">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2634.5!2d2.35!3d48.68!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDjCsDQwJzQ4LjAiTiAywrAyMScwMC4wIkU!5e0!3m2!1sfr!2sfr!4v1" class="w-full h-[300px] border-0" allowfullscreen="" loading="lazy" title="Localisation KATUISCIA"></iframe>
    </div>
    <p class="text-xs text-text-muted">📍 9 bis route de Corbeil, 91360 Villemoisson-sur-Orge</p>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\pages\checkout-success.blade.php ENDPATH**/ ?>