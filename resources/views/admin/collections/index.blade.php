@extends('layouts.admin')
@section('title', 'Gestion des Collections')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Gestion des Collections</h1><p class="page-subtitle">Créez et gérez vos packs de produits.</p></div>
    <button class="btn-primary" onclick="openCreateModal()">+ Nouvelle Collection</button>
  </div>

  @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded',function(){showToast(@json(session('success')),'success');});</script>
  @endif

  <div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:var(--space-xl);">
    <div class="card stat-card"><span class="stat-title">Actives</span><span class="stat-value">{{ $active }}</span></div>
    <div class="card stat-card"><span class="stat-title">Brouillons</span><span class="stat-value">{{ $drafts }}</span></div>
    <div class="card stat-card"><span class="stat-title">Total</span><span class="stat-value">{{ $collections->count() }}</span></div>
    <div class="card stat-card"><span class="stat-title">Produits</span><span class="stat-value">{{ $collections->sum(fn($c) => $c->products->count()) }}</span></div>
  </div>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr><th>Collection</th><th>Produits</th><th>Prix</th><th>Catégorie</th><th>Statut</th><th style="width:150px;">Actions</th></tr>
      </thead>
      <tbody>
        @forelse($collections as $collection)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:12px;">
              @if($collection->image_url)
              <img src="{{ $collection->image_url }}" style="width:48px;height:36px;border-radius:6px;object-fit:cover;">
              @else
              <div style="width:48px;height:36px;border-radius:6px;background:var(--color-peach);"></div>
              @endif
              <div>
                <strong>{{ $collection->name }}</strong>
                @if($collection->original_price && $collection->original_price > $collection->price)
                <span style="font-size:11px;color:var(--color-success);display:block;">-{{ $collection->discount_percent }}%</span>
                @endif
              </div>
            </div>
          </td>
          <td style="font-size:var(--text-sm);">{{ $collection->products->count() }} produit(s)</td>
          <td>
            @if($collection->original_price && $collection->original_price > $collection->price)
            <span style="text-decoration:line-through;color:var(--color-text-muted);font-size:12px;">{{ number_format($collection->original_price, 0, ',', ' ') }} €</span>
            @endif
            <strong>{{ number_format($collection->price, 0, ',', ' ') }} €</strong>
          </td>
          <td style="font-size:var(--text-sm);">{{ $collection->category?->name ?? '—' }}</td>
          <td>
            <span style="font-size:11px;font-weight:600;color:#fff;padding:3px 10px;border-radius:var(--radius-full);background:{{ $collection->is_active ? 'var(--color-success)' : 'var(--color-text-muted)' }};">{{ $collection->is_active ? 'Actif' : 'Brouillon' }}</span>
          </td>
          <td>
            <div style="display:flex;gap:4px;">
              <button class="action-btn" title="Modifier" onclick="openEditModal({{ $collection->id }})">✏️</button>
              <form method="POST" action="{{ route('admin.collections.toggle', $collection) }}" style="display:inline;">
                @csrf @method('PUT')
                <button type="submit" class="action-btn" title="{{ $collection->is_active ? 'Désactiver' : 'Activer' }}">{{ $collection->is_active ? '👁' : '👁‍🗨' }}</button>
              </form>
              <form method="POST" action="{{ route('admin.collections.destroy', $collection) }}" style="display:inline;" onsubmit="return confirm('Supprimer cette collection ?')">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn" title="Supprimer">🗑</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucune collection.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- MODAL --}}
