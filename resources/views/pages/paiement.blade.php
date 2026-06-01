@extends('layouts.public')

@php
$countryList = [
    'France' => [
        'FR' => '🇫🇷 France',
    ],
    'Union Européenne' => [
        'AT' => '🇦🇹 Autriche',
        'BE' => '🇧🇪 Belgique',
        'BG' => '🇧🇬 Bulgarie',
        'CY' => '🇨🇾 Chypre',
        'HR' => '🇭🇷 Croatie',
        'DK' => '🇩🇰 Danemark',
        'ES' => '🇪🇸 Espagne',
        'EE' => '🇪🇪 Estonie',
        'FI' => '🇫🇮 Finlande',
        'GR' => '🇬🇷 Grèce',
        'HU' => '🇭🇺 Hongrie',
        'IE' => '🇮🇪 Irlande',
        'IT' => '🇮🇹 Italie',
        'LV' => '🇱🇻 Lettonie',
        'LT' => '🇱🇹 Lituanie',
        'LU' => '🇱🇺 Luxembourg',
        'MT' => '🇲🇹 Malte',
        'NL' => '🇳🇱 Pays-Bas',
        'PL' => '🇵🇱 Pologne',
        'PT' => '🇵🇹 Portugal',
        'CZ' => '🇨🇿 République Tchèque',
        'RO' => '🇷🇴 Roumanie',
        'SK' => '🇸🇰 Slovaquie',
        'SI' => '🇸🇮 Slovénie',
        'SE' => '🇸🇪 Suède',
        'DE' => '🇩🇪 Allemagne',
    ],
    'Europe (hors UE)' => [
        'AL' => '🇦🇱 Albanie',
        'AD' => '🇦🇩 Andorre',
        'BA' => '🇧🇦 Bosnie-Herzégovine',
        'BY' => '🇧🇾 Biélorussie',
        'CH' => '🇨🇭 Suisse',
        'GB' => '🇬🇧 Royaume-Uni',
        'IS' => '🇮🇸 Islande',
        'LI' => '🇱🇮 Liechtenstein',
        'MC' => '🇲🇨 Monaco',
        'MD' => '🇲🇩 Moldavie',
        'ME' => '🇲🇪 Monténégro',
        'MK' => '🇲🇰 Macédoine du Nord',
        'NO' => '🇳🇴 Norvège',
        'RS' => '🇷🇸 Serbie',
        'SM' => '🇸🇲 Saint-Marin',
        'UA' => '🇺🇦 Ukraine',
        'VA' => '🇻🇦 Vatican',
    ],
    'Amériques' => [
        'AR' => '🇦🇷 Argentine',
        'BO' => '🇧🇴 Bolivie',
        'BR' => '🇧🇷 Brésil',
        'CA' => '🇨🇦 Canada',
        'CL' => '🇨🇱 Chili',
        'CO' => '🇨🇴 Colombie',
        'CR' => '🇨🇷 Costa Rica',
        'DO' => '🇩🇴 République Dominicaine',
        'EC' => '🇪🇨 Équateur',
        'GT' => '🇬🇹 Guatemala',
        'HN' => '🇭🇳 Honduras',
        'HT' => '🇭🇹 Haïti',
        'JM' => '🇯🇲 Jamaïque',
        'MX' => '🇲🇽 Mexique',
        'NI' => '🇳🇮 Nicaragua',
        'PA' => '🇵🇦 Panama',
        'PE' => '🇵🇪 Pérou',
        'PR' => '🇵🇷 Porto Rico',
        'PY' => '🇵🇾 Paraguay',
        'SV' => '🇸🇻 Salvador',
        'US' => '🇺🇸 États-Unis',
        'UY' => '🇺🇾 Uruguay',
        'VE' => '🇻🇪 Venezuela',
    ],
    'Afrique' => [
        'AO' => '🇦🇴 Angola',
        'BF' => '🇧🇫 Burkina Faso',
        'BJ' => '🇧🇯 Bénin',
        'BI' => '🇧🇮 Burundi',
        'BW' => '🇧🇼 Botswana',
        'CD' => '🇨🇩 RD Congo',
        'CG' => '🇨🇬 Congo-Brazzaville',
        'CI' => '🇨🇮 Côte d\'Ivoire',
        'CM' => '🇨🇲 Cameroun',
        'DJ' => '🇩🇯 Djibouti',
        'DZ' => '🇩🇿 Algérie',
        'EG' => '🇪🇬 Égypte',
        'ER' => '🇪🇷 Érythrée',
        'ET' => '🇪🇹 Éthiopie',
        'GA' => '🇬🇦 Gabon',
        'GH' => '🇬🇭 Ghana',
        'GN' => '🇬🇳 Guinée',
        'KE' => '🇰🇪 Kenya',
        'LR' => '🇱🇷 Libéria',
        'LS' => '🇱🇸 Lesotho',
        'LY' => '🇱🇾 Libye',
        'MA' => '🇲🇦 Maroc',
        'MG' => '🇲🇬 Madagascar',
        'ML' => '🇲🇱 Mali',
        'MR' => '🇲🇷 Mauritanie',
        'MW' => '🇲🇼 Malawi',
        'MZ' => '🇲🇿 Mozambique',
        'NA' => '🇳🇦 Namibie',
        'NE' => '🇳🇪 Niger',
        'NG' => '🇳🇬 Nigeria',
        'RW' => '🇷🇼 Rwanda',
        'SD' => '🇸🇩 Soudan',
        'SL' => '🇸🇱 Sierra Leone',
        'SN' => '🇸🇳 Sénégal',
        'SO' => '🇸🇴 Somalie',
        'SZ' => '🇸🇿 Eswatini',
        'TD' => '🇹🇩 Tchad',
        'TG' => '🇹🇬 Togo',
        'TN' => '🇹🇳 Tunisie',
        'TZ' => '🇹🇿 Tanzanie',
        'UG' => '🇺🇬 Ouganda',
        'ZA' => '🇿🇦 Afrique du Sud',
        'ZM' => '🇿🇲 Zambie',
        'ZW' => '🇿🇼 Zimbabwe',
    ],
    'Asie & Océanie' => [
        'AF' => '🇦🇫 Afghanistan',
        'AE' => '🇦🇪 Émirats Arabes Unis',
        'AU' => '🇦🇺 Australie',
        'BD' => '🇧🇩 Bangladesh',
        'BH' => '🇧🇭 Bahreïn',
        'KH' => '🇰🇭 Cambodge',
        'CN' => '🇨🇳 Chine',
        'FJ' => '🇫🇯 Fidji',
        'HK' => '🇭🇰 Hong Kong',
        'ID' => '🇮🇩 Indonésie',
        'IL' => '🇮🇱 Israël',
        'IN' => '🇮🇳 Inde',
        'IR' => '🇮🇷 Iran',
        'IQ' => '🇮🇶 Irak',
        'JO' => '🇯🇴 Jordanie',
        'JP' => '🇯🇵 Japon',
        'KG' => '🇰🇬 Kirghizistan',
        'KR' => '🇰🇷 Corée du Sud',
        'KW' => '🇰🇼 Koweït',
        'KZ' => '🇰🇿 Kazakhstan',
        'LA' => '🇱🇦 Laos',
        'LB' => '🇱🇧 Liban',
        'LK' => '🇱🇰 Sri Lanka',
        'MO' => '🇲🇴 Macao',
        'MM' => '🇲🇲 Myanmar',
        'MY' => '🇲🇾 Malaisie',
        'NP' => '🇳🇵 Népal',
        'NZ' => '🇳🇿 Nouvelle-Zélande',
        'OM' => '🇴🇲 Oman',
        'PG' => '🇵🇬 Papouasie-Nouvelle-Guinée',
        'PH' => '🇵🇭 Philippines',
        'PK' => '🇵🇰 Pakistan',
        'QA' => '🇶🇦 Qatar',
        'SA' => '🇸🇦 Arabie Saoudite',
        'SB' => '🇸🇧 Îles Salomon',
        'SG' => '🇸🇬 Singapour',
        'SY' => '🇸🇾 Syrie',
        'TH' => '🇹🇭 Thaïlande',
        'TJ' => '🇹🇯 Tadjikistan',
        'TR' => '🇹🇷 Turquie',
        'TW' => '🇹🇼 Taïwan',
        'TM' => '🇹🇲 Turkménistan',
        'UZ' => '🇺🇿 Ouzbékistan',
        'VU' => '🇻🇺 Vanuatu',
        'VN' => '🇻🇳 Viêt Nam',
        'YE' => '🇾🇪 Yémen',
    ]
];
foreach ($countryList as $zone => &$countries) {
    asort($countries);
}
$selectedCountryCode = old('country', auth()->user()->country ?? 'FR');
$selectedCountryName = 'France';
foreach ($countryList as $zone => $countries) {
  if (isset($countries[$selectedCountryCode])) {
    $selectedCountryName = $countries[$selectedCountryCode];
    break;
  }
}
@endphp

