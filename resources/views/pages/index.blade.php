@extends('layouts.public')

@section('title', 'KATUISCIA — Beauté Botanique de Luxe')
@section('description', 'Découvrez KATUISCIA, des formulations botaniques soignées pour élever votre rituel quotidien.')

@section('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')

<!-- ============ HERO SLIDESHOW ============ -->
<section class="relative min-h-screen flex items-center pt-header overflow-hidden" id="hero">
  <div class="absolute inset-0 bg-cream z-0 pointer-events-none">
    <div class="absolute top-[15%] right-[10%] w-[450px] h-[500px] bg-warm rounded-3xl -rotate-2 hidden lg:block" data-parallax="-0.15"></div>
  </div>

  <div class="relative z-10 max-w-site mx-auto px-4 lg:px-8 w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
    <div class="max-w-[500px] reveal-k-k">
      <p class="text-sm font-medium tracking-[0.2em] uppercase text-text-light mb-4">Collection Signature</p>
      <h1 class="font-heading text-4xl lg:text-5xl font-light leading-tight text-dark mb-6">
        L'excellence n'est pas<br>une promesse,<br><span class="text-warm">c'est un résultat.</span>
      </h1>
      <p class="text-base text-text-light leading-relaxed mb-8">Découvrez des soins capillaires conçus pour sublimer, nourrir et transformer.</p>
      <div class="flex items-center gap-6 mb-10">
        <a href="{{ url('boutique') }}" class="btn-katuiscia">Explorer la collection</a>
      </div>
      @if($heroProducts->isNotEmpty())
      <div class="bg-white p-5 rounded-2xl shadow-md inline-flex flex-col gap-1 max-w-[280px]">
        <span class="text-xs text-text-muted tracking-wide uppercase">Produit en vedette</span>
        <span class="font-heading text-xl font-medium text-dark transition-all duration-300" id="hero-tag-name">{{ $heroProducts->first()->name }}</span>
        <span class="text-md font-semibold text-warm transition-all duration-300" id="hero-tag-price">{{ number_format($heroProducts->first()->sale_price ?? $heroProducts->first()->price, 0, ',', ' ') }} €</span>
      </div>
      @endif
    </div>

    <div class="relative flex justify-center items-center min-h-[500px] reveal-k-k delay-2" id="hero-slideshow">
      @foreach($heroProducts as $i => $product)
      <a href="{{ url('produit/'.$product->slug) }}" class="hero-slide {{ $i === 0 ? 'active' : '' }}" data-name="{{ $product->name }}" data-price="{{ number_format($product->sale_price ?? $product->price, 0, ',', ' ') }} €">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
             class="max-w-[380px] w-full rounded-2xl shadow-xl transition-transform duration-500 ease-out-expo" loading="eager" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
      </a>
      @endforeach

      <div class="absolute -bottom-5 left-1/2 -translate-x-1/2 flex gap-2.5 z-[5]">
        @foreach($heroProducts as $i => $p)
        <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}" aria-label="Produit {{ $i+1 }}"></button>
        @endforeach
      </div>

      @if($heroProducts->count() > 2)
      <img src="{{ $heroProducts->get(2)->image_url }}" alt=""
           class="absolute top-0 -right-8 w-[130px] rounded-xl shadow-lg z-20 hidden lg:block animate-float" aria-hidden="true" data-parallax="-0.05" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
      @endif
      @if($heroProducts->count() > 3)
      <img src="{{ $heroProducts->get(3)->image_url }}" alt=""
           class="absolute bottom-8 -left-10 w-[110px] rounded-xl shadow-lg z-20 hidden lg:block animate-float-delayed" aria-hidden="true" data-parallax="-0.08" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
      @endif
    </div>
  </div>
</section>

