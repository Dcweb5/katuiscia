@extends('layouts.public')
@section('title', 'Recherche — KATUISCIA')
@section('content')
<div class="pt-[calc(190px+3rem)] pb-20 max-w-site mx-auto px-4 lg:px-8">
  <h1 class="font-heading text-4xl font-light text-dark mb-8">Recherche</h1>
  <form method="GET" action="{{ url('recherche') }}" class="max-w-lg mb-12">
    <div class="flex items-center border border-dark/80 rounded-[10px] overflow-hidden h-12">
      <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Rechercher un produit..." class="bg-transparent px-4 font-body text-sm text-dark w-full outline-none placeholder:text-text-muted" autofocus>
      <button class="bg-peach border-l border-dark/80 w-12 h-full flex items-center justify-center text-dark hover:bg-[#ecdccf] transition-colors"><svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></button>
    </div>
  </form>
  @if(isset($products) && $products->count() > 0)
  <p class="text-text-muted mb-6" style="grid-column:1/-1;">{{ $products->total() }} résultat(s) pour "{{ $q }}"</p>
  <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6" style="grid-column:1/-1;">
    @foreach($products as $product)
    <article class="product-card reveal revealed">
      <div class="product-card__image-wrapper">
        <a href="{{ url('produit/' . $product->slug) }}" style="display:block;width:100%;height:100%;">
          @php $img = $product->images()->orderBy('order')->first(); @endphp
          <img src="{{ $img ? asset('storage/'.$img->path) : asset($product->image_primary ?: 'assets/images/K ICONE.png') }}"
               alt="{{ $product->name }}" class="product-card__image product-card__image--primary" loading="lazy" width="400" height="533"
               onerror="this.src='{{ asset('assets/images/K ICONE.png') }}'">
          @if($img = $product->images()->skip(1)->first())
          <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $product->name }}" class="product-card__image product-card__image--secondary" loading="lazy" width="400" height="533">
          @elseif($product->image_secondary)
          <img src="{{ asset($product->image_secondary) }}" alt="{{ $product->name }}" class="product-card__image product-card__image--secondary" loading="lazy" width="400" height="533">
          @endif
        </a>
        @if($product->badge)<span class="product-card__badge">{{ $product->badge }}</span>@endif
        <button class="product-card__quick-view">Aperçu rapide</button>
        <button class="product-card__add-cart cart-add-btn" data-product-id="{{ $product->id }}" data-quantity="1" aria-label="Ajouter au panier">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </button>
      </div>
      <div class="product-card__info">
        <a href="{{ url('produit/' . $product->slug) }}" style="text-decoration:none;color:inherit;flex-grow:1;">
          <span class="product-card__category">{{ $product->categories->first()->name ?? 'Produit' }}</span>
          <h3 class="product-card__name">{{ $product->name }}</h3>
          <p class="product-card__description">{{ $product->description }}</p>
          @if($product->sale_price && $product->sale_price > $product->price)
          <span class="product-card__price" style="color:var(--color-warm);font-weight:600;">{{ number_format($product->price, 2, ',', ' ') }} €</span>
          <span class="product-card__price-old" style="text-decoration:line-through;color:var(--color-text-muted);font-size:12px;margin-left:8px;">{{ number_format($product->sale_price, 2, ',', ' ') }} €</span>
          @else
          <span class="product-card__price">{{ number_format($product->price, 2, ',', ' ') }} €</span>
          @endif
        </a>
      </div>
    </article>
    @endforeach
  </div>
  {{ $products->links() }}
  @elseif(isset($q))
  <p class="text-center text-text-muted py-16">Aucun résultat pour "{{ $q }}".</p>
  @endif
</div>
@endsection