@section('title', 'Paiement — KATUISCIA')

@section('head')
<link rel="stylesheet" href="{{ asset('css/paiement.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* Custom Country Selector styles */
.custom-select-wrapper {
  position: relative;
  width: 100%;
}

.custom-select-trigger {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fff;
  border: 1px solid #ede4db;
  border-radius: 8px;
  padding: 12px 16px;
  font-size: 14px;
  color: #3d2928;
  cursor: pointer;
  transition: all 0.2s ease;
  height: 48px;
  min-height: auto;
  box-sizing: border-box;
}

.custom-select-trigger:focus, .custom-select-trigger:active {
  border-color: var(--color-warm, #c29970);
  box-shadow: 0 0 0 2px rgba(194, 153, 112, 0.1);
  outline: none;
}

.custom-select-options {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  background: #fff;
  border: 1px solid #ede4db;
  border-radius: 8px;
  margin-top: 6px;
  box-shadow: 0 10px 25px rgba(61, 41, 40, 0.08);
  z-index: 999;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.custom-select-options.hidden {
  display: none;
}

.custom-select-search-container {
  padding: 8px;
  background-color: #fff;
  border-bottom: 1px solid #f5ede6;
}

#country-search {
  width: 100%;
  height: 38px;
  padding: 8px 12px;
  border: 1px solid #ede4db;
  border-radius: 6px;
  font-size: 14px;
  outline: none;
  background-color: #fff;
  box-sizing: border-box;
}

#country-search:focus {
  border-color: var(--color-warm, #c29970);
}

.custom-select-list {
  max-height: 250px;
  overflow-y: auto;
}

.custom-select-list::-webkit-scrollbar {
  width: 6px;
}

.custom-select-list::-webkit-scrollbar-track {
  background: #faf7f2;
}

.custom-select-list::-webkit-scrollbar-thumb {
  background: #e3d8cc;
  border-radius: 3px;
}

.custom-select-group-header {
  padding: 6px 12px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #8c7e74;
  background-color: #faf7f2;
  border-bottom: 1px solid #ede4db;
  border-top: 1px solid #ede4db;
}

.custom-select-option {
  padding: 10px 16px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.15s ease;
  color: #3d2928;
}

.custom-select-option:hover {
  background-color: #f7f2eb;
}

.custom-select-option.selected {
  background-color: #ede4db;
  font-weight: 600;
}
</style>
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
        <input type="hidden" name="coupon_code" id="hidden-coupon-code" value="{{ session('coupon.code', '') }}">
        <input type="hidden" name="coupon_discount" id="hidden-coupon-discount" value="{{ session('coupon.discount', 0) }}">
        
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
            <div class="admin-form-group relative" id="country-group" style="position: relative;">
              <label class="k-label">Pays *</label>
              <input type="hidden" name="country" id="input-country" value="{{ $selectedCountryCode }}" required>
              <div class="custom-select-wrapper">
                <button type="button" class="custom-select-trigger" id="country-trigger">
                  <span id="country-trigger-text">{{ $selectedCountryName }}</span>
                  <svg style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="custom-select-options hidden" id="country-options-panel">
                  <div class="custom-select-search-container">
                    <input type="text" id="country-search" placeholder="Rechercher un pays...">
                  </div>
                  <div class="custom-select-list">
                    @foreach($countryList as $zone => $countries)
                      <div class="custom-select-group">
                        <div class="custom-select-group-header">
                          {{ $zone }}
                        </div>
                        @foreach($countries as $code => $name)
                          <div class="custom-select-option @if($code == $selectedCountryCode) selected @endif" data-value="{{ $code }}" data-name="{{ $name }}">
                            {{ $name }}
                          </div>
                        @endforeach
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            <div class="admin-form-group" id="region-group">
              <label class="k-label">Région / État / Province *</label>
              <div id="region-field-wrapper">
                <!-- Injected dynamically by JS -->
              </div>
            </div>
            <div class="admin-form-group">
              <label class="k-label">Code postal *</label>
              <input type="text" name="postal_code" id="input-postal" class="k-input" value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}" required placeholder="Ex: 75001">
            </div>
            <div class="admin-form-group">
              <label class="k-label">Ville *</label>
              <input type="text" name="city" id="input-city" class="k-input" value="{{ old('city', auth()->user()->city ?? '') }}" required placeholder="Ex: Paris">
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
            <input type="radio" name="payment_method" id="payment-method-card" value="card" checked style="accent-color:var(--color-warm);">
            <span>Carte bancaire</span>
          </label>
          <label class="flex items-center gap-3 p-4 border border-border-k rounded-md cursor-pointer">
            <input type="radio" name="payment_method" id="payment-method-cod" value="cod" style="accent-color:var(--color-warm);">
            <span>Paiement à la livraison</span>
          </label>
        </div>

        <button type="submit" id="checkout-submit-btn" class="btn-katuiscia-filled w-full" style="margin-top:var(--space-lg);">Payer maintenant</button>
      </form>
    </div>

    <!-- Récapitulatif -->
    <div class="lg:w-[380px] reveal-k-k delay-2">
      <div class="bg-white rounded-xl p-6 shadow-card space-y-4 sticky top-[220px]">
        <h3 class="font-heading text-xl">Votre commande</h3>
        @foreach($cart->items as $item)
        <div class="flex justify-between text-sm">
          <span class="text-text-muted">{{ $item->product->name ?? 'Produit' }} x{{ $item->quantity }}</span>
          <span>{{ number_format($item->subtotal, 2, ',', ' ') }} €</span>
        </div>
        @endforeach
        <hr class="border-border-k">
        <div class="flex justify-between text-sm">
          <span class="text-text-muted">Sous-total</span>
          <span>{{ number_format($cart->total, 2, ',', ' ') }} €</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-text-muted">Livraison</span>
          <span id="shipping-price-display" class="text-success">OFFERTE</span>
        </div>
        <hr class="border-border-k">
        <div style="margin-bottom:1rem;">
          <label class="k-label">Code promo</label>
          <div style="display:flex;gap:0.5rem;">
            <input type="text" id="coupon-code" class="k-input" style="flex:1;" placeholder="Entrez votre code..." value="{{ session('coupon.code', '') }}">
            <button type="button" id="apply-coupon" class="btn-katuiscia" style="font-size:12px;white-space:nowrap;">Appliquer</button>
          </div>
        </div>
        <div id="coupon-message" style="display:none;font-size:12px;margin-top:4px;margin-bottom:var(--space-xs);"></div>
        @php $cpDiscount = session('coupon.discount', 0); $cpTotal = max(0, $cart->total - $cpDiscount); @endphp
        
        <div id="applied-coupon-banner" style="{{ session('coupon') ? '' : 'display:none;' }} padding:12px 16px;background:rgba(90,143,110,0.08);border:1px solid var(--color-success);border-radius:var(--radius-md);margin-bottom:var(--space-md);">
          <strong style="color:var(--color-success);font-size:14px;">🎫 Coupon appliqué : <span id="banner-coupon-code">{{ session('coupon.code', '') }}</span></strong>
          <span style="display:block;font-size:12px;color:var(--color-text-muted);margin-top:4px;">Réduction : -<span id="banner-coupon-discount">{{ number_format(session('coupon.discount', 0), 2, ',', ' ') }}</span> € (<span id="banner-coupon-label">{{ session('coupon.label', '') }}</span>)</span>
        </div>
        
        <div class="flex justify-between font-heading text-lg" id="total-line">
          <span>Total</span>
          <span id="total-price-display">{{ number_format($cpTotal, 2, ',', ' ') }} €</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
