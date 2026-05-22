@extends('layouts.admin')
@section('title', 'Sections de la page d\'accueil')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Sections de l'accueil</h1><p class="page-subtitle">Configurez les produits affichés dans chaque section.</p></div>
    <button type="submit" form="sections-form" class="btn-primary">Publier les changements</button>
  </div>

  <form id="sections-form" method="POST" action="{{ route('admin.sections.store') }}">
    @csrf

    {{-- HERO --}}
    <div class="card" style="margin-bottom:var(--space-xl);padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 0.5rem;">🎠 Hero Slider</h3>
      <p style="font-size:var(--text-sm);color:var(--color-text-muted);margin-bottom:1rem;">Produits qui défilent dans le carrousel principal (max 5).</p>

      <div class="product-pick-grid" id="hero-picks">
        @foreach($products as $p)
        <label class="product-pick {{ in_array($p->id, $sections->get('hero')?->product_ids ?? []) ? 'active' : '' }}">
          <input type="checkbox" name="hero_product_ids[]" value="{{ $p->id }}" @checked(in_array($p->id, $sections->get('hero')?->product_ids ?? [])) hidden>
          <img src="{{ $p->image_primary ? asset('storage/'.$p->image_primary) : asset('assets/images/K ICONE.webp') }}" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
          <span class="product-pick-name">{{ $p->name }}</span>
          <span class="product-pick-check">✓</span>
        </label>
        @endforeach
      </div>
    </div>

    {{-- BEST-SELLERS (info only) --}}
    <div class="card" style="margin-bottom:var(--space-xl);padding:var(--space-xl);background:var(--color-bg);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 0.25rem;">🏆 Best-Sellers & Nouveautés</h3>
      <p style="font-size:var(--text-sm);color:var(--color-text-muted);">Automatique — 2 plus vendus + 1 plus récent.</p>
      @if($mostSold->isNotEmpty())
      <div style="display:flex;gap:1rem;margin-top:0.75rem;font-size:13px;color:var(--color-text);">
        @foreach($mostSold->take(2) as $p)
        <span style="background:#fff;padding:4px 12px;border-radius:8px;border:1px solid var(--color-border);">🔥 {{ $p->name }}</span>
        @endforeach
        @if($latestProduct)
        <span style="background:#fff;padding:4px 12px;border-radius:8px;border:1px solid var(--color-border);">🆕 {{ $latestProduct->name }}</span>
        @endif
      </div>
      @else
      <p style="font-size:12px;color:var(--color-text-muted);margin-top:0.5rem;">Aucune vente pour le moment. Les produits seront affichés dès les premières commandes.</p>
      @endif
    </div>

    {{-- SÉLECTION ORGANISÉE --}}
    <div class="card" style="margin-bottom:var(--space-xl);padding:var(--space-xl);">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <div>
          <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0 0 0.25rem;">📐 Sélection Organisée</h3>
          <p style="font-size:var(--text-sm);color:var(--color-text-muted);">Gauche = plus vendu (auto). Droite = 4 produits ou collections au choix.</p>
        </div>
        <div style="display:flex;gap:0.5rem;">
          <button type="button" class="action-btn" onclick="switchTab('products')" id="tab-products" style="padding:6px 14px;height:auto;border-radius:8px;font-size:12px;">Produits</button>
          <button type="button" class="action-btn" onclick="switchTab('collections')" id="tab-collections" style="padding:6px 14px;height:auto;border-radius:8px;font-size:12px;">Collections</button>
        </div>
      </div>

      <div id="tab-products-content">
        <div class="product-pick-grid">
          @foreach($products as $p)
          <label class="product-pick {{ in_array($p->id, $sections->get('selection')?->product_ids ?? []) ? 'active' : '' }}">
            <input type="checkbox" name="selection_product_ids[]" value="{{ $p->id }}" @checked(in_array($p->id, $sections->get('selection')?->product_ids ?? [])) hidden>
            <img src="{{ $p->image_primary ? asset('storage/'.$p->image_primary) : asset('assets/images/K ICONE.webp') }}" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
            <span class="product-pick-name">{{ $p->name }}</span>
            <span class="product-pick-check">✓</span>
          </label>
          @endforeach
        </div>
      </div>
      <div id="tab-collections-content" style="display:none;">
        <div class="product-pick-grid">
          @foreach($collections as $c)
          <label class="product-pick {{ in_array($c->id, $sections->get('selection')?->collection_ids ?? []) ? 'active' : '' }}">
            <input type="checkbox" name="selection_collection_ids[]" value="{{ $c->id }}" @checked(in_array($c->id, $sections->get('selection')?->collection_ids ?? [])) hidden>
            <div style="width:80px;height:56px;background:var(--color-peach);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">📦</div>
            <span class="product-pick-name">{{ $c->name }}</span>
            <span class="product-pick-check">✓</span>
          </label>
          @endforeach
        </div>
      </div>
    </div>

  </form>
</div>

<style>
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
  document.getElementById('tab-products').style.borderColor = tab === 'products' ? 'var(--color-warm)' : '';
  document.getElementById('tab-collections').style.borderColor = tab === 'collections' ? 'var(--color-warm)' : '';
}

document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-sections') l.classList.add('active'); });
</script>
@endsection
