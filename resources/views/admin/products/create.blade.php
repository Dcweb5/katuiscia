@extends('layouts.admin')

@section('title', 'Nouveau Produit')

@section('head')
<style>
.upload-zone { border: 2px dashed var(--color-border); border-radius: var(--radius-md); padding: var(--space-xl); text-align: center; cursor: pointer; transition: border-color 0.3s; }
.upload-zone:hover, .upload-zone.dragover { border-color: var(--color-warm); }
.upload-zone input[type="file"] { display: none; }
.image-preview { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; margin-top: var(--space-md); }
.image-preview img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: var(--radius-sm); }
</style>
@endsection

@section('content')
<div class="dashboard-content">
  <div class="admin-form-container--large">
    <a href="{{ route('admin.products.index') }}" style="display:inline-flex; align-items:center; gap:8px; color:var(--color-text-muted); font-size:14px; margin-bottom:var(--space-lg); text-decoration:none;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:16px; height:16px;"><path d="m15 18-6-6 6-6"/></svg>
      Retour aux produits
    </a>

    <h1 class="page-title">Nouveau Produit</h1>
    <p class="page-subtitle">Remplissez les informations du produit.</p>

    @if($errors->any())
    <div style="padding:12px 16px; background:rgba(199,80,80,0.1); border:1px solid var(--color-error); border-radius:8px; color:var(--color-error); margin-bottom:var(--space-lg); font-size:14px;">
      <ul style="margin:0; padding-left:20px;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card" style="margin-bottom:var(--space-xl);">
      <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-lg);">Informations générales</h3>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:var(--space-md);">
        <div class="admin-form-group" style="grid-column:1/-1;">
          <label class="admin-label">Nom du produit *</label>
          <input type="text" name="name" class="admin-input" value="{{ old('name') }}" placeholder="Ex: Nectar Lumineux" required>
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Slug</label>
          <input type="text" name="slug" class="admin-input" value="{{ old('slug') }}" placeholder="Auto-généré" style="color:var(--color-text-muted);">
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Badge</label>
          <select name="badge" class="admin-input">
            <option value="">Aucun</option>
            <option value="Best-seller" @selected(old('badge')=='Best-seller')>Best-seller</option>
            <option value="Nouveau" @selected(old('badge')=='Nouveau')>Nouveau</option>
            <option value="Promo" @selected(old('badge')=='Promo')>Promo</option>
            <option value="TOP" @selected(old('badge')=='TOP')>TOP</option>
          </select>
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Prix (€) *</label>
          <input type="number" name="price" class="admin-input" value="{{ old('price') }}" placeholder="125.00" step="0.01" required>
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Prix barré (€)</label>
          <input type="number" name="sale_price" class="admin-input" value="{{ old('sale_price') }}" placeholder="150.00" step="0.01">
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Stock</label>
          <input type="number" name="stock" class="admin-input" value="{{ old('stock', 0) }}" min="0">
        </div>

        <div class="admin-form-group">
          <label class="admin-label">SKU</label>
          <input type="text" name="sku" class="admin-input" value="{{ old('sku') }}" placeholder="Ex: NL-001">
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Contenance</label>
          <input type="text" name="size" class="admin-input" value="{{ old('size') }}" placeholder="Ex: 30ml, 50g">
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Besoin</label>
          <select name="need" class="admin-input">
            <option value="">—</option>
            <option value="hydratation" @selected(old('need')=='hydratation')>Hydratation</option>
            <option value="eclat" @selected(old('need')=='eclat')>Éclat</option>
            <option value="restauration" @selected(old('need')=='restauration')>Restauration</option>
          </select>
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Statut</label>
          <select name="is_active" class="admin-input">
            <option value="1">Publié</option>
            <option value="0">Brouillon</option>
          </select>
        </div>

        <div class="admin-form-group" style="grid-column:1/-1;">
          <label class="admin-label">Date limite de promotion (Laisser vide si pas de limite temporelle)</label>
          <input type="datetime-local" name="promo_expires_at" class="admin-input" value="{{ old('promo_expires_at') }}">
        </div>
      </div>

      <div class="admin-form-group" style="margin-top:var(--space-md);">
        <label class="admin-label">Description courte</label>
        <textarea name="description" class="admin-input" rows="2" placeholder="Ex: Huile Visage Réparatrice..." style="resize:vertical;">{{ old('description') }}</textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Description détaillée</label>
        <textarea name="long_description" class="admin-input" rows="4" placeholder="Description complète du produit..." style="resize:vertical;">{{ old('long_description') }}</textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Ingrédients Clés (Optionnel)</label>
        <textarea name="key_ingredients" class="admin-input" rows="3" placeholder="Ex: Lavande sauvage: Calme l'épiderme..." style="resize:vertical;">{{ old('key_ingredients') }}</textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Rituel d'Application (Optionnel)</label>
        <textarea name="application_ritual" class="admin-input" rows="3" placeholder="Ex: Appliquer délicatement chaque soir..." style="resize:vertical;">{{ old('application_ritual') }}</textarea>
      </div>
    </div>

    <div class="card" style="margin-bottom:var(--space-xl);">
      <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-lg);">Images du produit (max 5)</h3>

      <div class="upload-zone" id="upload-zone">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:32px; height:32px; color:var(--color-text-muted); margin:0 auto 8px;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        <p style="font-size:var(--text-sm); color:var(--color-text-muted);">Glissez-déposez vos images ou <span style="color:var(--color-warm); font-weight:500;">parcourir</span></p>
        <small style="color:var(--color-text-muted);">Formats: JPG, PNG, WebP • Max 5 images</small>
        <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/png,image/webp" onchange="previewImages(event)" style="display:none;">
      </div>
      <div class="image-preview" id="image-preview"></div>
    </div>

    <div class="card" style="margin-bottom:var(--space-xl);">
      <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-lg);">Catégories</h3>
      <div style="display:flex; flex-wrap:wrap; gap:var(--space-sm);">
        @foreach($categories as $category)
        <label style="display:flex; align-items:center; gap:6px; padding:8px 14px; border:1px solid var(--color-border); border-radius:var(--radius-sm); cursor:pointer; font-size:var(--text-sm); transition:all 0.2s;">
          <input type="checkbox" name="categories[]" value="{{ $category->id }}" style="accent-color:var(--color-warm);">
          {{ $category->name }}
        </label>
        @endforeach
      </div>
    </div>

    <div class="admin-form-actions">
      <a href="{{ route('admin.products.index') }}" class="btn-katuiscia">Annuler</a>
      <button type="submit" class="btn-katuiscia-filled">Créer le produit</button>
    </div>
  </form>
  </div>