<script>
const regionsData = {
  FR: [
    { value: 'Île-de-France', label: 'Île-de-France (10 €)' },
    { value: 'Auvergne-Rhône-Alpes', label: 'Auvergne-Rhône-Alpes' },
    { value: 'Bourgogne-Franche-Comté', label: 'Bourgogne-Franche-Comté' },
    { value: 'Bretagne', label: 'Bretagne' },
    { value: 'Centre-Val de Loire', label: 'Centre-Val de Loire' },
    { value: 'Corse', label: 'Corse' },
    { value: 'Grand Est', label: 'Grand Est' },
    { value: 'Hauts-de-France', label: 'Hauts-de-France' },
    { value: 'Normandie', label: 'Normandie' },
    { value: 'Nouvelle-Aquitaine', label: 'Nouvelle-Aquitaine' },
    { value: 'Occitanie', label: 'Occitanie' },
    { value: 'Pays de la Loire', label: 'Pays de la Loire' },
    { value: 'Provence-Alpes-Côte d\'Azur', label: 'Provence-Alpes-Côte d\'Azur' },
    { value: 'Guadeloupe', label: 'Guadeloupe' },
    { value: 'Martinique', label: 'Martinique' },
    { value: 'Guyane', label: 'Guyane' },
    { value: 'La Réunion', label: 'La Réunion' },
    { value: 'Mayotte', label: 'Mayotte' }
  ],
  US: [
    { value: 'Alabama', label: 'Alabama' },
    { value: 'Alaska', label: 'Alaska' },
    { value: 'Arizona', label: 'Arizona' },
    { value: 'Arkansas', label: 'Arkansas' },
    { value: 'California', label: 'California' },
    { value: 'Colorado', label: 'Colorado' },
    { value: 'Connecticut', label: 'Connecticut' },
    { value: 'Delaware', label: 'Delaware' },
    { value: 'Florida', label: 'Florida' },
    { value: 'Georgia', label: 'Georgia' },
    { value: 'Hawaii', label: 'Hawaii' },
    { value: 'Idaho', label: 'Idaho' },
    { value: 'Illinois', label: 'Illinois' },
    { value: 'Indiana', label: 'Indiana' },
    { value: 'Iowa', label: 'Iowa' },
    { value: 'Kansas', label: 'Kansas' },
    { value: 'Kentucky', label: 'Kentucky' },
    { value: 'Louisiana', label: 'Louisiana' },
    { value: 'Maine', label: 'Maine' },
    { value: 'Maryland', label: 'Maryland' },
    { value: 'Massachusetts', label: 'Massachusetts' },
    { value: 'Michigan', label: 'Michigan' },
    { value: 'Minnesota', label: 'Minnesota' },
    { value: 'Mississippi', label: 'Mississippi' },
    { value: 'Missouri', label: 'Missouri' },
    { value: 'Montana', label: 'Montana' },
    { value: 'Nebraska', label: 'Nebraska' },
    { value: 'Nevada', label: 'Nevada' },
    { value: 'New Hampshire', label: 'New Hampshire' },
    { value: 'New Jersey', label: 'New Jersey' },
    { value: 'New Mexico', label: 'New Mexico' },
    { value: 'New York', label: 'New York' },
    { value: 'North Carolina', label: 'North Carolina' },
    { value: 'North Dakota', label: 'North Dakota' },
    { value: 'Ohio', label: 'Ohio' },
    { value: 'Oklahoma', label: 'Oklahoma' },
    { value: 'Oregon', label: 'Oregon' },
    { value: 'Pennsylvania', label: 'Pennsylvania' },
    { value: 'Rhode Island', label: 'Rhode Island' },
    { value: 'South Carolina', label: 'South Carolina' },
    { value: 'South Dakota', label: 'South Dakota' },
    { value: 'Tennessee', label: 'Tennessee' },
    { value: 'Texas', label: 'Texas' },
    { value: 'Utah', label: 'Utah' },
    { value: 'Vermont', label: 'Vermont' },
    { value: 'Virginia', label: 'Virginia' },
    { value: 'Washington', label: 'Washington' },
    { value: 'West Virginia', label: 'West Virginia' },
    { value: 'Wisconsin', label: 'Wisconsin' },
    { value: 'Wyoming', label: 'Wyoming' }
  ],
  CA: [
    { value: 'Alberta', label: 'Alberta' },
    { value: 'British Columbia', label: 'British Columbia' },
    { value: 'Manitoba', label: 'Manitoba' },
    { value: 'New Brunswick', label: 'New Brunswick' },
    { value: 'Newfoundland and Labrador', label: 'Newfoundland and Labrador' },
    { value: 'Nova Scotia', label: 'Nova Scotia' },
    { value: 'Ontario', label: 'Ontario' },
    { value: 'Prince Edward Island', label: 'Prince Edward Island' },
    { value: 'Quebec', label: 'Quebec' },
    { value: 'Saskatchewan', label: 'Saskatchewan' },
    { value: 'Northwest Territories', label: 'Northwest Territories' },
    { value: 'Nunavut', label: 'Nunavut' },
    { value: 'Yukon', label: 'Yukon' }
  ],
  BE: [
    { value: 'Région Bruxelloise', label: 'Région de Bruxelles-Capitale' },
    { value: 'Région Flamande', label: 'Région Flamande (Flandre)' },
    { value: 'Région Wallonne', label: 'Région Wallonne (Wallonie)' }
  ],
  CH: [
    { value: 'Genève', label: 'Genève' },
    { value: 'Vaud', label: 'Vaud' },
    { value: 'Valais', label: 'Valais' },
    { value: 'Neuchâtel', label: 'Neuchâtel' },
    { value: 'Fribourg', label: 'Fribourg' },
    { value: 'Jura', label: 'Jura' },
    { value: 'Berne', label: 'Berne' },
    { value: 'Zurich', label: 'Zurich' },
    { value: 'Autre Canton', label: 'Autre Canton' }
  ]
};

