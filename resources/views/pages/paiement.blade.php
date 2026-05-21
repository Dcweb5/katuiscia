@extends('layouts.public')

@section('title', 'Paiement — KATUISCIA')

@section('head')
<link rel="stylesheet" href="{{ asset('css/paiement.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="pt-[calc(190px+3rem)] pb-20 max-w-site mx-auto px-4 lg:px-8">
  <h1 class="font-heading text-4xl font-light text-dark mb-8 reveal-k-k">Finaliser la commande</h1>

  @if(session('error'))
  <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:var(--space-lg);">{{ session('error') }}</div>
  @endif
  @if($errors->any())
  <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:var(--space-lg);"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div class="flex flex-col lg:flex-row gap-12">
    <!-- Formulaire -->
    <div class="flex-1 reveal-k-k delay-1">
      <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="card p-6 space-y-4">
          <h3 class="font-heading text-lg mb-4">Contact</h3>
          <input type="email" name="email" class="admin-input" placeholder="Email *" value="{{ old('email', auth()->user()->email ?? '') }}" required>
        </div>

        <div class="card p-6 space-y-4" style="margin-top:var(--space-md);">
          <h3 class="font-heading text-lg mb-4">Adresse de livraison</h3>
          <div class="grid grid-cols-2 gap-4">
            <input type="text" name="firstname" class="admin-input" placeholder="Prénom *" value="{{ old('firstname', auth()->user()->firstname ?? '') }}" required>
            <input type="text" name="lastname" class="admin-input" placeholder="Nom *" value="{{ old('lastname', auth()->user()->lastname ?? '') }}" required>
            <input type="text" name="address" class="admin-input" placeholder="Adresse *" value="{{ old('address') }}" style="grid-column:1/-1;" required>
            <input type="text" name="address2" class="admin-input" placeholder="Appartement, étage..." value="{{ old('address2') }}" style="grid-column:1/-1;">
            <input type="text" name="postal_code" class="admin-input" placeholder="Code postal *" value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}" required>
            <input type="text" name="city" class="admin-input" placeholder="Ville *" value="{{ old('city', auth()->user()->city ?? '') }}" required>
            <input type="text" name="country" class="admin-input" placeholder="Pays *" value="{{ old('country', auth()->user()->country ?? 'FR') }}" required>
            <input type="text" name="phone" class="admin-input" placeholder="Téléphone" value="{{ old('phone', auth()->user()->phone ?? '') }}">
          </div>
        </div>

        <div class="card p-6 space-y-4" style="margin-top:var(--space-md);">
          <h3 class="font-heading text-lg mb-4">Paiement</h3>
          <label class="flex items-center gap-3 p-4 border border-border-k rounded-md cursor-pointer">
            <input type="radio" name="payment_method" value="card" checked style="accent-color:var(--color-warm);">
            <span>Carte bancaire</span>
          </label>
          <label class="flex items-center gap-3 p-4 border border-border-k rounded-md cursor-pointer">
            <input type="radio" name="payment_method" value="cod" style="accent-color:var(--color-warm);">
            <span>Paiement à la livraison</span>
          </label>
        </div>

        <button type="submit" class="btn-katuiscia-filled w-full" style="margin-top:var(--space-lg);">Payer maintenant</button>
      </form>
    </div>

    <!-- Récapitulatif -->
    <div class="lg:w-[380px] reveal-k-k delay-2">
      <div class="bg-white rounded-xl p-6 shadow-card space-y-4 sticky top-[220px]">
        <h3 class="font-heading text-xl">Votre commande</h3>
        @foreach($cart->items as $item)
        <div class="flex justify-between text-sm">
          <span class="text-text-muted">{{ $item->product->name ?? 'Produit' }} x{{ $item->quantity }}</span>
          <span>{{ number_format($item->subtotal, 0, ',', ' ') }} €</span>
        </div>
        @endforeach
        <hr class="border-border-k">
        <div class="flex justify-between text-sm">
          <span class="text-text-muted">Sous-total</span>
          <span>{{ number_format($cart->total, 0, ',', ' ') }} €</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-text-muted">Livraison</span>
          <span class="text-success">OFFERTE</span>
        </div>
        <hr class="border-border-k">
        <div style="display:flex;gap:var(--space-sm);">
          <input type="text" id="coupon-code" placeholder="Code promo" class="w-full p-3 border border-border-k rounded-md text-sm" value="{{ session('coupon.code', '') }}">
          <button type="button" id="apply-coupon" class="btn-katuiscia" style="font-size:12px;white-space:nowrap;">Appliquer</button>
        </div>
        <div id="coupon-message" style="display:none;font-size:12px;margin-top:4px;"></div>
        @php $cpDiscount = session('coupon.discount', 0); $cpTotal = max(0, $cart->total - $cpDiscount); @endphp
        @if(session('coupon'))
        <div style="padding:12px 16px;background:rgba(90,143,110,0.08);border:1px solid var(--color-success);border-radius:var(--radius-md);margin-bottom:var(--space-md);">
          <strong style="color:var(--color-success);font-size:14px;">🎫 Coupon appliqué : {{ session('coupon.code') }}</strong>
          <span style="display:block;font-size:12px;color:var(--color-text-muted);margin-top:4px;">Réduction : -{{ number_format(session('coupon.discount'), 0, ',', ' ') }} € ({{ session('coupon.label') }})</span>
        </div>
        @endif
        <div class="flex justify-between font-heading text-lg" id="total-line">
          <span>Total</span>
          <span>{{ number_format($cpTotal, 0, ',', ' ') }} €</span>
        </div>
        <input type="hidden" name="coupon_code" value="{{ session('coupon.code', '') }}">
        <input type="hidden" name="coupon_discount" value="{{ session('coupon.discount', 0) }}">
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
var cartTotal = {{ $cart->total }};
document.getElementById('apply-coupon').addEventListener('click', async function() {
  var code = document.getElementById('coupon-code').value.trim();
  var msg = document.getElementById('coupon-message');
  if (!code) return;

  var res = await fetch('/panier/coupon', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
    body: JSON.stringify({ code: code })
  });
  var data = await res.json();

  msg.style.display = 'block';
  if (data.valid) {
    msg.style.color = 'var(--color-success)';
    msg.textContent = '✅ ' + data.message;
    document.getElementById('coupon-hidden').value = code;
    document.getElementById('coupon-discount').value = data.discount_raw || 0;
    var newTotal = Math.max(0, cartTotal - (data.discount_raw || 0));
    document.getElementById('total-line').innerHTML = '<span>Total</span><span>' + newTotal.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' \u20AC</span>';
  } else {
    msg.style.color = 'var(--color-error)';
    msg.textContent = data.message;
    document.getElementById('coupon-hidden').value = '';
    document.getElementById('coupon-discount').value = '0';
    document.getElementById('total-line').innerHTML = '<span>Total</span><span>' + cartTotal.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' \u20AC</span>';
  }
});
</script>
@endsection