<dialog id="collection-modal" style="border:none;border-radius:12px;padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.2);max-width:700px;width:95%;">
  <div style="padding:0;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding:1.25rem 1.5rem;border-bottom:1px solid var(--color-border);background:#fafafa;">
      <h2 style="font-family:var(--font-heading);font-size:var(--text-xl);margin:0;" id="modal-title">Nouvelle Collection</h2>
      <button onclick="closeModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--color-text-muted);">&times;</button>
    </div>
    <form id="collection-form" method="POST" action="{{ route('admin.collections.store') }}" enctype="multipart/form-data" style="padding:1.5rem;max-height:75vh;overflow-y:auto;">
      @csrf
      <input type="hidden" name="_method" id="form-method" value="POST">

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div class="admin-form-group">
          <label class="admin-label">Nom *</label>
          <input type="text" name="name" id="input-name" class="admin-input" required>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Catégorie</label>
          <select name="category_id" id="input-category" class="admin-input">
            <option value="">Aucune</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <div class="admin-form-group">
          <label class="admin-label">Prix du pack *</label>
          <input type="number" name="price" id="input-price" class="admin-input" step="0.01" min="0" required>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Prix barré (optionnel)</label>
          <input type="number" name="original_price" id="input-original-price" class="admin-input" step="0.01" min="0">
        </div>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Description</label>
        <textarea name="description" id="input-desc" class="admin-input" rows="2"></textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Image</label>
        <input type="file" name="image" id="input-image" class="admin-input" accept="image/*">
        <div id="current-image" style="display:none;margin-top:0.5rem;"></div>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Produits inclus</label>
        <div id="products-list" style="max-height:200px;overflow-y:auto;border:1px solid var(--color-border);border-radius:8px;padding:0.5rem;">
          @foreach($products as $product)
          <label style="display:flex;align-items:center;gap:0.5rem;padding:6px 8px;cursor:pointer;border-radius:4px;font-size:14px;" class="product-check-label">
            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-check">
            @if($product->image_primary)
            <img src="{{ Storage::url($product->image_primary) }}" style="width:28px;height:28px;border-radius:4px;object-fit:cover;">
            @endif
            {{ $product->name }}
          </label>
          @endforeach
        </div>
      </div>

      <div class="admin-form-group" style="display:flex;align-items:center;gap:0.5rem;">
        <input type="checkbox" name="is_active" id="input-active" value="1" checked>
        <label for="input-active" style="font-size:14px;">Actif</label>
      </div>

      <div style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid var(--color-border);">
        <button type="button" onclick="closeModal()" class="action-btn" style="padding:0.5rem 1rem;">Annuler</button>
        <button type="submit" name="action" value="draft" class="btn-primary" style="background:transparent;color:var(--color-text);border:1px solid var(--color-border);">Sauvegarder brouillon</button>
        <button type="submit" name="action" value="publish" class="btn-primary">Publier</button>
      </div>
    </form>
  </div>
</dialog>

<style>
::backdrop { background:rgba(0,0,0,0.4); }
.product-check-label:hover { background:#faf7f2; }
</style>

<script>
var collectionsData = @json($collections->load('products'));

function openCreateModal() {
  document.getElementById('modal-title').textContent = 'Nouvelle Collection';
  document.getElementById('collection-form').action = '{{ route('admin.collections.store') }}';
  document.getElementById('form-method').value = 'POST';
  document.getElementById('input-name').value = '';
  document.getElementById('input-category').value = '';
  document.getElementById('input-price').value = '';
  document.getElementById('input-original-price').value = '';
  document.getElementById('input-desc').value = '';
  document.getElementById('input-image').value = '';
  document.getElementById('input-active').checked = true;
  document.getElementById('current-image').style.display = 'none';
  document.querySelectorAll('.product-check').forEach(c => c.checked = false);
  document.getElementById('collection-modal').showModal();
}

function openEditModal(id) {
  var c = collectionsData.find(col => col.id === id);
  if (!c) return;
  document.getElementById('modal-title').textContent = 'Modifier : ' + c.name;
  document.getElementById('collection-form').action = '{{ url('admin/collections') }}/' + c.id;
  document.getElementById('form-method').value = 'PUT';
  document.getElementById('input-name').value = c.name;
  document.getElementById('input-category').value = c.category_id || '';
  document.getElementById('input-price').value = c.price;
  document.getElementById('input-original-price').value = c.original_price || '';
  document.getElementById('input-desc').value = c.description || '';
  document.getElementById('input-image').value = '';
  document.getElementById('input-active').checked = c.is_active;
  if (c.image_url) {
    document.getElementById('current-image').style.display = '';
    document.getElementById('current-image').innerHTML = '<img src="'+c.image_url+'" style="max-height:80px;border-radius:6px;"> <small style="color:var(--color-text-muted);margin-left:0.5rem;">Image actuelle</small>';
  } else {
    document.getElementById('current-image').style.display = 'none';
  }
  document.querySelectorAll('.product-check').forEach(ch => {
    ch.checked = c.products && c.products.some(p => p.id == ch.value);
  });
  document.getElementById('collection-modal').showModal();
}

function closeModal() {
  document.getElementById('collection-modal').close();
}

document.getElementById('collection-form').addEventListener('submit', function(e) {
  var clicked = e.submitter;
  if (clicked && clicked.name === 'action' && clicked.value === 'draft') {
    document.getElementById('input-active').checked = false;
  }
});

document.querySelectorAll('.sidebar-link[data-page]').forEach(function(l) {
  if(l.dataset.page === 'admin-collections') l.classList.add('active');
});
</script>
@endsection