const countryDialCodes = {
  FR: '+33', BE: '+32', CH: '+41', LU: '+352', DE: '+49', ES: '+34', IT: '+39', PT: '+351', GB: '+44', US: '+1', CA: '+1',
  AT: '+43', BG: '+359', CY: '+357', HR: '+385', DK: '+45', EE: '+372', FI: '+358', GR: '+30', HU: '+36', IE: '+353',
  LV: '+371', LT: '+370', MT: '+356', NL: '+31', PL: '+48', RO: '+40', SK: '+421', SI: '+386', SE: '+46', AL: '+355',
  AD: '+376', BA: '+387', BY: '+375', IS: '+354', LI: '+423', MC: '+377', MD: '+373', ME: '+382', MK: '+389', NO: '+47',
  RS: '+381', SM: '+378', UA: '+380', VA: '+39', AR: '+54', BO: '+591', BR: '+55', CL: '+56', CO: '+57', CR: '+506',
  DO: '+1', EC: '+593', GT: '+502', HN: '+504', HT: '+509', JM: '+1', MX: '+52', NI: '+505', PA: '+507', PE: '+51',
  PR: '+1', PY: '+595', SV: '+503', UY: '+598', VE: '+58', AO: '+244', BF: '+226', BJ: '+229', BI: '+257', BW: '+267',
  CD: '+243', CG: '+242', CI: '+225', CM: '+237', DJ: '+253', DZ: '+213', EG: '+20', ER: '+291', ET: '+251', GA: '+241',
  GH: '+233', GN: '+224', KE: '+254', LR: '+231', LS: '+266', LY: '+218', MA: '+212', MG: '+261', ML: '+223', MR: '+222',
  MW: '+265', MZ: '+258', NA: '+264', NE: '+227', NG: '+234', RW: '+250', SD: '+249', SL: '+232', SN: '+221', SO: '+252',
  SZ: '+268', TD: '+235', TG: '+228', TN: '+216', TZ: '+255', UG: '+256', ZA: '+27', ZM: '+260', ZW: '+263', AF: '+93',
  AE: '+971', AU: '+61', BD: '+880', BH: '+973', KH: '+855', CN: '+86', FJ: '+679', HK: '+852', ID: '+62', IL: '+972',
  IN: '+91', IR: '+98', IQ: '+964', JO: '+962', JP: '+81', KG: '+996', KR: '+82', KW: '+965', KZ: '+7', LA: '+856',
  LB: '+961', LK: '+94', MO: '+853', MM: '+95', MY: '+60', NP: '+977', NZ: '+64', OM: '+968', PG: '+675', PH: '+63',
  PK: '+92', QA: '+974', SA: '+966', SB: '+677', SG: '+65', SY: '+963', TH: '+66', TJ: '+992', TR: '+90', TW: '+886',
  TM: '+993', UZ: '+998', VU: '+678', VN: '+84', YE: '+967'
};

