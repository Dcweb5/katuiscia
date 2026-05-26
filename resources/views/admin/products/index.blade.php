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

  {{-- Filters --}}
  <div class="filter-pills">
    <div class="search-bar" style="margin-right:auto;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="search" form="products-filter" value="{{ request('search') }}" placeholder="Rechercher un produit...">
    </div>
    @foreach([''=>'Toutes catégories'] + $categories->pluck('name','id')->toArray() as $k=>$l)
    <a href="?{{ http_build_query(array_merge(request()->except(['category','page']), $k ? ['category'=>$k] : [])) }}" class="filter-pill {{ request('category','') == (string)$k ? 'active' : '' }}">{{ $l }}</a>
    @endforeach
    <a href="?{{ http_build_query(array_merge(request()->except(['status','page']), request('status')==='active'?[]:['status'=>'active'])) }}" class="filter-pill {{ request('status') === 'active' ? 'active' : '' }}">Actif</a>
    <a href="?{{ http_build_query(array_merge(request()->except(['status','page']), ['status'=>'inactive'])) }}" class="filter-pill {{ request('status') === 'inactive' ? 'active' : '' }}">Inactif</a>
    <select name="sort" class="admin-input" form="products-filter" style="width:auto;min-width:160px;padding:8px 14px;margin-left:0.5rem;">
      <option value="newest" @selected(!request('sort') || request('sort') === 'newest')>Tri : Plus récent</option>
      <option value="oldest" @selected(request('sort') === 'oldest')>Plus ancien</option>
      <option value="name" @selected(request('sort') === 'name')>Nom A-Z</option>
      <option value="price_asc" @selected(request('sort') === 'price_asc')>Prix croissant</option>
      <option value="price_desc" @selected(request('sort') === 'price_desc')>Prix décroissant</option>
    </select>
    @include('components.date-filter', ['formId' => 'products-filter'])
  </div>
  <form id="products-filter" method="GET" style="display:none;"></form>

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