<!-- ============ BEST-SELLERS & NOUVEAUTÉS ============ -->
<section class="py-24 bg-white" id="bestsellers">
  <div class="section-heading reveal-k-k">
    <h2>Nos Best-Sellers &amp; Nouveautés</h2>
    <div class="divider-line"></div>
    <p>Découvrez les formules les plus appréciées et les dernières créations conçues pour sublimer votre rituel.</p>
  </div>

  <div class="showcase-track flex overflow-x-auto lg:overflow-visible lg:justify-center gap-6 lg:gap-12 max-w-[1200px] mx-auto px-4 pb-12 snap-x snap-mandatory hide-scrollbar">
    @foreach($bestsellers as $product)
    <a href="{{ url('produit/'.$product->slug) }}" class="showcase-item group flex-none w-[280px] lg:w-[320px] snap-center relative flex flex-col items-center text-center transition-transform duration-300 ease-out-expo hover:-translate-y-2 reveal-k-k @if($loop->iteration > 1) delay-{{ $loop->iteration - 1 }} @endif">
      <div class="w-full h-[350px] lg:h-[400px] bg-gray-med rounded-2xl flex items-center justify-center p-8 transition-colors group-hover:bg-[#E2DFD9]">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
             class="max-h-full object-contain drop-shadow-xl transition-transform duration-500 group-hover:scale-110" loading="lazy" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
      </div>
      <div class="absolute -bottom-5 bg-white px-8 py-3 rounded-xl shadow-md flex flex-col min-w-[220px]">
        <span class="text-xs text-text-muted tracking-wider uppercase">🔥 Best-seller</span>
        <span class="font-heading text-lg font-medium text-dark">{{ $product->name }}</span>
      </div>
    </a>
    @endforeach
    @if($latestProduct)
    <a href="{{ url('produit/'.$latestProduct->slug) }}" class="showcase-item group flex-none w-[280px] lg:w-[320px] snap-center relative flex flex-col items-center text-center transition-transform duration-300 ease-out-expo hover:-translate-y-2 reveal-k-k delay-3">
      <div class="w-full h-[350px] lg:h-[400px] bg-gray-med rounded-2xl flex items-center justify-center p-8 transition-colors group-hover:bg-[#E2DFD9]">
        <img src="{{ $latestProduct->image_url }}" alt="{{ $latestProduct->name }}"
             class="max-h-full object-contain drop-shadow-xl transition-transform duration-500 group-hover:scale-110" loading="lazy" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
      </div>
      <div class="absolute -bottom-5 bg-white px-8 py-3 rounded-xl shadow-md flex flex-col min-w-[220px]">
        <span class="text-xs text-text-muted tracking-wider uppercase">🆕 Nouveauté</span>
        <span class="font-heading text-lg font-medium text-dark">{{ $latestProduct->name }}</span>
      </div>
    </a>
    @endif
  </div>

  <div class="flex lg:hidden justify-center gap-2 mt-12">
    @for($i = 0; $i < min($bestsellers->count() + ($latestProduct ? 1 : 0), 3); $i++)
    <button class="showcase-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></button>
    @endfor
  </div>
</section>

