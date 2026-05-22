@extends('layouts.admin')

@section('title', 'Modifier — ' . $product->name)

@section('head')
<style>
.upload-zone { border: 2px dashed var(--color-border); border-radius: var(--radius-md); padding: var(--space-xl); text-align: center; cursor: pointer; transition: border-color 0.3s; }
.upload-zone:hover { border-color: var(--color-warm); }
.upload-zone input[type="file"] { display: none; }
.upload-zone.highlight { border-color: var(--color-warm); background: rgba(196,150,122,0.05); }
.img-card { position:relative; border-radius:var(--radius-md); overflow:hidden; border:2px solid var(--color-border); background:var(--color-gray-k); cursor:grab; transition:all 0.2s; }
.img-card:hover { border-color:var(--color-warm); transform:translateY(-2px); box-shadow:var(--shadow-md); }
.img-card.primary { border-color:var(--color-warm); box-shadow:0 0 0 2px rgba(196,150,122,0.3); }
.img-card.dragging { opacity:0.5; cursor:grabbing; }
.img-card img { width:100%; aspect-ratio:1; object-fit:cover; display:block; }
.img-badge { position:absolute; font-size:9px; padding:2px 8px; border-radius:var(--radius-sm); font-weight:600; z-index:2; }
.img-badge-primary { top:4px; left:4px; background:var(--color-warm); color:white; }
.img-badge-hover { top:4px; left:4px; background:var(--color-dark); color:white; }
.img-badge-num { bottom:4px; right:4px; background:rgba(0,0,0,0.6); color:white; font-size:10px; padding:1px 6px; border-radius:4px; }
.img-actions { position:absolute; top:4px; right:4px; display:flex; flex-direction:column; gap:2px; }
.img-btn { width:26px; height:26px; border-radius:4px; border:none; background:rgba(255,255,255,0.9); cursor:pointer; font-size:12px; display:flex; align-items:center; justify-content:center; transition:all 0.2s; }
.img-btn:hover { background:white; transform:scale(1.1); }
.img-btn-star { color:var(--color-warm); }
.img-btn-del { color:var(--color-error); }
</style>
@endsection

