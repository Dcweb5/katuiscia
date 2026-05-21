@extends('layouts.admin')
@section('title', 'Commande ' . $order->order_number)
@section('content')
<div class="dashboard-content">
  <a href="{{ route('admin.orders.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--color-text-muted);font-size:14px;margin-bottom:var(--space-lg);text-decoration:none;">← Retour</a>
  <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:var(--space-xl);">
    <div><h1 class="page-title">Commande {{ $order->order_number }}</h1><p class="page-subtitle">{{ $order->firstname }} {{ $order->lastname }} — {{ $order->created_at->format('d/m/Y H:i') }}</p></div>
    @php $colors=['pending'=>'var(--color-warm)','confirmed'=>'#3b82f6','preparing'=>'#f59e0b','shipped'=>'#8b5cf6','delivered'=>'var(--color-success)','cancelled'=>'var(--color-error)']; @endphp
    <span class="status-badge" style="background:{{ $colors[$order->status] ?? '#ccc' }};color:white;font-size:12px;font-weight:600;padding:8px 20px;border-radius:var(--radius-full);">{{ ucfirst($order->status) }}</span>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-xl);">
    <div>
      <div class="card" style="margin-bottom:var(--space-xl);">
        <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Client</h3>
        <div style="display:flex;flex-direction:column;gap:var(--space-sm);">
          <p><strong>{{ $order->firstname }} {{ $order->lastname }}</strong></p>
          <p style="font-size:var(--text-sm);color:var(--color-text-muted);">{{ $order->email }}</p>
          <p style="font-size:var(--text-sm);color:var(--color-text-muted);">{{ $order->phone ?? '—' }}</p>
        </div>
      </div>
      <div class="card" style="margin-bottom:var(--space-xl);">
        <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Livraison</h3>
        <p style="font-size:var(--text-sm);line-height:1.6;">{{ $order->address }}@if($order->address2), {{ $order->address2 }}@endif<br>{{ $order->postal_code }} {{ $order->city }}<br>{{ $order->country }}</p>
      </div>
      <div class="card">
        <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Statut</h3>
        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
          @csrf @method('PUT')
          <select name="status" class="admin-input" style="margin-bottom:var(--space-sm);">
            @foreach(['pending'=>'En attente','confirmed'=>'Confirmée','preparing'=>'En préparation','shipped'=>'Expédiée','delivered'=>'Livrée','cancelled'=>'Annulée'] as $k => $l)
            <option value="{{ $k }}" @selected($order->status==$k)>{{ $l }}</option>
            @endforeach
          </select>
          <input type="text" name="tracking_number" class="admin-input" style="margin-bottom:var(--space-sm);" placeholder="N° de suivi (optionnel)" value="{{ $order->tracking_number }}">
          <button type="submit" class="btn-primary" style="width:100%;">Mettre à jour</button>
        </form>
      </div>
    </div>
    <div>
      <div class="card" style="margin-bottom:var(--space-xl);">
        <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Articles ({{ $order->items->count() }})</h3>
        @foreach($order->items as $item)
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);">
          <span style="font-size:var(--text-sm);">{{ $item->product_name }} x{{ $item->quantity }}</span>
          <span style="font-size:var(--text-sm);">{{ number_format($item->subtotal, 2, ',', ' ') }} €</span>
        </div>
        @endforeach
        <hr style="margin-top:var(--space-md);">
        <div style="display:flex;justify-content:space-between;padding:var(--space-xs) 0;font-size:var(--text-sm);"><span class="text-text-muted">Sous-total</span><span>{{ number_format($order->subtotal, 2, ',', ' ') }} €</span></div>
        @if($order->discount > 0)
        <div style="display:flex;justify-content:space-between;padding:var(--space-xs) 0;font-size:var(--text-sm);"><span class="text-text-muted">Réduction @if($order->coupon_code)({{ $order->coupon_code }})@endif</span><span style="color:var(--color-success);">-{{ number_format($order->discount, 2, ',', ' ') }} €</span></div>
        @endif
        <div style="display:flex;justify-content:space-between;padding:var(--space-xs) 0;font-size:var(--text-sm);"><span class="text-text-muted">Livraison</span><span>{{ $order->shipping > 0 ? number_format($order->shipping, 2, ',', ' ') . ' €' : 'OFFERTE' }}</span></div>
        <div style="display:flex;justify-content:space-between;font-weight:700;font-family:var(--font-heading);font-size:var(--text-lg);margin-top:var(--space-sm);"><span>Total</span><span>{{ number_format($order->total, 2, ',', ' ') }} €</span></div>
      </div>
      <div class="card"><h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Paiement</h3><p style="font-size:var(--text-sm);">{{ $order->payment_method === 'cod' ? 'Paiement à la livraison' : 'Carte bancaire' }}</p></div>
    </div>
  </div>
</div>
@endsection
