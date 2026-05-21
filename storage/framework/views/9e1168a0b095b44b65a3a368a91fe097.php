<?php $__env->startSection('title', 'Panier — KATUISCIA'); ?>
<?php $__env->startSection('head'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/panier.css')); ?>">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="pt-[calc(190px+3rem)] pb-20 max-w-site mx-auto px-4 lg:px-8">
  <h1 class="font-heading text-4xl font-light text-dark mb-8 reveal-k-k">Votre Panier</h1>
  <?php if(session('success')): ?><div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);"><?php echo e(session('success')); ?></div><?php endif; ?>

  <?php if($cart->items->count() > 0): ?>
  <div class="flex flex-col lg:flex-row gap-12">
    <div class="flex-1 space-y-4 reveal-k-k delay-1">
      <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="bg-white rounded-xl p-6 shadow-card flex flex-col sm:flex-row gap-6 items-start sm:items-center" data-item-id="<?php echo e($item->id); ?>">
        <a href="<?php echo e(url('produit/' . $item->product->slug)); ?>" class="w-24 h-24 bg-gray-k rounded-lg flex-shrink-0 overflow-hidden">
          <?php $img = $item->product->images->first(); ?>
          <?php if($img): ?><img src="<?php echo e(asset('storage/' . $img->path)); ?>" alt="" class="w-full h-full object-cover rounded-lg"><?php elseif($item->product->image_primary): ?><img src="<?php echo e(asset($item->product->image_primary)); ?>" alt="" class="w-full h-full object-cover rounded-lg" onerror="this.src='<?php echo e(asset('assets/images/K ICONE.png')); ?>'"><?php else: ?><div class="w-full h-full flex items-center justify-center bg-gray-k text-text-muted text-xs">K</div><?php endif; ?>
        </a>
        <div class="flex-1 min-w-0">
          <h3 class="font-heading text-lg text-dark"><?php echo e($item->product->name); ?></h3>
          <p class="text-sm text-text-muted"><?php echo e($item->product->size ?? ''); ?></p>
          <p class="text-sm font-semibold text-dark mt-1"><span class="item-subtotal"><?php echo e(number_format($item->subtotal, 0, ',', ' ')); ?> €</span> <small class="text-text-muted">(<?php echo e(number_format($item->price, 0, ',', ' ')); ?> €/u)</small></p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
          <button type="button" class="w-8 h-8 border border-border-k rounded-full qty-btn" data-delta="-1">−</button>
          <input type="number" class="w-10 text-center bg-transparent text-sm border-x border-border-k outline-none qty-input" value="<?php echo e($item->quantity); ?>" min="1" max="10" readonly>
          <button type="button" class="w-8 h-8 border border-border-k rounded-full qty-btn" data-delta="1">+</button>
          <button type="button" class="text-text-muted hover:text-error text-xl leading-none cart-remove-btn" title="Retirer">&times;</button>
        </div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="lg:w-[380px] reveal-k-k delay-2">
      <div class="bg-white rounded-xl p-6 shadow-card space-y-4 sticky top-[220px]">
        <h3 class="font-heading text-xl text-dark">Récapitulatif</h3>
        <div class="flex justify-between text-sm"><span class="text-text-muted">Sous-total (<span id="cart-count-display"><?php echo e($cart->items_count); ?> article(s)</span>)</span><span id="cart-subtotal-display"><?php echo e(number_format($cart->total, 0, ',', ' ')); ?> €</span></div>
        <span id="cart-discount" style="display:none;"></span>
        <div class="flex justify-between text-sm"><span class="text-text-muted">Livraison</span><span class="text-success">OFFERTE</span></div>
        <hr class="border-border-k">
        <div style="display:flex;gap:var(--space-sm);">
          <input type="text" id="coupon-code" class="w-full p-3 border border-border-k rounded-md text-sm" placeholder="Code promo">
          <button type="button" id="apply-coupon" class="btn-katuiscia" style="font-size:12px;white-space:nowrap;">Appliquer</button>
        </div>
        <div id="coupon-message" style="display:none;font-size:12px;"></div>
        <div class="flex justify-between font-heading text-lg"><span>Total</span><span id="cart-total-display"><?php echo e(number_format($cart->total, 0, ',', ' ')); ?> €</span></div>
        <input type="hidden" id="coupon-code-input" value="" name="coupon_code">
        <input type="hidden" id="coupon-discount-input" value="0" name="coupon_discount">
        <a href="<?php echo e(url('paiement')); ?>" class="btn-katuiscia-filled w-full text-center block">Finaliser la commande</a>
      </div>
    </div>
  </div>
  <?php else: ?>
  <div class="text-center py-24 reveal-k-k">
    <div style="font-size:64px;margin-bottom:var(--space-lg);">🛒</div>
    <h2 class="font-heading text-2xl text-dark mb-4">Votre panier est vide</h2>
    <p class="text-text-light mb-8">Découvrez nos soins botaniques.</p>
    <a href="<?php echo e(url('boutique')); ?>" class="btn-katuiscia-filled">Explorer la boutique</a>
  </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?><script type="module" src="<?php echo e(asset('js/main.js')); ?>"></script><?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\pages\panier.blade.php ENDPATH**/ ?>