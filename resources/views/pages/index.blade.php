@extends('layouts.public')
@section('title', 'KATUISCIA — Beauté Botanique')
@section('head')
<link rel="stylesheet" href="{{ asset('css/boutique.css') }}">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection
@section('content')
<main id="main-content">

  {{-- ===== HERO SLIDER ===== --}}
  <section class="relative pt-[190px] pb-16 px-4 lg:px-8 max-w-site mx-auto overflow-hidden">
    <div style="display:flex;align-items:center;justify-content:center;gap:1rem;min-height:450px;position:relative;">
      <button class="w-10 h-10 rounded-full border border-dark flex items-center justify-center bg-transparent cursor-pointer flex-shrink-0 z-10 hover:bg-dark hover:text-cream transition-colors" onclick="slideChange(1)" aria-label="Précédent"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></button>

      <div id="hero-slides" style="flex:1;max-width:750px;position:relative;overflow:hidden;min-height:420px;">
        @foreach($heroProducts as $i => $product)
        <a href="{{ url('produit/'.$product->slug) }}" class="hero-slide {{ $i === 0 ? 'active' : '' }}" style="text-decoration:none;color:inherit;display:block;text-align:center;">
          @php
            $imgSrc = $product->image_primary;
            if ($imgSrc && !str_starts_with($imgSrc, 'http') && !str_starts_with($imgSrc, 'assets/') && !str_starts_with($imgSrc, '/')) {
              $imgSrc = asset('storage/'.$imgSrc);
            } elseif (!$imgSrc) {
              $imgSrc = asset('assets/images/K ICONE.webp');
            }
          @endphp
          <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="mx-auto" style="max-height:380px;width:auto;border-radius:20px;object-fit:contain;" loading="eager" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
          <div style="margin-top:1rem;">
            <span style="font-size:11px;text-transform:uppercase;letter-spacing:0.15em;color:var(--color-text-muted);">{{ $product->categories->first()?->name ?? 'Soin' }}</span>
            <h2 style="font-family:var(--font-heading);font-size:2rem;font-weight:400;color:var(--color-dark);margin:0.25rem 0;">{{ $product->name }}</h2>
            <span style="font-family:var(--font-display);font-size:1.25rem;color:var(--color-dark);">{{ $product->sale_price ? number_format($product->sale_price,0,',',' ').' €' : number_format($product->price,0,',',' ').' €' }}</span>
          </div>
        </a>
        @endforeach
      </div>

      <button class="w-10 h-10 rounded-full border border-dark flex items-center justify-center bg-transparent cursor-pointer flex-shrink-0 z-10 hover:bg-dark hover:text-cream transition-colors" onclick="slideChange(-1)" aria-label="Suivant"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></button>
    </div>

    <div id="hero-dots" style="display:flex;justify-content:center;gap:0.5rem;margin-top:1.5rem;">
      @foreach($heroProducts as $i => $p)
      <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" onclick="slideTo({{ $i }})"></button>
      @endforeach
    </div>
  </section>

  {{-- ===== BEST-SELLERS & NOUVEAUTÉS ===== --}}
  <section class="max-w-site mx-auto px-4 lg:px-8 pb-16 reveal-k-k">
    <div style="text-align:center;margin-bottom:2.5rem;">
      <h2 class="font-heading text-3xl font-light text-dark mb-2">Nos Best-Sellers & Nouveautés</h2>
      <p class="text-text-muted text-sm max-w-md mx-auto">Les produits les plus appréciés et nos dernières créations.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;">
      @foreach($bestsellers as $product)
      <a href="{{ url('produit/'.$product->slug) }}" class="product-card-k" style="text-decoration:none;">
        <div class="card-image">
          <span class="card-badge">🔥 Best-seller</span>
          <img src="{{ asset($product->image_primary ? 'storage/'.$product->image_primary : 'assets/images/K ICONE.webp') }}" class="img-primary" loading="lazy" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
        </div>
        <div class="card-info">
          <span class="card-category">{{ $product->categories->first()?->name ?? '' }}</span>
          <h3 class="card-name">{{ $product->name }}</h3>
          <span class="card-price">{{ $product->sale_price ? number_format($product->sale_price,0,',',' ').' €' : number_format($product->price,0,',',' ').' €' }}</span>
        </div>
      </a>
      @endforeach
      @if($latestProduct)
      <a href="{{ url('produit/'.$latestProduct->slug) }}" class="product-card-k" style="text-decoration:none;">
        <div class="card-image">
          <span class="card-badge" style="background:#e8f5e9;color:#2e7d32;">🆕 Nouveauté</span>
          <img src="{{ asset($latestProduct->image_primary ? 'storage/'.$latestProduct->image_primary : 'assets/images/K ICONE.webp') }}" class="img-primary" loading="lazy" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
        </div>
        <div class="card-info">
          <span class="card-category">{{ $latestProduct->categories->first()?->name ?? '' }}</span>
          <h3 class="card-name">{{ $latestProduct->name }}</h3>
          <span class="card-price">{{ $latestProduct->sale_price ? number_format($latestProduct->sale_price,0,',',' ').' €' : number_format($latestProduct->price,0,',',' ').' €' }}</span>
        </div>
      </a>
      @endif
    </div>
  </section>

  {{-- ===== SÉLECTION ORGANISÉE ===== --}}
  @if($selectionLarge)
  <section class="max-w-site mx-auto px-4 lg:px-8 pb-16 reveal-k-k delay-1">
    <div style="text-align:center;margin-bottom:2.5rem;">
      <h2 class="font-heading text-3xl font-light text-dark mb-2">Sélection Organisée</h2>
      <p class="text-text-muted text-sm max-w-md mx-auto">Nos incontournables, choisis avec soin.</p>
    </div>

    <div style="display:grid;grid-template-columns:1.5fr 1fr;gap:1.5rem;">
      {{-- Left: Large --}}
      <a href="{{ url('produit/'.$selectionLarge->slug) }}" style="display:block;position:relative;border-radius:20px;overflow:hidden;background:var(--color-gray-k);text-decoration:none;color:inherit;min-height:420px;">
        <img src="{{ asset($selectionLarge->image_primary ? 'storage/'.$selectionLarge->image_primary : 'assets/images/K ICONE.webp') }}" alt="{{ $selectionLarge->name }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;" loading="lazy">
        <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.6));padding:2rem 1.5rem 1.5rem;">
          <span style="font-size:10px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.8);">🔥 Le plus vendu</span>
          <h3 style="font-family:var(--font-heading);font-size:1.5rem;font-weight:400;color:#fff;margin:0.25rem 0;">{{ $selectionLarge->name }}</h3>
          <span style="font-size:1.1rem;color:#fff;">{{ $selectionLarge->sale_price ? number_format($selectionLarge->sale_price,0,',',' ').' €' : number_format($selectionLarge->price,0,',',' ').' €' }}</span>
        </div>
      </a>

      {{-- Right: 2x2 grid of selection products --}}
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
        @foreach($selectionSmall as $p)
        <a href="{{ url('produit/'.$p->slug) }}" style="display:block;position:relative;border-radius:16px;overflow:hidden;background:var(--color-gray-k);text-decoration:none;color:inherit;aspect-ratio:1/1;">
          <img src="{{ asset($p->image_primary ? 'storage/'.$p->image_primary : 'assets/images/K ICONE.webp') }}" alt="{{ $p->name }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;" loading="lazy">
          <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.5));padding:1rem 0.75rem 0.75rem;">
            <h4 style="font-family:var(--font-heading);font-size:1rem;font-weight:400;color:#fff;margin:0;">{{ $p->name }}</h4>
            <span style="font-size:0.85rem;color:rgba(255,255,255,0.9);">{{ $p->sale_price ? number_format($p->sale_price,0,',',' ').' €' : number_format($p->price,0,',',' ').' €' }}</span>
          </div>
        </a>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- ===== NOTRE BOUTIQUE ===== --}}
  @if($boutiqueProducts->isNotEmpty())
  <section class="max-w-site mx-auto px-4 lg:px-8 pb-20 reveal-k-k delay-2">
    <div style="text-align:center;margin-bottom:2.5rem;">
      <h2 class="font-heading text-3xl font-light text-dark mb-2">Notre Boutique</h2>
      <p class="text-text-muted text-sm max-w-md mx-auto">Un produit de chaque catégorie, sélectionné pour vous.</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
      @foreach($boutiqueProducts as $product)
      <article class="product-card reveal-k-k" data-id="{{ $product->id }}" data-category="{{ $product->categories->first()?->slug ?? '' }}" data-price="{{ $product->price }}" data-name="{{ $product->name }}">
        <div class="product-card__image-wrapper">
          <a href="{{ url('produit/'.$product->slug) }}" style="display:block;width:100%;height:100%;">
            <img src="{{ asset($product->image_primary ? 'storage/'.$product->image_primary : 'assets/images/K ICONE.webp') }}" alt="{{ $product->name }}" class="product-card__image product-card__image--primary" loading="lazy" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
          </a>
        </div>
        <div class="product-card__content">
          <span class="product-card__category">{{ $product->categories->first()?->name ?? '' }}</span>
          <h3 class="product-card__title"><a href="{{ url('produit/'.$product->slug) }}">{{ $product->name }}</a></h3>
          <p class="product-card__desc">{{ Str::limit($product->description, 60) }}</p>
          <div class="product-card__footer">
            <span class="product-card__price">{{ $product->sale_price ? number_format($product->sale_price,0,',',' ').' €' : number_format($product->price,0,',',' ').' €' }}</span>
            <button class="product-card__atc" onclick="addToCart({{ $product->id }})" aria-label="Ajouter au panier"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></button>
          </div>
        </div>
      </article>
      @endforeach
    </div>
  </section>
  @endif

  {{-- ===== PHILOSOPHIE ===== --}}
  <section class="max-w-[1200px] mx-auto px-4 lg:px-8 py-20">
    <div class="flex flex-col md:flex-row gap-12 items-center">
      <div class="flex-1 reveal-k-k">
        <img src="{{ asset('assets/images/editorial-main.webp') }}" alt="" class="w-full max-h-[500px] object-cover rounded-2xl shadow-lg" loading="lazy">
      </div>
      <div class="flex-1 reveal-k-k delay-1" style="max-width:480px;">
        <span class="inline-block text-xs font-semibold tracking-[0.2em] uppercase text-warm mb-3">Notre Philosophie</span>
        <h2 class="font-heading text-3xl lg:text-4xl font-light text-dark mb-6">L'Art du Rituel</h2>
        <p class="text-base text-text-light leading-relaxed mb-4">Chez KATUISCIA, nous croyons que la beauté n'est pas un résultat mais un processus. Chaque soin est une invitation à ralentir, à se reconnecter à soi.</p>
        <a href="{{ url('maison') }}" class="btn-katuiscia mt-4">Découvrir notre histoire</a>
      </div>
    </div>
  </section>

  {{-- ===== FEATURES ===== --}}
  <div style="display:flex;justify-content:center;gap:4rem;padding:3rem 1.5rem;border-top:1px solid var(--color-border);max-width:1000px;margin:0 auto;">
    <div style="text-align:center;"><span style="font-size:1.5rem;">🔒</span><p style="font-size:12px;color:var(--color-text-muted);margin-top:0.5rem;">Paiement sécurisé</p></div>
    <div style="text-align:center;"><span style="font-size:1.5rem;">🚚</span><p style="font-size:12px;color:var(--color-text-muted);margin-top:0.5rem;">Livraison offerte dès 80€</p></div>
    <div style="text-align:center;"><span style="font-size:1.5rem;">💬</span><p style="font-size:12px;color:var(--color-text-muted);margin-top:0.5rem;">Support 7j/7</p></div>
  </div>

</main>
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
<script>
var currentSlide = 0;
var totalSlides = {{ $heroProducts->count() }};

function slideTo(n) {
  currentSlide = n;
  updateSlides();
}
function slideChange(dir) {
  currentSlide = (currentSlide + dir + totalSlides) % totalSlides;
  updateSlides();
}
function updateSlides() {
  document.querySelectorAll('.hero-slide').forEach(function(s,i){
    s.classList.toggle('active', i === currentSlide);
  });
  document.querySelectorAll('.hero-dot').forEach(function(d,i){
    d.classList.toggle('active', i === currentSlide);
  });
}
if (totalSlides > 1) {
  setInterval(function(){ slideChange(1); }, 5000);
}
</script>
<script src="{{ asset('js/cart-ajax.js') }}"></script>
@endsection
