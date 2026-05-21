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

  {{-- Toast handled by global component --}}

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
</script>
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
      modules: {
        toolbar: [
          [{ header: [1, 2, 3, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          ['link', 'image', 'blockquote', 'code-block'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          [{ align: [] }],
          ['clean']
        ]
      }
    });
    quillReady = true;

    quill.getModule('toolbar').addHandler('image', function() {
      var input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', 'image/*');
      input.click();
      input.onchange = function() {
        var file = input.files[0];
        if (!file) return;
        var formData = new FormData();
        formData.append('file', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value || '');
        fetch('{{ route('admin.blog.upload-image') }}', { method:'POST', body:formData, credentials:'same-origin' })
          .then(r => r.json())
          .then(data => {
            var range = quill.getSelection(true);
            quill.insertEmbed(range.index, 'image', data.location);
          })
          .catch(function(err) { console.error('Upload failed:', err); alert('Erreur lors du téléchargement de l\'image.'); });
      };
    });
  } catch(e) {
    console.error('Quill init failed:', e);
    document.getElementById('input-content').style.display = '';
    document.getElementById('editor-container').style.display = 'none';
    quillReady = false;
  }
}

// Form errors
function showErrors(errors) {
  var el = document.getElementById('form-errors');
  if (Object.keys(errors).length === 0) { el.style.display = 'none'; return; }
  el.style.display = '';
  el.innerHTML = Object.values(errors).flat().map(e => '<div style="margin-bottom:4px;">• ' + e + '</div>').join('');
}

// Store & Update helpers
function submitForm(actionUrl, method) {
  document.getElementById('post-form').action = actionUrl;
  document.getElementById('form-method').value = method;
  if (quill && quillReady) {
    document.getElementById('input-content').value = quill.root.innerHTML;
  }
}

function openCreateModal() {
  initQuill();
  document.getElementById('modal-title').textContent = 'Nouvel Article';
  submitForm('{{ route('admin.blog.store') }}', 'POST');
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
  var post = postsData.find(p => p.id === id);
  if (!post) return;
  document.getElementById('modal-title').textContent = 'Modifier : ' + post.title;
  submitForm('{{ url('admin/blog') }}/' + post.id, 'PUT');
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
  } else {
    document.getElementById('current-image').style.display = 'none';
  }
  if (quill && quillReady) { quill.root.innerHTML = post.content || ''; quill.setSelection(0); }
  else { document.getElementById('input-content').value = post.content || ''; }
  document.getElementById('post-modal').showModal();
}

function closeModal() {
  document.getElementById('post-modal').close();
}

// Handle form submit
document.getElementById('post-form').addEventListener('submit', function(e) {
  if (quill && quillReady) {
    document.getElementById('input-content').value = quill.root.innerHTML;
  }

  var clicked = e.submitter;
  if (clicked && clicked.name === 'action') {
    document.getElementById('input-status').value = clicked.value;
  }

  var content = document.getElementById('input-content').value;
  if (!content.trim() && (!quill || !quillReady || !quill.getText().trim())) {
    e.preventDefault();
    showErrors({content: ['Le contenu de l\'article est requis.']});
    return false;
  }
});

// Show validation errors if present on page load
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

  @if(session('success'))
    showToast(@json(session('success')));
  @endif
});

document.querySelectorAll('.sidebar-link[data-page]').forEach(function(l) {
  if(l.dataset.page === 'admin-blog') l.classList.add('active');
});
</script>
@endsection
