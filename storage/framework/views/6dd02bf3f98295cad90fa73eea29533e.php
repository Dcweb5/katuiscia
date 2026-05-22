<?php $__env->startSection('title', 'Boutique — KATUISCIA'); ?>
<?php $__env->startSection('description', 'Découvrez notre sélection de soins naturels et artisanaux.'); ?>

<?php $__env->startSection('head'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/boutique.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="pt-[calc(190px+3rem)] pb-8 max-w-site mx-auto px-4 lg:px-8 reveal-k-k">
  <h1 class="font-heading text-4xl lg:text-5xl font-light text-dark mb-2">Boutique</h1>
  <p class="text-text-light text-md max-w-lg">Des formulations botaniques soignées.</p>
</div>

<?php if($collections->isNotEmpty()): ?>
<section class="max-w-site mx-auto px-4 lg:px-8 pb-12 reveal-k-k delay-1">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <h2 class="font-heading text-2xl lg:text-3xl font-light text-dark">Nos Collections</h2>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('collection.show', $collection->slug)); ?>" style="text-decoration:none;color:inherit;display:block;border-radius:14px;overflow:hidden;background:#fff;border:1px solid #ede4db;transition:all 0.25s;" class="reveal-k-k" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
      <div style="position:relative;height:220px;overflow:hidden;">
        <?php if($collection->image_url): ?>
        <img src="<?php echo e($collection->image_url); ?>" alt="<?php echo e($collection->name); ?>" style="width:100%;height:100%;object-fit:cover;">
        <?php else: ?>
        <div style="width:100%;height:100%;background:linear-gradient(135deg, #faf7f2, #ede4db);display:flex;align-items:center;justify-content:center;font-size:3rem;">📦</div>
        <?php endif; ?>
        <?php if($collection->discount_percent > 0): ?>
        <span style="position:absolute;top:12px;right:12px;background:#c62828;color:#fff;font-size:11px;font-weight:700;padding:4px 10px;border-radius:99px;">-<?php echo e($collection->discount_percent); ?>%</span>
        <?php endif; ?>
      </div>
      <div style="padding:1.25rem;">
        <span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#c4967a;"><?php echo e($collection->products->count()); ?> produit(s)</span>
        <h3 style="font-family:var(--font-heading);font-size:1.1rem;font-weight:400;color:#2d2117;margin:0.5rem 0;"><?php echo e($collection->name); ?></h3>
        <div style="display:flex;align-items:baseline;gap:0.5rem;">
          <?php if($collection->original_price && $collection->original_price > $collection->price): ?>
          <span style="font-size:14px;text-decoration:line-through;color:#a39688;"><?php echo e(number_format($collection->original_price, 0, ',', ' ')); ?> €</span>
          <?php endif; ?>
          <span style="font-family:var(--font-display);font-size:1.25rem;color:#2d2117;"><?php echo e(number_format($collection->price, 0, ',', ' ')); ?> €</span>
        </div>
        <span style="display:inline-flex;align-items:center;gap:0.35rem;margin-top:0.75rem;font-size:13px;font-weight:500;color:#8b6f5a;">Découvrir <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
      </div>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</section>
<?php endif; ?>

