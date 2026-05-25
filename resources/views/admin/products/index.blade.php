@extends('layouts.admin')
@section('title', 'Produits')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div>
      <h1 class="page-title">Gestion des Produits</h1>
      <p class="page-subtitle">Ajoutez, modifiez et organisez tous les produits de la boutique.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-primary" style="text-decoration:none;">+ Nouveau Produit</a>
  </div>

  {{-- Stats cards --}}
  <div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:var(--space-xl);">
    <div class="card stat-card"><span class="stat-title">Total Produits</span><span class="stat-value">{{ $total }}</span></div>
    <div class="card stat-card"><span class="stat-title">En Stock</span><span class="stat-value">{{ $inStock }}</span><span style="font-size:11px;color:var(--color-success);">{{ $total > 0 ? round($inStock / $total * 100) : 0 }}%</span></div>
    <div class="card stat-card"><span class="stat-title">Stock Faible</span><span class="stat-value">{{ $lowStock }}</span><span style="font-size:11px;color:#f59e0b;">Attention</span></div>
    <div class="card stat-card"><span class="stat-title">Catégories</span><span class="stat-value">{{ $categories->count() }}</span></div>
  </div>

  {{-- Filters bar --}}
  <div style="display:flex;gap:0.75rem;margin-bottom:1rem;flex-wrap:wrap;align-items:center;">
    <form method="GET" style="flex:1;min-width:200px;max-width:350px;display:flex;gap:0.5rem;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un produit..." class="admin-input" style="padding:8px 14px;">
      <button type="submit" class="action-btn" style="width:auto;padding:0 14px;height:40px;">🔍</button>
      @if(request('search'))<a href="?" class="action-btn" style="width:auto;padding:0 12px;text-decoration:none;height:40px;display:flex;align-items:center;">✕</a>@endif
    </form>

    <select name="category" class="admin-input" style="width:auto;min-width:150px;padding:8px 14px;" onchange="window.location=this.value?'?category='+this.value+(location.search.match(/sort=[^&]+/)?'&'+location.search.match(/sort=[^&]+/)[0]:'')+(location.search.match(/search=[^&]+/)?'&'+location.search.match(/search=[^&]+/)[0]:'')+(location.search.match(/status=[^&]+/)?'&'+location.search.match(/status=[^&]+/)[0]:''):'?'">
      <option value="">Toutes les catégories</option>
      @foreach($categories as $cat)
      <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
      @endforeach
    </select>

    <select name="status" class="admin-input" style="width:auto;min-width:140px;padding:8px 14px;" onchange="window.location=this.value?'?status='+this.value+(location.search.match(/sort=[^&]+/)?'&'+location.search.match(/sort=[^&]+/)[0]:'')+(location.search.match(/search=[^&]+/)?'&'+location.search.match(/search=[^&]+/)[0]:'')+(location.search.match(/category=[^&]+/)?'&'+location.search.match(/category=[^&]+/)[0]:''):'?'">
      <option value="">Tous les statuts</option>
      <option value="active" @selected(request('status') === 'active')>Actif</option>
      <option value="inactive" @selected(request('status') === 'inactive')>Inactif</option>
    </select>

    <select name="sort" class="admin-input" style="width:auto;min-width:160px;padding:8px 14px;" onchange="window.location='?sort='+this.value+(location.search.match(/search=[^&]+/)?'&'+location.search.match(/search=[^&]+/)[0]:'')+(location.search.match(/category=[^&]+/)?'&'+location.search.match(/category=[^&]+/)[0]:'')+(location.search.match(/status=[^&]+/)?'&'+location.search.match(/status=[^&]+/)[0]:'')">
      <option value="newest" @selected(!request('sort') || request('sort') === 'newest')>Trier par : Plus récent</option>
      <option value="oldest" @selected(request('sort') === 'oldest')>Plus ancien</option>
      <option value="name" @selected(request('sort') === 'name')>Nom A-Z</option>
      <option value="price_asc" @selected(request('sort') === 'price_asc')>Prix croissant</option>
      <option value="price_desc" @selected(request('sort') === 'price_desc')>Prix décroissant</option>
      <option value="stock_low" @selected(request('sort') === 'stock_low')>Stock faible d'abord</option>
    </select>
  </div>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr><th>Produit</th><th>SKU</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>Images</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($products as $product)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:12px;">
              @if($product->images->where('is_primary', true)->first())
              <img src="{{ asset('storage/'.$product->images->where('is_primary', true)->first()->path) }}" style="width:44px;height:44px;border-radius:8px;object-fit:cover;">
              @elseif($product->image_primary)
              <img src="{{ asset($product->image_primary) }}" style="width:44px;height:44px;border-radius:8px;object-fit:cover;" onerror="this.src='{{ asset('assets/images/K ICONE.webp') }}'">
              @else
              <div style="width:44px;height:44px;border-radius:8px;background:var(--color-gray-medium);display:flex;align-items:center;justify-content:center;">📦</div>
              @endif
              <div><strong>{{ $product->name }}</strong><br><small style="color:var(--color-text-muted);">{{ Str::limit($product->description, 50) }} {{ $product->size ? '• '.$product->size : '' }}</small></div>
            </div>
          </td>
          <td style="color:var(--color-text-muted);">{{ $product->sku ?? '—' }}</td>
          <td>{{ $product->categories->first()?->name ?? '—' }}</td>
          <td>
            @if($product->sale_price && $product->sale_price < $product->price)
            <span style="text-decoration:line-through;color:var(--color-text-muted);font-size:12px;">{{ number_format($product->price,0,',',' ') }} €</span><br>
            <span style="color:var(--color-success);">{{ number_format($product->sale_price,0,',',' ') }} €</span>
            @else
            {{ number_format($product->price,0,',',' ') }} €
            @endif
          </td>
          <td>
            <span style="font-size:13px;{{ $product->stock <= 0 ? 'color:var(--color-error);' : ($product->stock <= 5 ? 'color:#f59e0b;' : '') }}">{{ $product->stock ?? '—' }}</span>
          </td>
          <td style="font-size:12px;color:var(--color-text-muted);">{{ $product->images->count() }}</td>
          <td>
            <span style="font-size:11px;font-weight:600;color:#fff;padding:3px 10px;border-radius:var(--radius-full);background:{{ $product->is_active ? 'var(--color-success)' : 'var(--color-text-muted)' }};">{{ $product->is_active ? 'Actif' : 'Inactif' }}</span>
          </td>
          <td>
            <div style="display:flex;gap:4px;">
              <a href="{{ route('admin.products.edit', $product) }}" class="action-btn" title="Modifier">✏️</a>
              <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('Supprimer ce produit ?')">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn" title="Supprimer">🗑</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun produit trouvé.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $products->links() }}
</div>
<script>
  document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-produits') l.classList.add('active'); });
</script>
@endsection