var postalPatterns = { FR: /^\d{5}$/, BE: /^\d{4}$/, CH: /^\d{4}$/, LU: /^\d{4}$/, DE: /^\d{5}$/, ES: /^\d{5}$/, IT: /^\d{5}$/, PT: /^\d{4}-\d{3}$/, GB: /^[A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}$/i, US: /^\d{5}(-\d{4})?$/, CA: /^[A-Z]\d[A-Z] ?\d[A-Z]\d$/i };

function getPhonePlaceholderSuffix(country) {
  const formats = {
    FR: '6 12 34 56 78',
    BE: '491 12 34 56',
    CH: '79 123 45 67',
    DE: '170 1234567',
    GB: '7123 456789',
    US: '(555) 123-4567',
    CA: '(555) 123-4567',
    ES: '612 345 678',
    IT: '312 345 6789',
    PT: '912 345 678',
    MA: '6 12 34 56 78',
    DZ: '5 12 34 56 78',
    TN: '98 123 456',
    SN: '77 123 45 67',
    CI: '07 12 34 56 78'
  };
  return formats[country] || '6 12 34 56 78';
}

function updatePostalPhone(country) {
  var postal = document.getElementById('input-postal');
  var phone = document.getElementById('input-phone');
  if (!postal || !phone) return;

  var p = postalPatterns[country];
  if (p) { 
    postal.pattern = p.source; 
    postal.title = 'Format : ' + ({FR:'5 chiffres',BE:'4 chiffres',GB:'ex: SW1A 1AA',US:'5 chiffres',CA:'ex: K1A 0B1'}[country] || p.source); 
  } else { 
    postal.removeAttribute('pattern'); 
    postal.removeAttribute('title'); 
  }

  const prefix = countryDialCodes[country] || '';
  if (prefix) {
    const currentValue = phone.value.trim();
    const prefixes = Object.values(countryDialCodes);
    const isJustPrefix = prefixes.some(pref => currentValue === pref || currentValue === pref + ' ' || currentValue === pref + '-');
    
    if (currentValue === '' || isJustPrefix) {
      phone.value = prefix + ' ';
    }
    phone.placeholder = prefix + ' ' + getPhonePlaceholderSuffix(country);
  }
}