<section class="max-w-site mx-auto px-4 lg:px-8 pb-24">
  <div class="flex flex-col lg:flex-row gap-12">
    <!-- Filters sidebar -->
    <aside class="lg:w-[260px] flex-shrink-0 reveal-k-k" id="filters">
      <div class="lg:sticky lg:top-[220px] flex flex-col gap-8">
        <div>
          <h3 class="text-xs font-semibold tracking-[0.2em] uppercase text-dark mb-4">Catégories</h3>
          <div class="flex flex-col gap-2">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="cursor-pointer flex items-center gap-3 text-sm text-text-light hover:text-dark transition-colors">
              <input type="checkbox" class="filter-cat" value="<?php echo e($category->slug); ?>" onchange="applyFilters()" style="accent-color:var(--color-warm);">
              <?php echo e($category->name); ?>

            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
        <div>
          <h3 class="text-xs font-semibold tracking-[0.2em] uppercase text-dark mb-4">Besoins</h3>
          <div class="flex flex-col gap-2">
            <label class="cursor-pointer flex items-center gap-3 text-sm text-text-light hover:text-dark transition-colors"><input type="checkbox" class="filter-need" value="hydratation" onchange="applyFilters()" style="accent-color:var(--color-warm);"> Hydratation</label>
            <label class="cursor-pointer flex items-center gap-3 text-sm text-text-light hover:text-dark transition-colors"><input type="checkbox" class="filter-need" value="eclat" onchange="applyFilters()" style="accent-color:var(--color-warm);"> Éclat</label>
            <label class="cursor-pointer flex items-center gap-3 text-sm text-text-light hover:text-dark transition-colors"><input type="checkbox" class="filter-need" value="restauration" onchange="applyFilters()" style="accent-color:var(--color-warm);"> Restauration</label>
          </div>
        </div>
        <div>
          <h3 class="text-xs font-semibold tracking-[0.2em] uppercase text-dark mb-4">Prix</h3>
          <div class="flex items-center gap-2">
            <input type="number" id="price-min" class="w-full p-2 border border-border-k rounded-md text-sm" placeholder="Min" min="0" onchange="applyFilters()">
            <span class="text-text-muted">—</span>
            <input type="number" id="price-max" class="w-full p-2 border border-border-k rounded-md text-sm" placeholder="Max" min="0" onchange="applyFilters()">
          </div>
        </div>
        <button id="reset-filters" onclick="resetFilters()" class="text-xs text-text-muted hover:text-dark underline-offset-2 hover:underline text-left">Réinitialiser les filtres</button>
      </div>
    </aside>

    <!-- Products grid -->
    <div class="flex-1 min-w-0">
      <div class="flex items-center justify-between mb-8 reveal-k-k delay-1">
        <span class="text-sm text-text-muted" id="product-count"><?php echo e(count($products)); ?> produits</span>
        <select id="sort-select" class="p-2 border border-border-k rounded-md text-sm bg-transparent cursor-pointer" onchange="sortProducts()">
          <option value="order">Trier par : Notre sélection</option>
          <option value="price-asc">Prix croissant</option>
          <option value="price-desc">Prix décroissant</option>
          <option value="name">Nom A-Z</option>
        </select>
      </div>

      <button id="mobile-filter-toggle" onclick="document.getElementById('filters').classList.toggle('hidden')" class="lg:hidden w-full mb-6 py-3 border border-dark text-dark text-xs font-semibold tracking-widest uppercase rounded-full hover:bg-dark hover:text-cream transition-all duration-300">
        Filtrer & Trier
      </button>

      <div class="boutique__products grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6" id="products-grid">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <article class="product-card reveal revealed" data-id="<?php echo e($product->id); ?>"
          data-category="<?php echo e($product->categories->pluck('slug')->join(' ')); ?>"
          data-need="<?php echo e($product->need ?? ''); ?>"
          data-price="<?php echo e($product->price); ?>"
          data-name="<?php echo e($product->name); ?>"
          data-order="<?php echo e($loop->index); ?>">
          <div class="product-card__image-wrapper">
            <a href="<?php echo e(url('produit/' . $product->slug)); ?>" style="display:block; width:100%; height:100%;">
              <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>"
                   class="product-card__image product-card__image--primary" loading="lazy" width="400" height="533"
                   onerror="this.src='<?php echo e(asset('assets/images/K ICONE.png')); ?>'">
              <img src="<?php echo e(asset($product->image_secondary ?? $product->image_primary)); ?>" alt="<?php echo e($product->name); ?>"
                   class="product-card__image product-card__image--secondary" loading="lazy" width="400" height="533"
                   onerror="this.src='<?php echo e(asset('assets/images/K ICONE.png')); ?>'">
            </a>
            <?php if($product->badge): ?><span class="product-card__badge"><?php echo e($product->badge); ?></span><?php endif; ?>
            <button class="product-card__quick-view">Aperçu rapide</button>
            <button class="product-card__add-cart cart-add-btn" data-product-id="<?php echo e($product->id); ?>" data-quantity="1" aria-label="Ajouter au panier" style="background:none;border:none;color:inherit;cursor:pointer;padding:0;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </button>
          </div>
          <div class="product-card__info">
            <a href="<?php echo e(url('produit/' . $product->slug)); ?>" style="text-decoration:none; color:inherit; flex-grow:1;">
              <span class="product-card__category"><?php echo e($product->categories->first()->name ?? 'Produit'); ?></span>
              <h3 class="product-card__name"><?php echo e($product->name); ?></h3>
              <p class="product-card__description"><?php echo e($product->description); ?></p>
              <span class="product-card__price"><?php echo e(number_format($product->price, 0, ',', ' ')); ?> €</span>
            </a>
          </div>
        </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
(function() {
  var initialHTML = document.getElementById('products-grid').innerHTML;

  window.applyFilters = function() {
    var selectedCats = Array.from(document.querySelectorAll('.filter-cat:checked')).map(c => c.value);
    var selectedNeeds = Array.from(document.querySelectorAll('.filter-need:checked')).map(n => n.value);
    var priceMin = parseFloat(document.getElementById('price-min').value) || 0;
    var priceMax = parseFloat(document.getElementById('price-max').value) || Infinity;

    var visible = 0;
    document.querySelectorAll('#products-grid .product-card').forEach(function(card) {
      var cat = card.dataset.category || '';
      var need = card.dataset.need || '';
      var price = parseFloat(card.dataset.price) || 0;

      var matchesCat = selectedCats.length === 0 || selectedCats.some(function(c) { return cat.includes(c); });
      var matchesNeed = selectedNeeds.length === 0 || selectedNeeds.includes(need);
      var matchesPrice = price >= priceMin && price <= priceMax;

      if (matchesCat && matchesNeed && matchesPrice) {
        card.style.display = '';
        visible++;
      } else {
        card.style.display = 'none';
      }
    });

    document.getElementById('product-count').textContent = visible + ' produit' + (visible > 1 ? 's' : '');
  };

  window.sortProducts = function() {
    var sort = document.getElementById('sort-select').value;
    var grid = document.getElementById('products-grid');
    var cards = Array.from(grid.querySelectorAll('.product-card'));

    cards.sort(function(a, b) {
      if (sort === 'price-asc') return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
      if (sort === 'price-desc') return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
      if (sort === 'name') return (a.dataset.name || '').localeCompare(b.dataset.name || '');
      return parseInt(a.dataset.order) - parseInt(b.dataset.order);
    });

    cards.forEach(function(card) { grid.appendChild(card); });
  };

  window.resetFilters = function() {
    document.querySelectorAll('.filter-cat, .filter-need').forEach(function(c) { c.checked = false; });
    document.getElementById('price-min').value = '';
    document.getElementById('price-max').value = '';
    document.getElementById('sort-select').value = 'order';
    sortProducts();
    applyFilters();
  };

  // Activer les cartes au chargement
  document.querySelectorAll('#products-grid .product-card').forEach(function(card) {
    card.classList.add('revealed');
  });
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\pages\boutique.blade.php ENDPATH**/ ?>