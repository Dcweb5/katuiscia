@extends('layouts.public')

@section('title', 'Nos Collections — KATUISCIA')
@section('description', 'Découvrez toutes nos collections de soins naturels et artisanaux.')

@section('head')
<link rel="stylesheet" href="{{ asset('css/boutique.css') }}">
<style>
  /* Extra styling for collections list page */
  .collections-grid {
    margin-bottom: 5rem;
  }
</style>
@endsection

@section('content')
<div class="pt-[calc(190px+3rem)] pb-8 max-w-site mx-auto px-4 lg:px-8 reveal-k-k">
  <h1 class="font-heading text-4xl lg:text-5xl font-light text-dark mb-2">Nos Collections</h1>
  <p class="text-text-light text-md max-w-lg">Des rituels complets et des coffrets de soins botaniques.</p>
</div>

<section class="max-w-site mx-auto px-4 lg:px-8 pb-24 reveal-k-k delay-1">
  @if($collections->isNotEmpty())
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 collections-grid">
    @foreach($collections as $collection)
    <div style="border-radius:14px;overflow:hidden;background:#fff;border:1px solid #ede4db;transition:all 0.25s;position:relative;" class="collection-card reveal-k-k" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
      <div style="position:relative;height:220px;overflow:hidden;">
        <a href="{{ route('collection.show', $collection->slug) }}" style="display:block;width:100%;height:100%;">
          @if($collection->image_url)
          <img src="{{ $collection->image_url }}" alt="{{ $collection->name }}" style="width:100%;height:100%;object-fit:cover;">
          @else
          <div style="width:100%;height:100%;background:linear-gradient(135deg, #faf7f2, #ede4db);display:flex;align-items:center;justify-content:center;font-size:3rem;">📦</div>
          @endif
        </a>
        @if($collection->discount_percent > 0)
        <span style="position:absolute;top:12px;right:12px;background:#c62828;color:#fff;font-size:11px;font-weight:700;padding:4px 10px;border-radius:99px;z-index:4;">-{{ $collection->discount_percent }}%</span>
        @endif
        <button class="product-card__add-cart cart-add-btn" data-collection-id="{{ $collection->id }}" data-quantity="1" aria-label="Ajouter au panier" style="background:none;border:none;color:inherit;cursor:pointer;padding:0;position:absolute;bottom:12px;right:12px;background:white;padding:8px;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="20" height="20"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </button>
      </div>
      <a href="{{ route('collection.show', $collection->slug) }}" style="text-decoration:none;color:inherit;display:block;padding:1.25rem;">
        <span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#c4967a;">{{ $collection->products->count() }} produit(s)</span>
        <h3 style="font-family:var(--font-heading);font-size:1.1rem;font-weight:400;color:#2d2117;margin:0.5rem 0;">{{ $collection->name }}</h3>
        <div style="display:flex;align-items:baseline;gap:0.5rem;">
          @if($collection->original_price && $collection->original_price > $collection->price)
          <span style="font-size:14px;text-decoration:line-through;color:#a39688;">{{ number_format($collection->original_price, 2, ',', ' ') }} €</span>
          @endif
          <span style="font-family:var(--font-display);font-size:1.25rem;color:#2d2117;">{{ number_format($collection->price, 2, ',', ' ') }} €</span>
        </div>
        <span style="display:inline-flex;align-items:center;gap:0.35rem;margin-top:0.75rem;font-size:13px;font-weight:500;color:#8b6f5a;">Découvrir <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
      </a>
    </div>
    @endforeach
  </div>
  @else
  <div style="text-align:center;padding:5rem 1rem;background:#fff;border-radius:14px;border:1px solid #ede4db;">
    <span style="font-size:3rem;display:block;margin-bottom:1rem;">📦</span>
    <p class="text-text-muted">Aucune collection n'est disponible pour le moment.</p>
  </div>
  @endif
</section>
@endsection
