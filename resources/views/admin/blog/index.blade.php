@extends('layouts.admin')
@section('title', 'Gestion du Blog')

@section('head')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
@endsection

@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Gestion du Blog</h1><p class="page-subtitle">Rédigez, modifiez et publiez vos articles.</p></div>
    <button class="btn-primary" onclick="openCreateModal()">+ Nouvel Article</button>
  </div>

  <div class="filter-pills">
    <div class="search-bar" style="margin-right:auto;max-width:280px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" form="blog-filter" value="{{ request('search') }}" placeholder="Rechercher un article...">
    </div>
    <a href="?" class="filter-pill {{ !request('status') ? 'active' : '' }}">Tous</a>
    <a href="?status=published" class="filter-pill {{ request('status') === 'published' ? 'active' : '' }}">Publiés</a>
    <a href="?status=draft" class="filter-pill {{ request('status') === 'draft' ? 'active' : '' }}">Brouillons</a>
    @include('components.date-filter', ['formId' => 'blog-filter'])
  </div>
  <form id="blog-filter" method="GET" style="display:none;"></form>

  <div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:var(--space-xl);">
    <div class="card stat-card"><span class="stat-title">Publiés</span><span class="stat-value">{{ $published }}</span></div>
    <div class="card stat-card"><span class="stat-title">Brouillons</span><span class="stat-value">{{ $drafts }}</span></div>
    <div class="card stat-card"><span class="stat-title">Total</span><span class="stat-value">{{ $posts->count() }}</span></div>
    <div class="card stat-card"><span class="stat-title">Catégories</span><span class="stat-value">{{ count($categories) }}</span></div>
  </div>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr><th>Article</th><th>Catégorie</th><th>Date</th><th>Statut</th><th style="width:130px;">Actions</th></tr>
      </thead>
      <tbody>
        @forelse($posts as $post)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:12px;">
              @if($post->image_url)
              <img src="{{ $post->image_url }}" style="width:48px;height:36px;border-radius:6px;object-fit:cover;">
              @else
              <div style="width:48px;height:36px;border-radius:6px;background:var(--color-peach);"></div>
              @endif
              <strong>{{ $post->title }}</strong>
            </div>
          </td>
          <td>{{ $post->category }}</td>
          <td style="color:var(--color-text-muted);">{{ $post->created_at->format('d/m/Y') }}</td>
          <td>
            <span style="font-size:11px;font-weight:600;color:#fff;padding:3px 10px;border-radius:var(--radius-full);background:{{ $post->status === 'published' ? 'var(--color-success)' : 'var(--color-text-muted)' }};">{{ $post->status === 'published' ? 'Publié' : 'Brouillon' }}</span>
          </td>
          <td>
            <div style="display:flex;gap:4px;">
              <button class="action-btn" title="Modifier" onclick="openEditModal({{ $post->id }})">✏️</button>
              <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" style="display:inline;" onsubmit="event.preventDefault();showConfirm('Supprimer cet article ?',()=>this.submit())">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn" title="Supprimer">🗑</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun article.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- FULL-SCREEN MODAL --}}
<dialog id="post-modal" class="fs-modal">
  <div class="fs-modal__content">
    <div class="fs-modal__header">
      <h2 id="modal-title" style="font-family:var(--font-heading);font-size:1.3rem;margin:0;">Nouvel Article</h2>
      <button onclick="closeModal()" class="fs-modal__close">&times;</button>
    </div>
    <form id="post-form" method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="fs-modal__body" novalidate>
      @csrf
      <input type="hidden" name="_method" id="form-method" value="POST">

      <div id="form-errors" style="display:none;background:#ffebee;color:#c62828;padding:12px 16px;border-radius:8px;margin-bottom:1rem;font-size:14px;border:1px solid #ef9a9a;"></div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
        <div class="admin-form-group">
          <label class="admin-label">Titre *</label>
          <input type="text" name="title" id="input-title" class="admin-input" required>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Catégorie *</label>
          <select name="category" id="input-category" class="admin-input" required>
            <option value="">Choisir...</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Statut</label>
          <select name="status" id="input-status" class="admin-input">
            <option value="draft">Brouillon</option>
            <option value="published">Publié</option>
          </select>
        </div>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Image de couverture</label>
        <input type="file" name="image" id="input-image" class="admin-input" accept="image/*" onchange="validateSingleImage(this)">
        <div id="current-image" style="display:none;margin-top:0.5rem;"></div>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Extrait (aperçu) *</label>
        <textarea name="excerpt" id="input-excerpt" class="admin-input" rows="2" required style="resize:vertical;"></textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Contenu *</label>
        <div id="editor-container" style="height:300px;"></div>
        <textarea name="content" id="input-content" style="display:none;"></textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Tags (séparés par des virgules)</label>
        <input type="text" name="tags" id="input-tags" class="admin-input" placeholder="cheveux, karité, routine">
      </div>

      <div class="admin-form-actions" style="padding-top:1rem;border-top:1px solid var(--color-border);">
        <button type="button" onclick="closeModal()" class="btn-katuiscia">Annuler</button>
        <button type="submit" name="action" value="draft" class="btn-katuiscia">Sauvegarder brouillon</button>
        <button type="submit" name="action" value="published" class="btn-katuiscia-filled">Publier</button>
      </div>
    </form>
  </div>