@section('content')
<div class="dashboard-content">
  <a href="{{ route('admin.products.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--color-text-muted);font-size:14px;margin-bottom:var(--space-lg);text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:16px;height:16px;"><path d="m15 18-6-6 6-6"/></svg> Retour aux produits
  </a>

  <h1 class="page-title">Modifier — {{ $product->name }}</h1>

  @if(session('success'))
  <div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);">{{ session('success') }}</div>
  @endif
  @if($errors->any())
  <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:var(--space-lg);"><ul style="margin:0;padding-left:20px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" style="max-width:800px;">
    @csrf @method('PUT')

    <div class="card" style="margin-bottom:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Informations générales</h3>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-md);">
        <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-label">Nom *</label><input type="text" name="name" class="admin-input" value="{{ old('name', $product->name) }}" required></div>
        <div class="admin-form-group"><label class="admin-label">Slug</label><input type="text" name="slug" class="admin-input" value="{{ old('slug', $product->slug) }}"></div>
        <div class="admin-form-group"><label class="admin-label">Badge</label><select name="badge" class="admin-input"><option value="">Aucun</option><option value="Best-seller" @selected(old('badge',$product->badge)=='Best-seller')>Best-seller</option><option value="Nouveau" @selected(old('badge',$product->badge)=='Nouveau')>Nouveau</option><option value="Promo" @selected(old('badge',$product->badge)=='Promo')>Promo</option><option value="TOP" @selected(old('badge',$product->badge)=='TOP')>TOP</option></select></div>
        <div class="admin-form-group"><label class="admin-label">Prix (€) *</label><input type="number" name="price" class="admin-input" value="{{ old('price', $product->price) }}" step="0.01" required></div>
        <div class="admin-form-group"><label class="admin-label">Prix barré (€)</label><input type="number" name="sale_price" class="admin-input" value="{{ old('sale_price', $product->sale_price) }}" step="0.01"></div>
        <div class="admin-form-group"><label class="admin-label">Stock</label><input type="number" name="stock" class="admin-input" value="{{ old('stock', $product->stock) }}" min="0"></div>
        <div class="admin-form-group"><label class="admin-label">SKU</label><input type="text" name="sku" class="admin-input" value="{{ old('sku', $product->sku) }}"></div>
        <div class="admin-form-group"><label class="admin-label">Contenance</label><input type="text" name="size" class="admin-input" value="{{ old('size', $product->size) }}"></div>
        <div class="admin-form-group"><label class="admin-label">Besoin</label><select name="need" class="admin-input"><option value="">—</option><option value="hydratation" @selected(old('need',$product->need)=='hydratation')>Hydratation</option><option value="eclat" @selected(old('need',$product->need)=='eclat')>Éclat</option><option value="restauration" @selected(old('need',$product->need)=='restauration')>Restauration</option></select></div>
        <div class="admin-form-group"><label class="admin-label">Statut</label><select name="is_active" class="admin-input"><option value="1" @selected(old('is_active',$product->is_active)==true)>Publié</option><option value="0" @selected(old('is_active',$product->is_active)==false)>Brouillon</option></select></div>
      </div>
      <div class="admin-form-group" style="margin-top:var(--space-md);"><label class="admin-label">Description courte</label><textarea name="description" class="admin-input" rows="2" style="resize:vertical;">{{ old('description', $product->description) }}</textarea></div>
      <div class="admin-form-group"><label class="admin-label">Description détaillée</label><textarea name="long_description" class="admin-input" rows="4" style="resize:vertical;">{{ old('long_description', $product->long_description) }}</textarea></div>
    </div>

    <!-- IMAGES (pas de formulaires imbriqués — tout en JS) -->
    <div class="card" style="margin-bottom:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);display:flex;align-items:center;gap:var(--space-md);">
        <span>📸 Images ({{ $product->images->count() }}/5)</span>
        <span style="font-size:11px;color:var(--color-text-muted);font-weight:normal;">Glissez pour réorganiser</span>
      </h3>

      <!-- Grille images existantes -->
      <div id="existing-images-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:var(--space-md);margin-bottom:var(--space-lg);">
        @foreach($product->images->sortBy('order') as $image)
        <div class="img-card @if($image->is_primary) primary @endif" data-id="{{ $image->id }}" data-order="{{ $image->order }}" draggable="true">
          <img src="{{ asset('storage/' . $image->path) }}" alt="Image">
          @if($image->is_primary)
          <span class="img-badge img-badge-primary">★ Principale</span>
          @elseif($loop->index === 1)
          <span class="img-badge img-badge-hover">↗ Hover</span>
          @endif
          <span class="img-badge-num">{{ $loop->index + 1 }}</span>
          <div class="img-actions">
            <button type="button" class="img-btn img-btn-star" title="Définir principale" onclick="imageSetPrimary({{ $image->id }})">★</button>
            <button type="button" class="img-btn img-btn-del" title="Supprimer" onclick="imageDelete({{ $image->id }})">×</button>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Zone upload nouvelles images -->
      @if($product->images->count() < 5)
      <div class="upload-zone" id="upload-zone">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:32px;height:32px;color:var(--color-text-muted);margin:0 auto 8px;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        <p style="font-size:var(--text-sm);color:var(--color-text-muted);">Glissez-déposez ou <span style="color:var(--color-warm);font-weight:500;">parcourir</span></p>
        <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/png,image/webp" onchange="handleNewFiles(event)" style="display:none;">
      </div>
      <div id="new-images-preview" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:var(--space-md);margin-top:var(--space-md);"></div>
      @endif
    </div>

    <!-- Catégories -->
    <div class="card" style="margin-bottom:var(--space-xl);">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Catégories</h3>
      <div style="display:flex;flex-wrap:wrap;gap:var(--space-sm);">
        @foreach($categories as $category)
        <label style="display:flex;align-items:center;gap:6px;padding:8px 14px;border:1px solid {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'var(--color-warm)' : 'var(--color-border)' }};border-radius:var(--radius-sm);cursor:pointer;font-size:var(--text-sm);{{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'background:rgba(196,150,122,0.1);' : '' }}">
          <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, old('categories', $product->categories->pluck('id')->toArray()))) style="accent-color:var(--color-warm);">
          {{ $category->name }}
        </label>
        @endforeach
      </div>
    </div>

    <div style="display:flex;gap:var(--space-sm);justify-content:flex-end;">
      <a href="{{ route('admin.products.index') }}" style="padding:10px 20px;border:1px solid var(--color-border);border-radius:var(--radius-sm);background:transparent;font-size:var(--text-sm);text-decoration:none;color:inherit;">Annuler</a>
      <button type="submit" class="btn-primary">Enregistrer</button>
    </div>
  </form>
</div>

@php
  $productId = $product->id;
  $imagesCount = $product->images->count();
@endphp

<script>
// ===== DRAG REORDER DES IMAGES EXISTANTES =====
const grid = document.getElementById('existing-images-grid');
let dragged = null;

grid.addEventListener('dragstart', function(e) {
  const card = e.target.closest('.img-card');
  if (!card) return;
  dragged = card;
  card.classList.add('dragging');
  e.dataTransfer.effectAllowed = 'move';
});

grid.addEventListener('dragend', function(e) {
  const card = e.target.closest('.img-card');
  if (card) card.classList.remove('dragging');
  dragged = null;
});

