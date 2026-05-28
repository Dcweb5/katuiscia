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
        <div class="card p-6" style="border:1px solid #ede4db;">
          <h3 class="font-heading text-lg mb-4">Contact</h3>
          <div class="admin-form-group">
            <label class="k-label">Email *</label>
            <input type="email" name="email" class="k-input" placeholder="votre@email.com" value="{{ old('email', auth()->user()->email ?? '') }}" required>
          </div>
        </div>

        <div class="card p-6" style="margin-top:var(--space-md);border:1px solid #ede4db;">
          <h3 class="font-heading text-lg mb-4">Adresse de livraison</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="admin-form-group">
              <label class="k-label">Prénom *</label>
              <input type="text" name="firstname" class="k-input" placeholder="" value="{{ old('firstname', auth()->user()->firstname ?? '') }}" required>
            </div>
            <div class="admin-form-group">
              <label class="k-label">Nom *</label>
              <input type="text" name="lastname" class="k-input" value="{{ old('lastname', auth()->user()->lastname ?? '') }}" required>
            </div>
            <div class="admin-form-group" style="grid-column:1/-1;">
              <label class="k-label">Adresse *</label>
              <input type="text" name="address" class="k-input" value="{{ old('address') }}" required>
            </div>
            <div class="admin-form-group" style="grid-column:1/-1;">
              <label class="k-label">Complément</label>
              <input type="text" name="address2" class="k-input" placeholder="Appartement, étage..." value="{{ old('address2') }}">
            </div>
            <div class="admin-form-group">
              <label class="k-label">Pays *</label>
              <select name="country" class="k-input" required onchange="updatePostalPhone(this.value)">
                <option value="">Sélectionnez un pays</option>
                @foreach(['FR'=>'🇫🇷 France','BE'=>'🇧🇪 Belgique','CH'=>'🇨🇭 Suisse','LU'=>'🇱🇺 Luxembourg','DE'=>'🇩🇪 Allemagne','ES'=>'🇪🇸 Espagne','IT'=>'🇮🇹 Italie','PT'=>'🇵🇹 Portugal','GB'=>'🇬🇧 Royaume-Uni','US'=>'🇺🇸 États-Unis','CA'=>'🇨🇦 Canada','DZ'=>'🇩🇿 Algérie','CM'=>'🇨🇲 Cameroun','CI'=>'🇨🇮 Côte d\'Ivoire','CD'=>'🇨🇩 RD Congo','SN'=>'🇸🇳 Sénégal','MA'=>'🇲🇦 Maroc','TN'=>'🇹🇳 Tunisie'] as $code=>$name)
                <option value="{{ $code }}" @selected(old('country', auth()->user()->country ?? 'FR') == $code)>{{ $name }}</option>
                @endforeach
              </select>
            </div>
            <div class="admin-form-group">
              <label class="k-label">Code postal *</label>
              <input type="text" name="postal_code" id="input-postal" class="k-input" value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}" required placeholder="Ex: 75001">
            </div>
            <div class="admin-form-group">
              <label class="k-label">Ville *</label>
              <input type="text" name="city" class="k-input" value="{{ old('city', auth()->user()->city ?? '') }}" required>
            </div>
            <div class="admin-form-group">
              <label class="k-label">Téléphone</label>
              <input type="tel" name="phone" id="input-phone" class="k-input" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="+33 6 12 34 56 78">
            </div>
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
        <div style="margin-bottom:1rem;">
          <label class="k-label">Code promo</label>
          <div style="display:flex;gap:0.5rem;">
            <input type="text" id="coupon-code" class="k-input" style="flex:1;" placeholder="Entrez votre code..." value="{{ session('coupon.code', '') }}">
            <button type="button" id="apply-coupon" class="btn-katuiscia" style="font-size:12px;white-space:nowrap;">Appliquer</button>
          </div>
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
var postalPatterns = { FR: /^\d{5}$/, BE: /^\d{4}$/, CH: /^\d{4}$/, LU: /^\d{4}$/, DE: /^\d{5}$/, ES: /^\d{5}$/, IT: /^\d{5}$/, PT: /^\d{4}-\d{3}$/, GB: /^[A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}$/i, US: /^\d{5}(-\d{4})?$/, CA: /^[A-Z]\d[A-Z] ?\d[A-Z]\d$/i };
function updatePostalPhone(country) {
  var postal = document.getElementById('input-postal');
  var phone = document.getElementById('input-phone');
  var p = postalPatterns[country];
  if (p) { postal.pattern = p.source; postal.title = 'Format : ' + {FR:'5 chiffres',BE:'4 chiffres',GB:'ex: SW1A 1AA',US:'5 chiffres',CA:'ex: K1A 0B1'}[country] || p.source; }
  else { postal.removeAttribute('pattern'); postal.removeAttribute('title'); }
  var phonePrefix = {FR:'+33','BE':'+32','CH':'+41','LU':'+352','DE':'+49','ES':'+34','IT':'+39','GB':'+44','US':'+1','CA':'+1'}[country];
  if (phonePrefix && !phone.value) phone.placeholder = phonePrefix + ' 6 12 34 56 78';
}
document.addEventListener('DOMContentLoaded', function(){ updatePostalPhone(document.querySelector('select[name="country"]').value); });
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
    document.querySelector('input[name="coupon_code"]').value = code;
    document.querySelector('input[name="coupon_discount"]').value = data.discount_raw || 0;
    var newTotal = Math.max(0, cartTotal - (data.discount_raw || 0));
    document.getElementById('total-line').innerHTML = '<span>Total</span><span>' + newTotal.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' \u20AC</span>';
  } else {
    msg.style.color = 'var(--color-error)';
    msg.textContent = data.message;
    document.querySelector('input[name="coupon_code"]').value = '';
    document.querySelector('input[name="coupon_discount"]').value = '0';
    document.getElementById('total-line').innerHTML = '<span>Total</span><span>' + cartTotal.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' \u20AC</span>';
  }
});
</script>
@endsection
