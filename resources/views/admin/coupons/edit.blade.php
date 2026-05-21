@extends('layouts.admin')
@section('title', 'Modifier — ' . $coupon->code)
@section('content')
<div class="dashboard-content">
  <a href="{{ route('admin.coupons.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--color-text-muted);font-size:14px;margin-bottom:var(--space-lg);text-decoration:none;">← Retour</a>
  <h1 class="page-title">Modifier — {{ $coupon->code }}</h1>
  @if($errors->any())<div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:var(--space-lg);"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" style="max-width:600px;">
    @csrf @method('PUT')
    <div class="card" style="margin-bottom:var(--space-xl);">
      <div class="admin-form-group"><label class="admin-label">Code *</label><input type="text" name="code" class="admin-input" value="{{ old('code', $coupon->code) }}" required></div>
      <div class="admin-form-group"><label class="admin-label">Type *</label><select name="type" class="admin-input" id="coupon-type" onchange="document.getElementById('value-group').style.display=this.value==='free_shipping'?'none':''"><option value="percentage" @selected(old('type',$coupon->type)=='percentage')>Pourcentage (%)</option><option value="fixed" @selected(old('type',$coupon->type)=='fixed')>Montant fixe (€)</option><option value="free_shipping" @selected(old('type',$coupon->type)=='free_shipping')>Livraison gratuite</option></select></div>
      <div class="admin-form-group" id="value-group" @if($coupon->type==='free_shipping') style="display:none;" @endif><label class="admin-label">Valeur</label><input type="number" name="value" class="admin-input" value="{{ old('value', $coupon->value) }}" step="0.01" min="0"></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-md);">
        <div class="admin-form-group"><label class="admin-label">Min. commande (€)</label><input type="number" name="min_order_amount" class="admin-input" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" step="0.01" min="0"></div>
        <div class="admin-form-group"><label class="admin-label">Max. utilisations</label><input type="number" name="max_uses" class="admin-input" value="{{ old('max_uses', $coupon->max_uses) }}" min="1"></div>
        <div class="admin-form-group"><label class="admin-label">Début</label><input type="datetime-local" name="starts_at" class="admin-input" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}"></div>
        <div class="admin-form-group"><label class="admin-label">Expiration</label><input type="datetime-local" name="expires_at" class="admin-input" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}"></div>
      </div>
      <div class="admin-form-group"><label class="admin-label">Description</label><input type="text" name="description" class="admin-input" value="{{ old('description', $coupon->description) }}"></div>
      <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:var(--space-md);"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active)) style="accent-color:var(--color-warm);"> Actif</label>
    </div>
    <div style="display:flex;gap:var(--space-sm);">
      <a href="{{ route('admin.coupons.index') }}" class="btn-katuiscia">Annuler</a>
      <button type="submit" class="btn-primary">Enregistrer</button>
    </div>
  </form>
</div>
@endsection