grid.addEventListener('dragover', function(e) {
  e.preventDefault();
  const card = e.target.closest('.img-card');
  if (!card || card === dragged) return;
  
  const cards = [...grid.querySelectorAll('.img-card')];
  const draggedIndex = cards.indexOf(dragged);
  const overIndex = cards.indexOf(card);
  
  if (draggedIndex < overIndex) {
    grid.insertBefore(dragged, card.nextSibling);
  } else {
    grid.insertBefore(dragged, card);
  }
  
  // Mettre à jour l'ordre via API
  updateImageOrder();
});

grid.addEventListener('drop', function(e) { e.preventDefault(); });

async function updateImageOrder() {
  const cards = [...grid.querySelectorAll('.img-card')];
  const orderData = cards.map((card, index) => ({
    id: parseInt(card.dataset.id),
    order: index
  }));
  
  const token = '{{ csrf_token() }}';
  try {
    await fetch('{{ route("admin.products.images.reorder", $product) }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
      body: JSON.stringify({ order: orderData })
    });
    // Mettre à jour les numéros
    cards.forEach((card, i) => {
      const badge = card.querySelector('.img-badge-num');
      if (badge) badge.textContent = i + 1;
    });
    location.reload();
  } catch(e) { console.error(e); }
}

// ===== ACTIONS IMAGES (JS fetch au lieu de formulaires imbriqués) =====
const csrfToken = '{{ csrf_token() }}';
const productId = {{ $productId }};

async function imageSetPrimary(id) {
  try {
    const r = await fetch('/admin/produits/' + productId + '/images/' + id + '/primary', {
      method: 'PUT', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    });
    if (r.ok) location.reload();
    else location.reload();
  } catch(e) { location.reload(); }
}

async function imageDelete(id) {
  try {
    const r = await fetch('/admin/produits/' + productId + '/images/' + id, {
      method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    });
    if (r.ok) {
      var card = document.querySelector('.img-card[data-id="' + id + '"]');
      if (card) { card.style.transition = 'opacity 0.3s'; card.style.opacity = '0'; setTimeout(function(){ card.remove(); updateImageCount(); }, 300); }
      if (typeof showToast === 'function') showToast('Image supprimee', 'success');
    }
  } catch(e) { location.reload(); }
}

function updateImageCount() {
  var count = document.querySelectorAll('.img-card').length;
  var zone = document.getElementById('upload-zone');
  var preview = document.getElementById('new-images-preview');
  if (zone && count >= 5) { zone.style.display = 'none'; if (preview) preview.style.display = 'none'; }
  else if (zone) { zone.style.display = ''; }
}

// ===== UPLOAD NOUVELLES IMAGES =====
var newFiles = [];

function handleNewFiles(event) {
  var files = Array.from(event.target.files || []);
  var remaining = 5 - {{ $imagesCount }} - newFiles.length;
  files = files.slice(0, Math.max(0, remaining));
  files.forEach(function(f) { newFiles.push(f); });
  renderNewPreview();
  updateFileInput();
}

function renderNewPreview() {
  var preview = document.getElementById('new-images-preview');
  preview.innerHTML = '';
  newFiles.forEach(function(file, i) {
    var reader = new FileReader();
    reader.onload = function(e) {
      var w = document.createElement('div');
      w.style.cssText = 'position:relative;border:2px dashed var(--color-border);border-radius:var(--radius-md);overflow:hidden;';
      var img = document.createElement('img');
      img.src = e.target.result;
      img.style.cssText = 'width:100%;aspect-ratio:1;object-fit:cover;';
      var num = document.createElement('span');
      num.className = 'img-badge-num';
      num.textContent = ({{ $imagesCount }} + i + 1);
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'img-btn img-btn-del';
      btn.innerHTML = '×';
      btn.style.cssText = 'position:absolute;top:4px;right:4px;width:26px;height:26px;cursor:pointer;z-index:3;';
      btn.onclick = function() { newFiles.splice(i,1); renderNewPreview(); updateFileInput(); };
      w.appendChild(img); w.appendChild(num); w.appendChild(btn);
      preview.appendChild(w);
    };
    reader.readAsDataURL(file);
  });
}

function updateFileInput() {
  var dt = new DataTransfer();
  newFiles.forEach(function(f) { dt.items.add(f); });
  document.getElementById('images').files = dt.files;
}

// Drag & drop upload zone
var zone = document.getElementById('upload-zone');
if (zone) {
  zone.addEventListener('dragover', function(e) { e.preventDefault(); e.stopPropagation(); zone.classList.add('highlight'); });
  zone.addEventListener('dragleave', function(e) { e.preventDefault(); e.stopPropagation(); zone.classList.remove('highlight'); });
  zone.addEventListener('drop', function(e) {
    e.preventDefault(); e.stopPropagation(); zone.classList.remove('highlight');
    handleNewFiles({ target: { files: e.dataTransfer.files } });
  });
  zone.addEventListener('click', function(e) { if (e.target.tagName !== 'INPUT') document.getElementById('images').click(); });
}
</script>
@endsection
