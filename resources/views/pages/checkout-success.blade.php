@extends('layouts.public')
@section('title', 'Commande confirmée — KATUISCIA')
@section('content')
<div class="pt-[calc(190px+4rem)] pb-20 max-w-site mx-auto px-4 lg:px-8 text-center">
  <div class="reveal-k-k">
    <div style="font-size:64px;margin-bottom:var(--space-md);">✅</div>
    <h1 class="font-heading text-4xl font-light text-dark mb-4">Commande confirmée !</h1>
    <p class="text-text-light text-lg mb-2">Merci pour votre commande, {{ $order->firstname }}.</p>
    <p class="text-text-muted mb-8">Numéro de commande : <strong>{{ $order->order_number }}</strong></p>

    <div class="max-w-lg mx-auto bg-white rounded-xl p-8 shadow-card text-left mb-8">
      <h3 class="font-heading text-lg mb-4">Récapitulatif</h3>
      @foreach($order->items as $item)
      <div class="flex justify-between text-sm py-2 border-b border-border-k"><span>{{ $item->product_name }} x{{ $item->quantity }}</span><span>{{ number_format($item->subtotal, 0, ',', ' ') }} €</span></div>
      @endforeach
      @if($order->discount > 0)
      <div class="flex justify-between text-sm py-2 border-b border-border-k"><span class="text-text-muted">Réduction ({{ $order->coupon_code }})</span><span style="color:var(--color-success);">-{{ number_format($order->discount, 0, ',', ' ') }} €</span></div>
      @endif
      <div class="flex justify-between text-sm py-2"><span class="text-text-muted">Livraison</span><span class="text-success">OFFERTE</span></div>
      <div class="flex justify-between font-heading text-lg mt-4 pt-4 border-t border-dark"><span>Total</span><span>{{ number_format($order->total, 0, ',', ' ') }} €</span></div>
      <div class="mt-4 text-sm text-text-muted">
        <p>📧 {{ $order->email }}</p>
        <p>📍 {{ $order->address }}, {{ $order->postal_code }} {{ $order->city }}</p>
        <p>💳 {{ $order->payment_method === 'cod' ? 'Paiement à la livraison' : 'Carte bancaire' }}</p>
      </div>
    </div>

    <div style="display:flex;gap:var(--space-md);justify-content:center;flex-wrap:wrap;margin-bottom:var(--space-2xl);">
      @auth<a href="{{ url('compte/commandes') }}" class="btn-katuiscia">Suivre ma commande</a>@endauth
      <a href="{{ route('track') }}?order_number={{ $order->order_number }}&email={{ urlencode($order->email) }}" class="btn-katuiscia">📦 Suivi de commande</a>
      <a href="{{ url('boutique') }}" class="btn-katuiscia-filled">Continuer mes achats</a>
    </div>

    <!-- Map -->
    <div class="max-w-2xl mx-auto rounded-xl overflow-hidden shadow-lg mb-8 reveal-k-k delay-1">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2634.5!2d2.35!3d48.68!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDjCsDQwJzQ4LjAiTiAywrAyMScwMC4wIkU!5e0!3m2!1sfr!2sfr!4v1" class="w-full h-[300px] border-0" allowfullscreen="" loading="lazy" title="Localisation KATUISCIA"></iframe>
    </div>
    <p class="text-xs text-text-muted">📍 9 bis route de Corbeil, 91360 Villemoisson-sur-Orge</p>
  </div>
</div>
@endsection
