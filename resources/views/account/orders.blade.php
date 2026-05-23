@extends('layouts.account')
@section('title', 'Mes Commandes')
@section('content')
<header class="dashboard-header">
  <div style="display:flex;align-items:center;gap:16px;"><button class="mobile-toggle" id="mobileToggle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button><span style="font-family:var(--font-heading);font-size:var(--text-lg);color:var(--color-dark);">Mes Commandes</span></div>
</header>
<div class="dashboard-content">
  <h1 class="page-title">Mes Commandes</h1>
  @php $steps = ['confirmed'=>'Confirmée','preparing'=>'Préparée','shipped'=>'Expédiée','delivered'=>'Livrée']; @endphp

  @if($orders->count() > 0)
  <div style="display:flex;flex-direction:column;gap:var(--space-lg);">
    @foreach($orders as $order)
    <div class="card" style="padding:var(--space-xl);">
      <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-md);">
        <div>
          <strong style="font-family:monospace;font-size:var(--text-md);">{{ $order->order_number }}</strong>
          <span style="font-size:var(--text-xs);color:var(--color-text-muted);margin-left:var(--space-md);">{{ $order->created_at->format('d/m/Y') }}</span>
        </div>
        <div style="display:flex;gap:var(--space-sm);align-items:center;">
          @php $colors=['pending'=>'var(--color-warm)','confirmed'=>'#3b82f6','preparing'=>'#f59e0b','shipped'=>'#8b5cf6','delivered'=>'var(--color-success)','cancelled'=>'var(--color-error)']; @endphp
          <span style="font-size:11px;font-weight:600;color:white;background:{{ $colors[$order->status] ?? '#ccc' }};padding:4px 12px;border-radius:var(--radius-full);">{{ $steps[$order->status] ?? $order->status }}</span>
          @if($order->invoice)
          <a href="{{ route('invoice.download', $order->invoice) }}" class="action-btn" title="Télécharger la facture" style="width:auto;padding:4px 10px;text-decoration:none;font-size:11px;gap:4px;">📄 Facture</a>
          @endif
          <strong style="font-family:var(--font-display);">{{ number_format($order->total, 0, ',', ' ') }} €</strong>
        </div>
      </div>

      <!-- Progression -->
      @if($order->status !== 'cancelled')
      @php $statusOrder = ['confirmed','preparing','shipped','delivered']; $currentIdx = array_search($order->status, $statusOrder); $currentIdx = $currentIdx !== false ? $currentIdx : 0; @endphp
      <div style="display:flex;align-items:center;gap:0;margin-bottom:var(--space-md);">
        @foreach(['confirmed'=>'✓','preparing'=>'📦','shipped'=>'🚚','delivered'=>'✅'] as $s => $icon)
        @php $idx = array_search($s, $statusOrder); $done = $idx <= $currentIdx; @endphp
        <div style="flex:1;text-align:center;position:relative;">
          <div style="width:28px;height:28px;border-radius:50%;background:{{ $done ? 'var(--color-dark)' : 'var(--color-gray-medium)' }};color:{{ $done ? 'white' : 'var(--color-text-muted)' }};display:inline-flex;align-items:center;justify-content:center;font-size:12px;position:relative;z-index:1;">{{ $icon }}</div>
          <div style="font-size:9px;color:{{ $done ? 'var(--color-dark)' : 'var(--color-text-muted)' }};margin-top:4px;">{{ $steps[$s] ?? $s }}</div>
        </div>
        @if(!$loop->last)
        <div style="flex:0.5;height:2px;background:{{ $idx < $currentIdx ? 'var(--color-dark)' : 'var(--color-gray-medium)' }};min-width:20px;"></div>
        @endif
        @endforeach
      </div>
      @endif

      <!-- Articles -->
      <div style="display:flex;gap:var(--space-md);flex-wrap:wrap;">
        @foreach($order->items as $item)
        <div style="display:flex;align-items:center;gap:var(--space-sm);background:var(--color-gray-k);padding:8px 12px;border-radius:var(--radius-sm);">
          <span style="font-size:var(--text-sm);">{{ $item->product_name }}</span>
          <span style="font-size:11px;color:var(--color-text-muted);">x{{ $item->quantity }}</span>
        </div>
        @endforeach
      </div>
    </div>
    @endforeach
  </div>
  @else
  <div style="text-align:center;padding:4rem;color:var(--color-text-muted);">
    <p style="font-size:40px;margin-bottom:var(--space-md);">📦</p>
    <p>Aucune commande pour le moment.</p>
    <a href="{{ url('boutique') }}" class="btn-katuiscia" style="margin-top:var(--space-md);">Découvrir la boutique</a>
  </div>
  @endif
</div>
<script>document.getElementById('mobileToggle')?.addEventListener('click',()=>document.getElementById('sidebar').classList.toggle('open'));document.querySelectorAll('.sidebar-link[data-page]').forEach(l=>{if(l.dataset.page==='compte-commandes')l.classList.add('active');});</script>
@endsection