<!-- ============ SÉLECTION ORGANISÉE ============ -->
@if($selectionLarge)
<section class="py-24 bg-cream" id="curated">
  <div class="section-heading reveal-k-k">
    <h2>Sélection Organisée</h2>
    <div class="divider-line"></div>
    <p>Des formulations soigneusement sélectionnées pour des routines sur-mesure.</p>
  </div>

  <div class="max-w-[1200px] mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1.3fr_0.7fr_0.7fr] lg:grid-rows-2 gap-4">
    {{-- Large card --}}
    <div class="lg:row-span-2 relative rounded-xl overflow-hidden bg-white shadow-card group cursor-pointer transition-all duration-300 hover:shadow-lg hover:-translate-y-1 reveal-k-k">
      @if($selectionLargeType === 'collection')
      <div class="w-full h-full overflow-hidden bg-[#f5f2ee]"><a href="{{ url('collection/'.$selectionLarge->slug) }}" class="block w-full h-full flex items-center justify-center p-12">
        @if($selectionLarge->image_url)
        <img src="{{ $selectionLarge->image_url }}" alt="{{ $selectionLarge->name }}"
             class="max-w-full max-h-full object-contain transition-transform duration-700 group-hover:scale-105" style="min-height:350px;" loading="lazy">
        @else
        <div style="font-size:4rem;">📚</div>
        @endif
      </a></div>
      <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-dark/60 to-transparent text-white">
        <span class="text-xs text-gray-200">📚 Collection</span>
        <h3 class="font-heading text-xl font-medium">{{ $selectionLarge->name }}</h3>
        <p class="text-sm text-gray-200">{{ $selectionLarge->description }}</p>
      </div>
      <span class="absolute top-4 right-4 bg-white text-dark text-sm font-semibold px-4 py-1.5 rounded-full">{{ number_format($selectionLarge->price, 0, ',', ' ') }} €</span>
      @else
      <div class="w-full h-full overflow-hidden bg-[#f5f2ee]"><a href="{{ url('produit/'.$selectionLarge->slug) }}" class="block w-full h-full flex items-center justify-center p-12">
        <img src="{{ $selectionLarge->image_url }}" alt="{{ $selectionLarge->name }}"
             class="max-w-full max-h-full object-contain transition-transform duration-700 group-hover:scale-105" style="min-height:350px;" loading="lazy" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
      </a></div>
      <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-dark/60 to-transparent text-white">
        <span class="text-xs text-gray-200">🔥 Plus vendu</span>
        <h3 class="font-heading text-xl font-medium">{{ $selectionLarge->name }}</h3>
        <p class="text-sm text-gray-200">{{ $selectionLarge->description }}</p>
      </div>
      <span class="absolute top-4 right-4 bg-white text-dark text-sm font-semibold px-4 py-1.5 rounded-full">{{ number_format($selectionLarge->sale_price ?? $selectionLarge->price, 0, ',', ' ') }} €</span>
      @endif
    </div>

    {{-- Small cards --}}
    @foreach($selectionSmall as $item)
    <div class="relative rounded-xl overflow-hidden bg-white shadow-card group cursor-pointer transition-all duration-300 hover:shadow-lg hover:-translate-y-1 reveal-k-k delay-{{ $loop->iteration }}">
      @if($selectionType === 'collections')
      <a href="{{ url('collection/'.$item->slug) }}" class="block w-full h-[220px] overflow-hidden">
        @if($item->image_url)
        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
        @else
        <div style="width:100%;height:100%;background:linear-gradient(135deg, #faf7f2, #ede4db);display:flex;align-items:center;justify-content:center;font-size:2rem;">📦</div>
        @endif
      </a>
      <div class="p-4 flex flex-col gap-1">
        <span class="text-xs text-text-muted tracking-wider uppercase">📚 Collection</span>
        <h3 class="font-heading text-xl font-medium text-dark">{{ $item->name }}</h3>
        <span class="text-md font-semibold text-dark mt-1">{{ number_format($item->price, 0, ',', ' ') }} €</span>
      </div>
      @else
      <a href="{{ url('produit/'.$item->slug) }}" class="block w-full h-[220px] overflow-hidden">
        <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
      </a>
      <div class="p-4 flex flex-col gap-1">
        <span class="text-xs text-text-muted tracking-wider uppercase">{{ $item->categories->first()?->name ?? 'Soin' }}</span>
        <h3 class="font-heading text-xl font-medium text-dark">{{ $item->name }}</h3>
        <span class="text-md font-semibold text-dark mt-1">{{ number_format($item->sale_price ?? $item->price, 0, ',', ' ') }} €</span>
      </div>
      @endif
    </div>
    @endforeach
  </div>
  <div class="text-center mt-12 reveal-k-k"><a href="{{ url('boutique') }}" class="btn-katuiscia">Découvrir plus</a></div>
</section>
@endif

<!-- ============ NOTRE BOUTIQUE ============ -->
@if($boutiqueProducts->isNotEmpty())
<section class="py-24 bg-white" id="shoppreview">
  <div class="section-heading reveal-k-k">
    <h2>Notre Boutique</h2>
    <div class="divider-line"></div>
    <p>Explorez nos essentiels les plus demandés et trouvez le rituel parfait pour votre peau.</p>
  </div>
  <div class="max-w-site mx-auto px-4 lg:px-8 grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
    @foreach($boutiqueProducts as $product)
    <div class="product-card-k reveal-k-k delay-{{ $loop->iteration }}">
      <div class="card-image">
        <a href="{{ url('produit/'.$product->slug) }}" class="block w-full h-full">
          <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-primary" loading="lazy" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
        </a>
        @if($product->badge)<span class="card-badge">{{ $product->badge }}</span>@endif
        <button class="card-quick-view">Aperçu rapide</button>
        <button class="card-cart-btn cart-add-btn" data-product-id="{{ $product->id }}" data-quantity="1" aria-label="Ajouter au panier" style="background:none;border:none;color:inherit;cursor:pointer;padding:0;display:flex;align-items:center;justify-content:center;">
          <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </button>
      </div>
      <div class="card-info"><a href="{{ url('produit/'.$product->slug) }}" class="no-underline text-inherit flex-1">
        <span class="card-category">{{ $product->categories->first()?->name ?? 'Soin de la peau' }}</span>
        <h3 class="card-name">{{ $product->name }}</h3>
        <p class="card-desc">{{ $product->description }}</p>
        <span class="card-price">{{ number_format($product->sale_price ?? $product->price, 0, ',', ' ') }} €</span>
      </a></div>
    </div>
    @endforeach
  </div>
</section>
@endif

