@extends('layouts.admin')

@section('title', 'Nouvelle Catégorie')

@section('content')
<div class="dashboard-content">
  <a href="{{ route('admin.categories.index') }}" style="display:inline-flex; align-items:center; gap:8px; color:var(--color-text-muted); font-size:14px; margin-bottom:var(--space-lg); text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:16px; height:16px;"><path d="m15 18-6-6 6-6"/></svg>
    Retour aux catégories
  </a>

  <h1 class="page-title">Nouvelle Catégorie</h1>

  @if($errors->any())
  <div style="padding:12px 16px; background:rgba(199,80,80,0.1); border:1px solid var(--color-error); border-radius:8px; color:var(--color-error); margin-bottom:var(--space-lg);">
    <ul style="margin:0; padding-left:20px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
  @endif

  <form method="POST" action="{{ route('admin.categories.store') }}" style="max-width:500px;">
    @csrf
    <div class="card">
      <div class="admin-form-group">
        <label class="admin-label">Nom *</label>
        <input type="text" name="name" class="admin-input" value="{{ old('name') }}" required>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Slug</label>
        <input type="text" name="slug" class="admin-input" value="{{ old('slug') }}" placeholder="Auto-généré" style="color:var(--color-text-muted);">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Description</label>
        <textarea name="description" class="admin-input" rows="3" style="resize:vertical;">{{ old('description') }}</textarea>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Catégorie parente</label>
        <select name="parent_id" class="admin-input">
          <option value="">Aucune (catégorie racine)</option>
          @foreach($parentCategories as $parent)
          <option value="{{ $parent->id }}" @selected(old('parent_id')==$parent->id)>{{ $parent->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="admin-form-group">
        <label style="display:flex; align-items:center; gap:8px; font-size:var(--text-sm); cursor:pointer;">
          <input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--color-warm);"> Active (visible en boutique)
        </label>
      </div>
    </div>
    <div style="display:flex; gap:var(--space-sm); justify-content:flex-end; margin-top:var(--space-lg);">
      <a href="{{ route('admin.categories.index') }}" style="padding:10px 20px; border:1px solid var(--color-border); border-radius:var(--radius-sm); background:transparent; font-size:var(--text-sm); text-decoration:none; color:inherit;">Annuler</a>
      <button type="submit" class="btn-primary">Créer la catégorie</button>
    </div>
  </form>
</div>
@endsection
