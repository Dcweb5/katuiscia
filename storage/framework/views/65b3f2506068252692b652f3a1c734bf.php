<?php $__env->startSection('title', $collection->name . ' — KATUISCIA'); ?>

<?php $__env->startSection('head'); ?>
<style>
.collection-hero {
  position:relative;padding:10rem 1.5rem 3rem;
  background:linear-gradient(180deg, #faf7f2 0%, #fff 100%);
  text-align:center;
}
.collection-hero__badge {
  display:inline-block;padding:6px 18px;border-radius:99px;
  font-size:11px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;
  background:#ede4db;color:#8b6f5a;margin-bottom:1rem;
}
.collection-hero__title {
  font-family:var(--font-heading);font-size:clamp(2rem,5vw,3rem);
  font-weight:300;color:#2d2117;line-height:1.25;
  max-width:700px;margin:0 auto 1rem;
}
.collection-hero__desc {
  max-width:600px;margin:0 auto;font-size:1.1rem;line-height:1.6;color:#6b5d53;
}
.collection-body { max-width:900px;margin:0 auto;padding:2rem 1.5rem 4rem; }
.collection-cover {
  width:100%;max-height:420px;object-fit:cover;border-radius:16px;
  margin-bottom:2.5rem;box-shadow:0 4px 24px rgba(0,0,0,0.06);
}
.collection-products { display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1rem;margin-bottom:2.5rem; }
.collection-product {
  display:flex;align-items:center;gap:1rem;padding:1rem;
  border:1px solid #ede4db;border-radius:12px;background:#fff;
  text-decoration:none;color:inherit;transition:all 0.2s;
}
.collection-product:hover { border-color:#c4967a;box-shadow:0 4px 16px rgba(0,0,0,0.04); }
.collection-product__img { width:56px;height:56px;border-radius:8px;object-fit:cover;flex-shrink:0; }
.collection-product__placeholder { width:56px;height:56px;border-radius:8px;background:var(--color-peach);display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0; }
.collection-product__name { font-weight:500;font-size:14px;margin-bottom:0.25rem; }
.collection-product__qty { font-size:12px;color:var(--color-text-muted); }
.collection-pricing {
  background:#faf7f2;border-radius:16px;padding:2rem;text-align:center;
  margin-bottom:2rem;border:1px solid #ede4db;
}
.collection-pricing__original {
  font-size:1.1rem;text-decoration:line-through;color:#a39688;
}
.collection-pricing__price {
  font-family:var(--font-display);font-size:2.5rem;color:#2d2117;
}
.collection-pricing__save {
  font-size:14px;color:var(--color-success);font-weight:600;margin-top:0.25rem;
}
.collection-cta {
  display:inline-flex;align-items:center;gap:0.75rem;
  padding:14px 32px;background:var(--color-dark);color:#fff;
  border-radius:99px;font-size:16px;font-weight:500;text-decoration:none;
  transition:all 0.2s;border:none;cursor:pointer;
}
.collection-cta:hover { background:#3d2b24;transform:translateY(-1px);box-shadow:0 6px 20px rgba(0,0,0,0.1); }
.collection-back {
  display:inline-flex;align-items:center;gap:0.5rem;color:#8b7b6e;
  font-size:14px;text-decoration:none;margin-top:1.5rem;
}
.collection-back:hover { color:#2d2117; }
@media (max-width:768px) {
  .collection-hero { padding:7rem 1rem 2rem; }
  .collection-products { grid-template-columns:1fr; }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<main id="main-content">

  <header class="collection-hero reveal-k-k">
    <span class="collection-hero__badge">Collection</span>
    <h1 class="collection-hero__title"><?php echo e($collection->name); ?></h1>
    <?php if($collection->description): ?>
    <p class="collection-hero__desc"><?php echo e($collection->description); ?></p>
    <?php endif; ?>
  </header>

  <div class="collection-body reveal-k-k delay-1">
    <?php if($collection->image_url): ?>
    <img src="<?php echo e($collection->image_url); ?>" alt="<?php echo e($collection->name); ?>" class="collection-cover">
    <?php endif; ?>

    
    <div class="collection-pricing">
      <?php if($collection->original_price && $collection->original_price > $collection->price): ?>
      <div class="collection-pricing__original"><?php echo e(number_format($collection->original_price, 0, ',', ' ')); ?> €</div>
      <?php endif; ?>
      <div class="collection-pricing__price"><?php echo e(number_format($collection->price, 0, ',', ' ')); ?> €</div>
      <?php if($collection->discount_percent > 0): ?>
      <div class="collection-pricing__save">Vous économisez <?php echo e($collection->discount_percent); ?>% (<?php echo e(number_format($collection->original_price - $collection->price, 0, ',', ' ')); ?> €)</div>
      <?php endif; ?>
    </div>

    
    <h2 style="font-family:var(--font-heading);font-size:1.25rem;font-weight:400;color:#2d2117;margin-bottom:1rem;text-align:center;">
      <?php echo e($collection->products->count()); ?> produit(s) inclus
    </h2>
    <div class="collection-products">
      <?php $__currentLoopData = $collection->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(url('produit/'.$product->slug)); ?>" class="collection-product">
        <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" class="collection-product__img" onerror="this.src='<?php echo e(asset('assets/images/K ICONE.webp')); ?>'">
        <div>
          <div class="collection-product__name"><?php echo e($product->name); ?></div>
          <div class="collection-product__qty">Qté : <?php echo e($product->pivot->quantity ?? 1); ?></div>
        </div>
      </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div style="text-align:center;margin-bottom:2rem;display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <button class="collection-cta" onclick="addCollectionToCart(<?php echo e($collection->id); ?>)">
        🛒 Ajouter le pack au panier
      </button>
      <button class="collection-cta" style="background:var(--color-warm);" onclick="buyNowCollection(<?php echo e($collection->id); ?>)">
        ⚡ Acheter maintenant
      </button>
    </div>

    <div style="text-align:center;">
      <a href="<?php echo e(url('boutique')); ?>" class="collection-back">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Retour à la boutique
      </a>
    </div>
  </div>

</main>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="module" src="<?php echo e(asset('js/main.js')); ?>"></script>
<script>
function addCollectionToCart(collectionId) {
  var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  fetch('<?php echo e(url('panier/ajouter')); ?>', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded','X-CSRF-TOKEN':csrf},
    body: 'collection_id='+collectionId+'&quantity=1'
  }).then(function(){
    fetch('<?php echo e(url('panier/count')); ?>').then(r=>r.json()).then(d=>{
      var badge = document.getElementById('cart-badge');
      if (badge) badge.textContent = d.count;
    });
  }).catch(function(){});
}

function buyNowCollection(collectionId) {
  var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  fetch('<?php echo e(url('panier/ajouter')); ?>', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded','X-CSRF-TOKEN':csrf},
    body: 'collection_id='+collectionId+'&quantity=1'
  }).then(function(r){
    if (r.ok) window.location = '<?php echo e(url('paiement')); ?>';
  }).catch(function(){});
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\pages\collection-show.blade.php ENDPATH**/ ?>