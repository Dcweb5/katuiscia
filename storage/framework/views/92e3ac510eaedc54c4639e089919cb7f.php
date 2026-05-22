<?php $__env->startSection('title', 'Sections de la page d\'accueil'); ?>
<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Sections de l'accueil</h1><p class="page-subtitle">Configurez les produits affichés dans chaque section.</p></div>
    <button type="submit" form="sections-form" class="btn-primary">Publier les changements</button>
  </div>

  <form id="sections-form" method="POST" action="<?php echo e(route('admin.sections.store')); ?>">
    <?php echo csrf_field(); ?>

    
    <div class="card" style="margin-bottom:var(--space-xl);padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 0.5rem;">🎠 Hero Slider</h3>
      <p style="font-size:var(--text-sm);color:var(--color-text-muted);margin-bottom:1rem;">Produits qui défilent dans le carrousel principal (max 5).</p>

      <div class="product-pick-grid" id="hero-picks">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <label class="product-pick <?php echo e(in_array($p->id, $sections->get('hero')?->product_ids ?? []) ? 'active' : ''); ?>">
          <input type="checkbox" name="hero_product_ids[]" value="<?php echo e($p->id); ?>" <?php if(in_array($p->id, $sections->get('hero')?->product_ids ?? [])): echo 'checked'; endif; ?> hidden>
          <img src="<?php echo e($p->image_url); ?>" onerror="this.src='<?php echo e(asset('assets/images/K ICONE.webp')); ?>'">
          <span class="product-pick-name"><?php echo e($p->name); ?></span>
          <span class="product-pick-check">✓</span>
        </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    
    <div class="card" style="margin-bottom:var(--space-xl);padding:var(--space-xl);background:var(--color-bg);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 0.25rem;">🏆 Best-Sellers & Nouveautés</h3>
      <p style="font-size:var(--text-sm);color:var(--color-text-muted);">Automatique — 2 plus vendus + 1 plus récent.</p>
      <?php if($mostSold->isNotEmpty()): ?>
      <div style="display:flex;gap:1rem;margin-top:0.75rem;font-size:13px;color:var(--color-text);">
        <?php $__currentLoopData = $mostSold->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span style="background:#fff;padding:4px 12px;border-radius:8px;border:1px solid var(--color-border);">🔥 <?php echo e($p->name); ?></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if($latestProduct): ?>
        <span style="background:#fff;padding:4px 12px;border-radius:8px;border:1px solid var(--color-border);">🆕 <?php echo e($latestProduct->name); ?></span>
        <?php endif; ?>
      </div>
      <?php else: ?>
      <p style="font-size:12px;color:var(--color-text-muted);margin-top:0.5rem;">Aucune vente pour le moment. Les produits seront affichés dès les premières commandes.</p>
      <?php endif; ?>
    </div>

    
    <div class="card" style="margin-bottom:var(--space-xl);padding:var(--space-xl);">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <div>
          <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 0.25rem;">📐 Sélection Organisée</h3>
          <p style="font-size:var(--text-sm);color:var(--color-text-muted);">Gauche = plus vendu (auto). Droite = 4 produits ou collections au choix.</p>
        </div>
        <div style="display:flex;gap:0;">
          <button type="button" onclick="switchTab('products')" id="tab-products" class="tab-btn tab-btn--active">📦 Produits</button>
          <button type="button" onclick="switchTab('collections')" id="tab-collections" class="tab-btn">📚 Collections</button>
        </div>
      </div>

      <div id="tab-products-content">
        <div class="product-pick-grid">
          <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <label class="product-pick <?php echo e(in_array($p->id, $sections->get('selection')?->product_ids ?? []) ? 'active' : ''); ?>">
            <input type="checkbox" name="selection_product_ids[]" value="<?php echo e($p->id); ?>" <?php if(in_array($p->id, $sections->get('selection')?->product_ids ?? [])): echo 'checked'; endif; ?> hidden>
            <img src="<?php echo e($p->image_url); ?>" onerror="this.src='<?php echo e(asset('assets/images/K ICONE.webp')); ?>'">
            <span class="product-pick-name"><?php echo e($p->name); ?></span>
            <span class="product-pick-check">✓</span>
          </label>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
      <div id="tab-collections-content" style="display:none;">
        <div class="product-pick-grid">
          <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <label class="product-pick <?php echo e(in_array($c->id, $sections->get('selection')?->collection_ids ?? []) ? 'active' : ''); ?>">
            <input type="checkbox" name="selection_collection_ids[]" value="<?php echo e($c->id); ?>" <?php if(in_array($c->id, $sections->get('selection')?->collection_ids ?? [])): echo 'checked'; endif; ?> hidden>
            <?php if($c->image_url): ?>
            <img src="<?php echo e($c->image_url); ?>" style="width:80px;height:56px;border-radius:6px;object-fit:cover;">
            <?php else: ?>
            <div style="width:80px;height:56px;background:var(--color-peach);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">📦</div>
            <?php endif; ?>
            <span class="product-pick-name"><?php echo e($c->name); ?></span>
            <span class="product-pick-check">✓</span>
          </label>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </div>

  </form>
</div>

<style>
.tab-btn { padding:10px 20px;border:2px solid #d1d5db;background:#fff;cursor:pointer;font-size:14px;font-weight:500;color:#6b7280;transition:all 0.15s; }
.tab-btn:first-child { border-radius:10px 0 0 10px;border-right:none; }
.tab-btn:last-child { border-radius:0 10px 10px 0; }
.tab-btn--active { background:var(--color-warm);border-color:var(--color-warm);color:#fff;font-weight:600; }

.product-pick-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:0.75rem; }
.product-pick { display:flex;flex-direction:column;align-items:center;gap:0.5rem;padding:1rem 0.75rem;border:2px solid var(--color-border);border-radius:12px;cursor:pointer;transition:all 0.15s;position:relative; }
.product-pick:hover { border-color:var(--color-warm); }
.product-pick.active { border-color:var(--color-warm);background:rgba(196,150,122,0.06); }
.product-pick img { width:80px;height:56px;border-radius:8px;object-fit:cover; }
.product-pick-check { position:absolute;top:8px;right:8px;width:20px;height:20px;border-radius:50%;background:var(--color-border);color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;opacity:0;transition:all 0.15s; }
.product-pick.active .product-pick-check { background:var(--color-warm);opacity:1; }
.product-pick-name { font-size:12px;font-weight:500;color:var(--color-text);text-align:center;line-height:1.3; }
</style>

<script>
document.querySelectorAll('.product-pick').forEach(function(el){
  el.addEventListener('click', function(){
    var cb = this.querySelector('input[type="checkbox"]');
    cb.checked = !cb.checked;
    this.classList.toggle('active', cb.checked);
  });
});

function switchTab(tab) {
  document.getElementById('tab-products-content').style.display = tab === 'products' ? '' : 'none';
  document.getElementById('tab-collections-content').style.display = tab === 'collections' ? '' : 'none';
  document.getElementById('tab-products').classList.toggle('tab-btn--active', tab === 'products');
  document.getElementById('tab-collections').classList.toggle('tab-btn--active', tab === 'collections');
}

document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-sections') l.classList.add('active'); });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\sections\index.blade.php ENDPATH**/ ?>