</dialog>

<style>
.fs-modal { border:none;border-radius:0;padding:0;margin:0;width:100%;height:100%;max-width:100%;max-height:100%;background:transparent; }
.fs-modal::backdrop { background:rgba(0,0,0,0.4); }
.fs-modal__content { background:#fff;border-radius:0;width:100%;height:100%;display:flex;flex-direction:column; }
.fs-modal__header { display:flex;justify-content:space-between;align-items:center;padding:1rem 2rem;border-bottom:1px solid var(--color-border);background:#fafafa;flex-shrink:0; }
.fs-modal__close { background:none;border:none;font-size:1.8rem;cursor:pointer;color:var(--color-text-muted);line-height:1;padding:0 4px; }
.fs-modal__body { padding:1.5rem 2rem;overflow-y:auto;flex:1;display:flex;flex-direction:column; }
.ql-toolbar.ql-snow { border:2px solid #d1d5db;border-bottom:none;border-radius:8px 8px 0 0;background:#fafafa;padding:8px 12px; }
.ql-container.ql-snow { border:2px solid #d1d5db;border-top:none;border-radius:0 0 8px 8px;font-size:15px;font-family:inherit;background:#fff;height:calc(100% - 42px); }
.ql-editor { height:100%;overflow-y:auto;line-height:1.7; }
.ql-editor.ql-blank::before { color:#9ca3af;font-style:normal; }
.ql-snow .ql-tooltip { z-index:99999 !important; }

/* Force font weights inside the editor to allow bold to visual toggle correctly */
.ql-editor p, .ql-editor span, .ql-editor li {
  font-weight: 400 !important;
}
.ql-editor strong, .ql-editor strong *, .ql-editor b, .ql-editor b * {
  font-weight: 700 !important;
}
</style>
@endsection

@section('scripts')
<script>
var postsData = @json($posts);
var quill = null;
var quillReady = false;

function initQuill() {
  if (quill) return;
  if (typeof Quill === 'undefined') {
    console.warn('Quill CDN not loaded, using plain textarea fallback');
    document.getElementById('input-content').style.display = '';
    document.getElementById('editor-container').style.display = 'none';
    return;
  }
  try {
    quill = new Quill('#editor-container', {
      theme: 'snow',
      placeholder: 'Rédigez votre article...',
      modules: { toolbar: [ [{ header: [1, 2, 3, false] }], ['bold','italic','underline','strike'], ['link','image','blockquote','code-block'], [{ list:'ordered' }, { list:'bullet' }], [{ align:[] }], ['clean'] ] }
    });
    quillReady = true;
    quill.getModule('toolbar').addHandler('image', function() {
      var input = document.createElement('input');
      input.setAttribute('type','file'); input.setAttribute('accept','image/*'); input.click();
      input.onchange = function() {
        var file = input.files[0]; if (!file) return;
        var fd = new FormData(); fd.append('file', file);
        fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
        fetch('{{ route("admin.blog.upload-image") }}', { method:'POST', body:fd, credentials:'same-origin' })
          .then(r => r.json()).then(data => { var r = quill.getSelection(true); quill.insertEmbed(r.index, 'image', data.location); })
          .catch(function() { console.error('Upload failed'); });
      };
    });
  } catch(e) { console.error('Quill init failed:', e); document.getElementById('input-content').style.display = ''; document.getElementById('editor-container').style.display = 'none'; quillReady = false; }
}

function showErrors(errors) {
  var el = document.getElementById('form-errors');
  if (Object.keys(errors).length === 0) { el.style.display = 'none'; return; }
  el.style.display = ''; el.innerHTML = Object.values(errors).flat().map(e => '<div style="margin-bottom:4px;">• ' + e + '</div>').join('');
}

function openCreateModal() {
  initQuill();
  document.getElementById('modal-title').textContent = 'Nouvel Article';
  document.getElementById('post-form').action = '{{ route('admin.blog.store') }}';
  document.getElementById('form-method').value = 'POST';
  document.getElementById('input-title').value = '';
  document.getElementById('input-category').value = '';
  document.getElementById('input-status').value = 'draft';
  document.getElementById('input-excerpt').value = '';
  document.getElementById('input-tags').value = '';
  document.getElementById('input-image').value = '';
  document.getElementById('current-image').style.display = 'none';
  document.getElementById('form-errors').style.display = 'none';
  document.getElementById('input-content').value = '';
  if (quill && quillReady) { quill.setContents([]); quill.setSelection(0); }
  document.getElementById('post-modal').showModal();
}

function openEditModal(id) {
  initQuill();
  var post = postsData.find(function(p){ return p.id === id; });
  if (!post) return;
  document.getElementById('modal-title').textContent = 'Modifier : ' + post.title;
  document.getElementById('post-form').action = '{{ url('admin/blog') }}/' + post.id;
  document.getElementById('form-method').value = 'PUT';
  document.getElementById('input-title').value = post.title;
  document.getElementById('input-category').value = post.category;
  document.getElementById('input-status').value = post.status;
  document.getElementById('input-excerpt').value = post.excerpt;
  document.getElementById('input-tags').value = post.tags || '';
  document.getElementById('input-image').value = '';
  document.getElementById('form-errors').style.display = 'none';
  if (post.image_url) {
    document.getElementById('current-image').style.display = '';
    document.getElementById('current-image').innerHTML = '<img src="'+post.image_url+'" style="max-height:80px;border-radius:6px;"> <small style="color:var(--color-text-muted);margin-left:0.5rem;">Image actuelle</small>';
  } else { document.getElementById('current-image').style.display = 'none'; }
  if (quill && quillReady) { quill.root.innerHTML = post.content || ''; quill.setSelection(0); }
  else { document.getElementById('input-content').value = post.content || ''; }
  document.getElementById('post-modal').showModal();
}

function closeModal() { document.getElementById('post-modal').close(); }

document.getElementById('post-form').addEventListener('submit', function(e) {
  if (quill && quillReady) { document.getElementById('input-content').value = quill.root.innerHTML; }
  var clicked = e.submitter;
  if (clicked && clicked.name === 'action') { document.getElementById('input-status').value = clicked.value; }
  var content = document.getElementById('input-content').value;
  if (!content.trim() && (!quill || !quillReady || !quill.getText().trim())) {
    e.preventDefault(); showErrors({content: ['Le contenu de l\'article est requis.']}); return false;
  }
});

document.addEventListener('DOMContentLoaded', function() {
  @if($errors->any())
    initQuill();
    showErrors(@json($errors->toArray()));
    if (quill && quillReady) { quill.root.innerHTML = @json(old('content', '')); }
    else { document.getElementById('input-content').style.display = ''; document.getElementById('input-content').value = @json(old('content', '')); }
    document.getElementById('input-title').value = @json(old('title', ''));
    document.getElementById('input-category').value = @json(old('category', ''));
    document.getElementById('input-status').value = @json(old('status', 'draft'));
    document.getElementById('input-excerpt').value = @json(old('excerpt', ''));
    document.getElementById('input-tags').value = @json(old('tags', ''));
    try { document.getElementById('post-modal').showModal(); } catch(ex) {}
  @endif
});

document.querySelectorAll('.sidebar-link[data-page]').forEach(function(l) { if(l.dataset.page === 'admin-blog') l.classList.add('active'); });

function validateSingleImage(input) {
  var file = input.files[0];
  if (!file) return;
  var maxSize = 2 * 1024 * 1024; // 2 Mo
  if (file.size > maxSize) {
    if (typeof showToast === 'function') {
      showToast("L'image \"" + file.name + "\" est trop volumineuse (max 2 Mo). Veuillez la compresser avant de l'ajouter.", "error");
    } else {
      alert("L'image \"" + file.name + "\" est trop volumineuse (max 2 Mo). Veuillez la compresser avant de l'ajouter.");
    }
    input.value = ''; // Reset input
  }
}
</script>
@endsection