<!-- ============ PHILOSOPHIE ============ -->
<section class="bg-peach overflow-hidden" id="philosophy">
  <div class="max-w-site mx-auto grid grid-cols-1 lg:grid-cols-2">
    <div class="relative min-h-[400px] lg:min-h-[600px] reveal-k-k">
      <img src="{{ asset('assets/images/editorial-main.webp') }}" alt="Botanique" class="absolute w-full h-full object-cover" loading="lazy">
      <img src="{{ asset('assets/images/product-5a.webp') }}" alt="Soin" class="absolute -bottom-5 -right-8 w-32 lg:w-40 rounded-2xl shadow-xl z-10 border-4 border-peach" loading="lazy">
    </div>
    <div class="p-12 lg:p-24 flex flex-col justify-center reveal-k-k delay-1">
      <span class="text-xs font-semibold tracking-[0.2em] uppercase text-warm mb-4">Notre Philosophie</span>
      <h2 class="font-heading text-4xl lg:text-5xl font-light text-dark leading-tight mb-8">Formulé<br>avec Intention.</h2>
      <p class="text-base text-text-light leading-relaxed mb-8 max-w-md">Nous croyons que le soin de la peau va au-delà des apparences. Chaque produit KATUISCIA est conçu avec des ingrédients botaniques rigoureusement sélectionnés.</p>
      <div class="bg-white/50 backdrop-blur-sm p-6 rounded-xl flex items-center gap-6 mb-10 max-w-lg">
        <img src="{{ asset('assets/images/editorial-accent.webp') }}" alt="" class="w-20 h-20 rounded-lg object-cover flex-none" loading="lazy">
        <p class="font-heading italic text-dark leading-relaxed">"Ce qui est en harmonie avec la nature doit être aussi en harmonie avec votre peau."</p>
      </div>
      <a href="{{ url('maison') }}" class="btn-katuiscia self-start">Découvrir nos produits</a>
    </div>
  </div>
</section>

<!-- ============ FEATURES BAR ============ -->
<section class="py-12 bg-white border-y border-border-k" id="features">
  <div class="max-w-[1200px] mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
    <div class="flex flex-col items-center gap-3 reveal-k-k">
      <svg class="w-10 h-10 text-warm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M2 10h20"/></svg>
      <h3 class="text-sm font-semibold tracking-wide text-dark">Paiement sécurisé</h3>
      <p class="text-sm text-text-muted">Transactions protégées par cryptage SSL</p>
    </div>
    <div class="flex flex-col items-center gap-3 reveal-k-k delay-1">
      <svg class="w-10 h-10 text-warm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
      <h3 class="text-sm font-semibold tracking-wide text-dark">Livraison rapide</h3>
      <p class="text-sm text-text-muted">Expédition sous 72h, livraison 2-5 jours</p>
    </div>
    <div class="flex flex-col items-center gap-3 reveal-k-k delay-2">
      <svg class="w-10 h-10 text-warm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
      <h3 class="text-sm font-semibold tracking-wide text-dark">SAV bienveillant</h3>
      <p class="text-sm text-text-muted">À votre écoute 7j/7</p>
    </div>
  </div>
</section>

<!-- ============ STATS COUNTERS ============ -->
<section class="py-20 bg-dark text-cream">
  <div class="max-w-[1000px] mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
    <div class="reveal-k-k">
      <span class="block font-heading text-5xl font-light text-warm mb-2" data-counter="25000" data-suffix="+">0</span>
      <span class="text-sm text-cream/60 tracking-wider uppercase">Clients satisfaits</span>
    </div>
    <div class="reveal-k-k delay-1">
      <span class="block font-heading text-5xl font-light text-warm mb-2" data-counter="50" data-suffix="+">0</span>
      <span class="text-sm text-cream/60 tracking-wider uppercase">Produits exclusifs</span>
    </div>
    <div class="reveal-k-k delay-2">
      <span class="block font-heading text-5xl font-light text-warm mb-2" data-counter="98" data-suffix="%">0</span>
      <span class="text-sm text-cream/60 tracking-wider uppercase">Ingrédients naturels</span>
    </div>
    <div class="reveal-k-k delay-3">
      <span class="block font-heading text-5xl font-light text-warm mb-2" data-counter="15" data-suffix="">0</span>
      <span class="text-sm text-cream/60 tracking-wider uppercase">Pays livrés</span>
    </div>
  </div>
</section>

@endsection

@section('scripts')
<script type="module" src="{{ asset('js/index-dynamics.js') }}"></script>
@endsection