</div>

<script>
const MAX_IMAGES = 5;
let uploadedFiles = [];

function previewImages(event) {
  const files = Array.from(event.target.files);
  handleFiles(files);
}

function handleFiles(files) {
  const remaining = MAX_IMAGES - uploadedFiles.length;
  const newFiles = files.slice(0, remaining);
  const maxSize = 2 * 1024 * 1024; // 2 Mo
  
  newFiles.forEach(function(file) {
    if (file.size > maxSize) {
      if (typeof showToast === 'function') {
        showToast("L'image \"" + file.name + "\" est trop volumineuse (max 2 Mo). Veuillez la compresser avant de l'ajouter.", "error");
      } else {
        alert("L'image \"" + file.name + "\" est trop volumineuse (max 2 Mo). Veuillez la compresser avant de l'ajouter.");
      }
      return;
    }
    if (!file.type.match(/^image\/(jpeg|png|webp)$/)) {
      if (typeof showToast === 'function') {
        showToast("Le format de \"" + file.name + "\" n'est pas supporté (JPG, PNG, WebP uniquement).", "error");
      } else {
        alert("Le format de \"" + file.name + "\" n'est pas supporté (JPG, PNG, WebP uniquement).");
      }
      return;
    }
    uploadedFiles.push(file);
  });

  renderPreview();
  updateFileInput();
}

function renderPreview() {
  const preview = document.getElementById('image-preview');
  preview.innerHTML = '';
  
  uploadedFiles.forEach(function(file, index) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const wrap = document.createElement('div');
      wrap.style.position = 'relative';
      wrap.dataset.index = index;
      wrap.draggable = true;
      
      const img = document.createElement('img');
      img.src = e.target.result;
      img.style.width = '100%';
      img.style.aspectRatio = '1';
      img.style.objectFit = 'cover';
      img.style.borderRadius = 'var(--radius-sm)';
      wrap.appendChild(img);
      
      const num = document.createElement('span');
      num.style.cssText = 'position:absolute;bottom:2px;right:2px;font-size:10px;background:rgba(0,0,0,0.6);color:white;padding:2px 6px;border-radius:4px;';
      num.textContent = (index + 1);
      wrap.appendChild(num);
      
      const remove = document.createElement('button');
      remove.innerHTML = '&times;';
      remove.style.cssText = 'position:absolute;top:2px;right:2px;width:22px;height:22px;border-radius:50%;border:none;background:rgba(199,80,80,0.9);color:white;font-size:14px;cursor:pointer;line-height:1;';
      remove.addEventListener('click', function(ev) {
        ev.stopPropagation();
        uploadedFiles.splice(index, 1);
        renderPreview();
        updateFileInput();
      });
      wrap.appendChild(remove);
      
      preview.appendChild(wrap);
    };
    reader.readAsDataURL(file);
  });
}

function updateFileInput() {
  const dt = new DataTransfer();
  uploadedFiles.forEach(function(f) { dt.items.add(f); });
  document.getElementById('images').files = dt.files;
}

// Drag & drop zone
var zone = document.getElementById('upload-zone');
zone.addEventListener('dragover', function(e) {
  e.preventDefault();
  e.stopPropagation();
  this.style.borderColor = 'var(--color-warm)';
  this.style.background = 'rgba(196,150,122,0.05)';
});
zone.addEventListener('dragleave', function(e) {
  e.preventDefault();
  e.stopPropagation();
  this.style.borderColor = '';
  this.style.background = '';
});
zone.addEventListener('drop', function(e) {
  e.preventDefault();
  e.stopPropagation();
  this.style.borderColor = '';
  this.style.background = '';
  handleFiles(Array.from(e.dataTransfer.files));
});

// Empêcher le clic sur la zone de déclencher deux fois le file input
zone.addEventListener('click', function(e) {
  if (e.target.tagName !== 'INPUT') {
    document.getElementById('images').click();
  }
});
</script>
@endsection
