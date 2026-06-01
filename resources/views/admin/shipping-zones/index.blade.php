@extends('layouts.admin')

@section('title', 'Zones de Livraison')

@section('content')
<div class="dashboard-content">
  <div style="margin-bottom:var(--space-2xl);">
    <h1 class="page-title">Frais de Livraison par Zone</h1>
    <p class="page-subtitle">Configurez les tarifs et les délais de livraison pour chaque zone géographique.</p>
  </div>

  @if(session('success'))
  <div style="padding:12px 16px; background:rgba(90,143,110,0.1); border:1px solid var(--color-success); border-radius:8px; color:var(--color-success); margin-bottom:var(--space-lg);">
    {{ session('success') }}
  </div>
  @endif

  @if($errors->any())
  <div style="padding:12px 16px; background:rgba(199,80,80,0.1); border:1px solid var(--color-error); border-radius:8px; color:var(--color-error); margin-bottom:var(--space-lg);">
    <ul style="margin:0; padding-left:20px;">
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(340px, 1fr)); gap:var(--space-lg);">
    @foreach($zones as $zone)
    <div class="card" style="position:relative; overflow:visible; padding:var(--space-xl); border:1px solid {{ $zone->is_active ? 'var(--color-border)' : '#ede4db' }}; background:{{ $zone->is_active ? 'white' : '#f9f6f0' }}; transition:all 0.3s;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:var(--space-md); border-bottom:1px solid var(--color-border); padding-bottom:var(--space-sm);">
        <div>
          <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:4px; color:var(--color-dark);">{{ $zone->name }}</h3>
          <span style="font-size:var(--text-xs); color:var(--color-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:var(--tracking-wide);">
            Code : {{ $zone->code }}
          </span>
        </div>
        <form method="POST" action="{{ route('admin.shipping-zones.toggle', $zone) }}">
          @csrf @method('PUT')
          <button type="submit" class="btn btn-sm" style="font-size:11px; padding:4px 10px; border-radius:6px; cursor:pointer; background:{{ $zone->is_active ? 'var(--color-success)' : 'var(--color-gray-medium)' }}; color:white; border:none; transition:all 0.2s;">
            {{ $zone->is_active ? '✅ Activé' : '❌ Désactivé' }}
          </button>
        </form>
      </div>

      <form method="POST" action="{{ route('admin.shipping-zones.update', $zone) }}">
        @csrf @method('PUT')
        
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:var(--space-md);">
          <div class="admin-form-group">
            <label class="admin-label">Prix de la livraison (€)</label>
            <input type="number" name="price" class="admin-input" value="{{ old('price', $zone->price) }}" step="0.01" min="0" required {{ !$zone->is_active ? 'disabled' : '' }}>
            <small style="font-size:10px;color:var(--color-text-muted);">Mettez 0 pour gratuit</small>
          </div>
          
          <div class="admin-form-group">
            <label class="admin-label">Délai estimé</label>
            <input type="text" name="delivery_time" class="admin-input" value="{{ old('delivery_time', $zone->delivery_time) }}" placeholder="Ex: 3-5 jours" {{ !$zone->is_active ? 'disabled' : '' }}>
          </div>
        </div>

        @if($zone->is_active)
        <input type="hidden" name="is_active" value="1">
        <button type="submit" class="btn-katuiscia-filled" style="width:100%; margin-top:var(--space-md); padding:8px; font-size:12px; height:auto; justify-content:center;">
          Sauvegarder la zone
        </button>
        @else
        <div style="text-align:center; margin-top:var(--space-md); font-size:12px; color:var(--color-text-muted); font-style:italic;">
          Activez cette zone pour modifier ses tarifs.
        </div>
        @endif
      </form>
    </div>
    @endforeach
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var link = document.querySelector('.sidebar-link[data-page="admin-shipping-zones"]');
    if (link) link.classList.add('active');
  });
</script>
@endsection