var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
var cartTotal = {{ $cart->total }};

async function updateShippingEstimate() {
  const country = document.getElementById('input-country').value;
  const regionInput = document.getElementById('input-region');
  const region = regionInput ? regionInput.value.trim() : '';

  const discount = parseFloat(document.getElementById('hidden-coupon-discount').value || '0');
  const totalDisplay = document.getElementById('total-price-display');
  const shippingDisplay = document.getElementById('shipping-price-display');
  const submitBtn = document.getElementById('checkout-submit-btn');
  const codRadio = document.getElementById('payment-method-cod');
  const isCod = codRadio && codRadio.checked;

  if (isCod) {
    if (shippingDisplay) {
      shippingDisplay.textContent = 'OFFERTE';
      shippingDisplay.style.color = 'var(--color-success)';
    }
    const newTotal = Math.max(0, cartTotal - discount);
    if (totalDisplay) {
      totalDisplay.textContent = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(newTotal) + ' €';
    }
    if (!country || !region) {
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        submitBtn.textContent = 'Sélectionnez une région';
      }
    } else {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
        submitBtn.textContent = 'Payer maintenant';
      }
    }
    return;
  }

  if (!country || !region) {
    if (shippingDisplay) {
      shippingDisplay.textContent = 'Sélectionnez la région';
      shippingDisplay.style.color = 'var(--color-text-muted, #8c7e74)';
    }
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.5';
      submitBtn.textContent = 'Sélectionnez une région';
    }
    const newTotal = Math.max(0, cartTotal - discount);
    if (totalDisplay) {
      totalDisplay.textContent = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(newTotal) + ' €';
    }
    return;
  }

  try {
    const response = await fetch(`/api/shipping/estimate?country=${encodeURIComponent(country)}&region=${encodeURIComponent(region)}`);
    const data = await response.json();

    if (data.success && data.available) {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
        submitBtn.textContent = 'Payer maintenant';
      }

      const price = data.price;
      if (shippingDisplay) {
        if (price === 0) {
          shippingDisplay.textContent = 'OFFERTE';
          shippingDisplay.style.color = 'var(--color-success)';
        } else {
          shippingDisplay.textContent = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(price) + ' €';
          shippingDisplay.style.color = 'var(--color-dark)';
        }
      }

      const newTotal = Math.max(0, cartTotal - discount + price);
      if (totalDisplay) {
        totalDisplay.textContent = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(newTotal) + ' €';
      }
    } else {
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        submitBtn.textContent = 'Livraison indisponible';
      }

      if (shippingDisplay) {
        shippingDisplay.textContent = 'Non disponible';
        shippingDisplay.style.color = 'var(--color-error)';
      }

      const newTotal = Math.max(0, cartTotal - discount);
      if (totalDisplay) {
        totalDisplay.textContent = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(newTotal) + ' €';
      }
    }
  } catch (error) {
    console.error('Error fetching shipping estimate:', error);
  }
}

