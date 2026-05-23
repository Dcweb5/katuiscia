@extends('layouts.public')

@section('title', ($product ? $product->name . ' — ' : '') . 'KATUISCIA')
@section('description', $product->description ?? 'Découvrez nos soins Katuiscia.')

@section('head')
<link rel="stylesheet" href="{{ asset('css/produit.css') }}">
@if($product)
<style>
  .gallery-thumb { width: 80px; height: 80px; border-radius: var(--radius-md); overflow: hidden; border: 2px solid transparent; cursor: pointer; opacity: 0.6; transition: all 0.3s; }
  .gallery-thumb.active, .gallery-thumb:hover { border-color: var(--color-dark); opacity: 1; }
  .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
</style>
@endif
@endsection

@section('content')
@if($product)
<!-- PRODUCT DETAIL -->
<section class="pt-[calc(190px+2rem)] pb-16 max-w-site mx-auto px-4 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">

  <!-- Gallery -->
  <div class="reveal-k-k">
    @php
      $images = $product->images()->orderBy('order')->get();
      $mainImage = $images->first();
    @endphp
    <div class="relative rounded-2xl overflow-hidden bg-gray-k aspect-square">
      @if($product->badge)
      <span class="absolute top-4 left-4 z-10 bg-warm text-white text-xs font-semibold tracking-wider uppercase px-4 py-1.5 rounded-full">{{ $product->badge }}</span>
      @endif
      <img src="{{ $mainImage ? asset('storage/' . $mainImage->path) : asset($product->image_primary ?: 'assets/images/product-1a.webp') }}"
           alt="{{ $product->name }}" class="w-full h-full object-cover transition-opacity duration-500" id="main-product-image">
    </div>
    @if($images->count() > 1 || $product->image_secondary)
    <div class="flex gap-3 mt-4" id="gallery-thumbs">
      @foreach($images->take(5) as $idx => $img)
      <button class="gallery-thumb {{ $idx === 0 ? 'active' : '' }}" data-src="{{ asset('storage/' . $img->path) }}">
        <img src="{{ asset('storage/' . $img->path) }}" alt="">
      </button>
      @endforeach
      @if($images->isEmpty() && $product->image_secondary)
      <button class="gallery-thumb" data-src="{{ asset($product->image_secondary) }}">
        <img src="{{ asset($product->image_secondary) }}" alt="">
      </button>
      @endif
    </div>
    @endif
  </div>

  <!-- Info -->
  <div class="reveal-k-k delay-1">
    <nav class="flex items-center gap-2 text-xs text-text-muted tracking-wider uppercase mb-6" aria-label="Fil d'ariane">
      <a href="{{ url('boutique') }}" class="hover:text-dark transition-colors">BOUTIQUE</a>
      <span>/</span>
      <span class="text-dark">{{ $product->categories->first()->name ?? 'Produit' }}</span>
    </nav>

    <h1 class="font-heading text-4xl lg:text-5xl font-light text-dark leading-tight mb-6">{{ $product->name }}</h1>

    <div class="flex items-baseline gap-4 mb-6">
      <span class="text-3xl font-semibold text-dark">{{ number_format($product->price, 0, ',', ' ') }} €</span>
      @if($product->sale_price)
      <span class="text-lg text-text-muted line-through">{{ number_format($product->sale_price, 0, ',', ' ') }} €</span>
      @endif
    </div>

    <p class="text-base text-text-light leading-relaxed mb-8">{{ $product->long_description ?? $product->description }}</p>

    @if($product->size)
    <p class="text-sm text-text-muted mb-2">Contenance : {{ $product->size }}</p>
    @endif

    <!-- Quantity & Actions -->
    <div class="space-y-4 mb-8">
      <div class="flex items-center gap-4">
        <span class="text-xs font-semibold tracking-wider uppercase text-dark">QUANTITÉ:</span>
        <div class="join border border-border-k rounded-lg">
          <button class="join-item btn btn-sm btn-ghost" id="qty-minus">-</button>
          <input type="number" class="join-item w-12 text-center bg-transparent text-sm border-x border-border-k outline-none" id="qty-input" value="1" min="1" max="10">
          <button class="join-item btn btn-sm btn-ghost" id="qty-plus">+</button>
        </div>
      </div>
      <button type="button" class="btn-katuiscia-filled w-full !justify-center cart-add-btn" data-product-id="{{ $product->id }}" id="add-to-cart-btn">AJOUTER AU PANIER</button>
      <button type="button" class="btn-katuiscia-warm w-full !justify-center" onclick="buyNow({{ $product->id }})" style="margin-top:var(--space-sm);">⚡ ACHETER MAINTENANT</button>
      <a href="{{ url('paiement') }}" class="btn-katuiscia-warm w-full !justify-center">ACHETER MAINTENANT</a>
    </div>

    <div class="flex gap-6 text-sm text-text-light mb-8">
      <span class="flex items-center gap-2"><svg class="w-4 h-4 text-warm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg> Livraison Gratuite</span>
      <span class="flex items-center gap-2"><svg class="w-4 h-4 text-warm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Authenticité Garantie</span>
    </div>

    @if($product->ingredients)
    <div class="space-y-0 border-t border-border-k">
      <details class="group border-b border-border-k">
        <summary class="flex items-center justify-between py-4 cursor-pointer text-sm font-semibold tracking-wide uppercase text-dark">
          <span>Ingrédients Clés</span>
          <svg class="w-4 h-4 text-text-muted transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
        </summary>
        <div class="pb-4 text-sm text-text-light leading-relaxed">{{ $product->ingredients }}</div>
      </details>
    </div>
    @endif
  </div>
</section>

<!-- AVIS -->
<section class="py-20 bg-white">
  <div class="max-w-site mx-auto px-4 lg:px-8">
    <div class="flex items-center justify-between mb-12 reveal-k-k">
      <h2 class="font-heading text-3xl font-normal text-dark">Avis clients</h2>
      @auth
      <button class="btn-katuiscia text-xs" onclick="document.getElementById('review-form-section').style.display='block';this.style.display='none';">Laisser un avis</button>
      @endauth
    </div>

    @auth
    <div id="review-form-section" style="display:none;max-width:500px;margin:0 auto var(--space-2xl);">
      <div class="bg-cream rounded-xl p-6">
        <h3 class="font-heading text-lg mb-4">Votre avis</h3>
        <form method="POST" action="{{ route('reviews.store', $product) }}">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <div class="admin-form-group"><label class="admin-label">Note</label><select name="rating" class="admin-input" required><option value="5">⭐⭐⭐⭐⭐ (5)</option><option value="4">⭐⭐⭐⭐ (4)</option><option value="3">⭐⭐⭐ (3)</option><option value="2">⭐⭐ (2)</option><option value="1">⭐ (1)</option></select></div>
          <div class="admin-form-group"><label class="admin-label">Titre</label><input type="text" name="title" class="admin-input" required placeholder="Résumez votre avis..."></div>
          <div class="admin-form-group"><label class="admin-label">Commentaire</label><textarea name="body" class="admin-input" rows="3" placeholder="Partagez votre expérience..."></textarea></div>
          <button type="submit" class="btn-katuiscia-filled w-full">Publier mon avis (+50 pts)</button>
        </form>
      </div>
    </div>
    @else
    <p class="text-center text-text-muted mb-8 reveal-k-k"><a href="{{ url('connexion') }}" class="text-warm underline">Connectez-vous</a> pour laisser un avis et gagner 50 points.</p>
    @endauth

    @php $reviews = $product->reviews()->where('is_approved', true)->orderBy('created_at','desc')->get(); @endphp
    @if($reviews->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @foreach($reviews as $review)
      <div class="bg-white rounded-xl p-6 shadow-card reveal-k-k">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:var(--space-sm);">
          <div>
            <strong style="font-size:var(--text-sm);">{{ $review->user->firstname ?? 'Client' }}</strong>
            <span style="color:var(--color-warm);margin-left:8px;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5-$review->rating) }}</span>
          </div>
          <span style="font-size:11px;color:var(--color-text-muted);">{{ $review->created_at->format('d/m/Y') }}</span>
        </div>
        <strong style="font-size:var(--text-sm);color:var(--color-dark);display:block;margin-bottom:4px;">{{ $review->title }}</strong>
        @if($review->body)<p style="font-size:var(--text-sm);color:var(--color-text-light);line-height:1.5;">{{ $review->body }}</p>@endif
      </div>
      @endforeach
    </div>
    @else
    <p class="text-center text-text-muted py-8">Aucun avis pour ce produit. Soyez le premier !</p>
    @endif
  </div>
</section>

<!-- UPSELL -->
@if($related->count() > 0)
<section class="py-20 bg-white">
  <div class="max-w-site mx-auto px-4 lg:px-8">
    <div class="flex items-center justify-between mb-12 reveal-k-k">
      <h2 class="font-heading text-3xl font-normal text-dark">Complétez Votre Rituel</h2>
      <a href="{{ url('boutique') }}" class="text-xs font-medium tracking-wider uppercase text-text-muted hover:text-dark transition-colors hidden lg:block">VOIR TOUT</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach($related as $rp)
      <a href="{{ url('produit/' . $rp->slug) }}" class="group block rounded-xl overflow-hidden bg-cream shadow-card hover:shadow-lg transition-all hover:-translate-y-1 reveal-k-k delay-{{ $loop->index }}">
        <div class="h-[260px] bg-gray-k flex items-center justify-center p-6">
          <img src="{{ $rp->images->first() ? asset('storage/' . $rp->images->first()->path) : asset($rp->image_primary ?: 'assets/images/product-1a.webp') }}"
               alt="{{ $rp->name }}" class="max-h-full object-contain transition-transform duration-500 group-hover:scale-110" loading="lazy">
        </div>
        <div class="p-5"><h3 class="font-heading text-lg font-medium text-dark">{{ $rp->name }}</h3><p class="text-sm text-text-light">{{ $rp->description }}</p><span class="text-md font-semibold text-dark mt-2 block">{{ number_format($rp->price, 0, ',', ' ') }} €</span></div>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@else
<div class="text-center py-24 pt-[calc(190px+4rem)]">
  <h1 class="font-heading text-3xl text-dark mb-4">Produit</h1>
  <p class="text-text-light">Sélectionnez un produit depuis la boutique.</p>
  <a href="{{ url('boutique') }}" class="btn-katuiscia mt-8">Voir la boutique</a>
</div>
@endif
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
<script>
function buyNow(productId) {
  var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  fetch('{{ url('panier/ajouter') }}', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded','X-CSRF-TOKEN':csrf},
    body: 'product_id='+productId+'&quantity=1'
  }).then(function(r){
    if (r.ok) window.location = '{{ url('paiement') }}';
  }).catch(function(){});
}
document.addEventListener('DOMContentLoaded', function() {
  var mainImg = document.getElementById('main-product-image');
  document.querySelectorAll('.gallery-thumb').forEach(function(thumb) {
    thumb.addEventListener('click', function() {
      document.querySelectorAll('.gallery-thumb').forEach(function(t) { t.classList.remove('active'); });
      this.classList.add('active');
      mainImg.style.opacity = '0';
      setTimeout(function() { mainImg.src = thumb.dataset.src; mainImg.style.opacity = '1'; }, 200);
    });
  });
  var qty = document.getElementById('qty-input');
  document.getElementById('qty-minus')?.addEventListener('click', function() { var v = parseInt(qty.value); if (v > 1) qty.value = v - 1; document.getElementById('add-to-cart-btn').dataset.quantity = qty.value; });
  document.getElementById('qty-plus')?.addEventListener('click', function() { var v = parseInt(qty.value); if (v < 10) qty.value = v + 1; document.getElementById('add-to-cart-btn').dataset.quantity = qty.value; });
});
</script>
@endsection