function updateRegionField(countryCode, selectedRegion = '') {
  const wrapper = document.getElementById('region-field-wrapper');
  if (!wrapper) return;
  wrapper.innerHTML = '';

  const localRegions = regionsData[countryCode];

  if (localRegions) {
    renderRegionDropdown(localRegions, selectedRegion);
  } else {
    renderLoadingIndicator();

    fetch(`https://countriesnow.space/api/v0.1/countries/states/q?iso2=${countryCode}`)
      .then(res => {
        if (!res.ok) throw new Error('API error');
        return res.json();
      })
      .then(data => {
        if (data.error || !data.data || !data.data.states || data.data.states.length === 0) {
          renderTextInput(selectedRegion);
        } else {
          const apiRegions = data.data.states.map(s => ({
            value: s.name,
            label: s.name
          })).sort((a, b) => a.label.localeCompare(b.label));

          renderRegionDropdown(apiRegions, selectedRegion);
        }
      })
      .catch(err => {
        console.warn('Could not fetch regions from CountriesNow, falling back to text input.', err);
        renderTextInput(selectedRegion);
      });
  }

  updatePostalPhone(countryCode);
  updateShippingEstimate();
}

function renderRegionDropdown(regions, selectedValue) {
  const wrapper = document.getElementById('region-field-wrapper');
  if (!wrapper) return;
  wrapper.innerHTML = '';

  const select = document.createElement('select');
  select.name = 'region';
  select.id = 'input-region';
  select.className = 'k-input';
  select.required = true;

  const placeholderOpt = document.createElement('option');
  placeholderOpt.value = '';
  placeholderOpt.textContent = 'Sélectionnez votre région / état *';
  select.appendChild(placeholderOpt);

  regions.forEach(r => {
    const opt = document.createElement('option');
    opt.value = r.value;
    opt.textContent = r.label;
    if (r.value === selectedValue) {
      opt.selected = true;
    }
    select.appendChild(opt);
  });

  select.addEventListener('change', updateShippingEstimate);
  wrapper.appendChild(select);
}

function renderTextInput(selectedValue) {
  const wrapper = document.getElementById('region-field-wrapper');
  if (!wrapper) return;
  wrapper.innerHTML = '';

  const input = document.createElement('input');
  input.type = 'text';
  input.name = 'region';
  input.id = 'input-region';
  input.className = 'k-input';
  input.placeholder = 'Région / Province / État *';
  input.required = true;
  input.value = selectedValue;

  let debounceTimer;
  input.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(updateShippingEstimate, 400);
  });
  input.addEventListener('change', updateShippingEstimate);

  wrapper.appendChild(input);
}

function renderLoadingIndicator() {
  const wrapper = document.getElementById('region-field-wrapper');
  if (!wrapper) return;
  wrapper.innerHTML = `
    <div style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--color-text-muted, #8c7e74); padding: 12px; border: 1px solid #ede4db; border-radius: 8px; background-color: #faf7f2;">
      <svg style="animation: spin 1s linear infinite; width: 16px; height: 16px; margin-right: 8px; display: inline-block; vertical-align: middle;" fill="none" viewBox="0 0 24 24">
        <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <span>Chargement des régions...</span>
    </div>
    <style>
      @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }
    </style>
  `;
}

const cityInput = document.getElementById('input-city');
const codRadio = document.getElementById('payment-method-cod');
const cardRadio = document.getElementById('payment-method-card');

function checkCityForCod() {
  if (!cityInput || !codRadio) return;
  const city = cityInput.value.trim().toLowerCase();
  const isParis = city === 'paris';
  codRadio.disabled = !isParis;
  if (codRadio.parentElement) {
    codRadio.parentElement.style.opacity = isParis ? '1' : '0.5';
    codRadio.parentElement.style.cursor = isParis ? 'pointer' : 'not-allowed';
  }
  if (!isParis && codRadio.checked) {
    if (cardRadio) cardRadio.checked = true;
  }
  updateShippingEstimate();
}

// Custom Country Select Dropdown Initialization
function initCountryDropdown() {
  const countryTrigger = document.getElementById('country-trigger');
  const countryOptionsPanel = document.getElementById('country-options-panel');
  const countrySearch = document.getElementById('country-search');
  const countryOptions = document.querySelectorAll('.custom-select-option');
  const inputCountry = document.getElementById('input-country');
  const countryTriggerText = document.getElementById('country-trigger-text');

  if (countryTrigger && countryOptionsPanel) {
    countryTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      countryOptionsPanel.classList.toggle('hidden');
      if (!countryOptionsPanel.classList.contains('hidden') && countrySearch) {
        countrySearch.value = '';
        filterCountries('');
        countrySearch.focus();
      }
    });

    // Close panel when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.custom-select-wrapper')) {
        countryOptionsPanel.classList.add('hidden');
      }
    });

    // Select option
    countryOptions.forEach(opt => {
      opt.addEventListener('click', (e) => {
        e.stopPropagation();
        const code = opt.getAttribute('data-value');
        const name = opt.getAttribute('data-name');

        if (inputCountry) {
          inputCountry.value = code;
          const event = new Event('change');
          inputCountry.dispatchEvent(event);
        }
        if (countryTriggerText) {
          countryTriggerText.textContent = name;
        }

        // Toggle selected class
        countryOptions.forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');

        countryOptionsPanel.classList.add('hidden');

        // Update region field for the new country
        updateRegionField(code);
      });
    });

    // Search filter
    if (countrySearch) {
      countrySearch.addEventListener('input', (e) => {
        filterCountries(e.target.value);
      });
    }
  }
}

function filterCountries(query) {
  const cleanQuery = query.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
  const listContainer = document.querySelector('.custom-select-list');
  if (!listContainer) return;

  const children = listContainer.children;
  for (let i = 0; i < children.length; i++) {
    const child = children[i];
    if (child.classList.contains('custom-select-group')) {
      const options = child.querySelectorAll('.custom-select-option');
      const header = child.querySelector('.custom-select-group-header');
      let groupVisibleCount = 0;

      options.forEach(opt => {
        const name = opt.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        if (name.includes(cleanQuery)) {
          opt.style.display = '';
          groupVisibleCount++;
        } else {
          opt.style.display = 'none';
        }
      });

      if (header) {
        header.style.display = groupVisibleCount > 0 ? '' : 'none';
      }
    }
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const initialCountry = '{{ $selectedCountryCode }}';
  const initialRegion = '{{ old('region', '') }}';
  updateRegionField(initialCountry, initialRegion);
  initCountryDropdown();

  if (cityInput) {
    cityInput.addEventListener('input', checkCityForCod);
    cityInput.addEventListener('change', checkCityForCod);
    checkCityForCod();
  }

  document.querySelectorAll('input[name="payment_method"]').forEach(input => {
    input.addEventListener('change', updateShippingEstimate);
  });
});

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
    document.getElementById('hidden-coupon-code').value = code;
    document.getElementById('hidden-coupon-discount').value = data.discount_raw || 0;
    
    document.getElementById('banner-coupon-code').textContent = code;
    document.getElementById('banner-coupon-discount').textContent = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(data.discount_raw || 0);
    document.getElementById('banner-coupon-label').textContent = data.message;
    document.getElementById('applied-coupon-banner').style.display = 'block';
  } else {
    msg.style.color = 'var(--color-error)';
    msg.textContent = data.message;
    document.getElementById('hidden-coupon-code').value = '';
    document.getElementById('hidden-coupon-discount').value = '0';
    document.getElementById('applied-coupon-banner').style.display = 'none';
  }
  updateShippingEstimate();
});
</script>
@endsection
