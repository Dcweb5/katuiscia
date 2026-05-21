@extends('layouts.account')
@section('title','Mes Avis')
@section('content')
<div class="dashboard-content">
  <h1 class="page-title">Mes Avis</h1>
  <p class="page-subtitle">Partagez votre expérience et gagnez <strong>50 points</strong> par avis approuvé.</p>

  <!-- PRODUITS EN ATTENTE D'AVIS -->
  @if($pendingProducts->count() > 0)
  <h3 style="font-family:var(--font-heading);font-size:var(--text-xl);margin-bottom:var(--space-md);display:flex;align-items:center;gap:10px;">
    <span style="width:8px;height:8px;background:var(--color-warm);border-radius:50%;display:inline-block;"></span>
    Donner mon avis ({{ $pendingProducts->count() }})
  </h3>
  <p style="font-size:var(--text-sm);color:var(--color-text-muted);margin-bottom:var(--space-lg);">Vous avez acheté ces produits, partagez votre expérience pour gagner 50 points par avis.</p>
  <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:var(--space-md);margin-bottom:var(--space-2xl);">
    @foreach($pendingProducts as $p)
    <div class="card" style="border:2px solid var(--color-peach);padding:var(--space-lg);">
      <div style="display:flex;gap:var(--space-md);align-items:center;margin-bottom:var(--space-md);">
        @php $img = $p->images->first(); @endphp
        <img src="{{ $img ? asset('storage/'.$img->path) : asset($p->image_primary ?: 'assets/images/K ICONE.png') }}"
             style="width:64px;height:64px;border-radius:var(--radius-md);object-fit:cover;" onerror="this.src='{{ asset('assets/images/K ICONE.png') }}'">
        <div>
          <strong style="font-size:var(--text-sm);">{{ $p->name }}</strong>
          <p style="font-size:11px;color:var(--color-text-muted);">{{ $p->categories->first()->name ?? '' }} • {{ number_format($p->price,0,',',' ') }} €</p>
        </div>
      </div>
      <button class="btn-primary" style="width:100%;" onclick="openCreateModal({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ $img ? asset('storage/'.$img->path) : asset($p->image_primary ?: 'assets/images/K ICONE.png') }}')">Donner mon avis (+50 pts)</button>
    </div>
    @endforeach
  </div>
  @endif

  <!-- AVIS PUBLIÉS -->
  <h3 style="font-family:var(--font-heading);font-size:var(--text-xl);margin-bottom:var(--space-md);">Mes avis ({{ $reviews->count() }})</h3>
  @if($reviews->count() > 0)
  <div style="display:flex;flex-direction:column;gap:var(--space-lg);">
    @foreach($reviews as $review)
    <div class="card" style="padding:var(--space-xl);{{ $review->is_approved ? '' : 'border-left:4px solid var(--color-warm);' }}">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-md);">
        <div style="display:flex;gap:var(--space-md);align-items:center;">
          @php $pImg = $review->product->images->first(); @endphp
          <img src="{{ $pImg ? asset('storage/'.$pImg->path) : asset($review->product->image_primary ?: 'assets/images/K ICONE.png') }}"
               style="width:64px;height:64px;border-radius:var(--radius-md);object-fit:cover;" onerror="this.src='{{ asset('assets/images/K ICONE.png') }}'">
          <div>
            <a href="{{ url('produit/'.$review->product->slug) }}" style="font-weight:600;color:var(--color-dark);text-decoration:none;font-size:var(--text-md);">{{ $review->product->name }}</a>
            <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
              <span style="color:var(--color-warm);font-size:var(--text-md);">{{ str_repeat('★',$review->rating) }}{{ str_repeat('☆',5-$review->rating) }}</span>
              @if(!$review->is_approved)<span style="font-size:10px;background:var(--color-warm);color:white;padding:2px 8px;border-radius:var(--radius-full);">En attente</span>@else<span style="font-size:10px;background:var(--color-success);color:white;padding:2px 8px;border-radius:var(--radius-full);">Approuvé</span>@endif
            </div>
          </div>
        </div>
        <div style="display:flex;gap:6px;align-items:center;">
          <span style="font-size:11px;color:var(--color-text-muted);">Publié le {{ $review->created_at->format('d/m/Y') }}</span>
          <button class="action-btn" title="Modifier" onclick="openEditModal({{ $review->id }},'{{ addslashes($review->title) }}','{{ addslashes($review->body ?? '') }}',{{ $review->rating }})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <form method="POST" action="{{ route('reviews.destroy', $review) }}" onsubmit="return confirm('Supprimer cet avis ?')" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="action-btn action-btn--danger" title="Supprimer">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
          </form>
        </div>
      </div>
      <h4 style="font-size:var(--text-base);color:var(--color-dark);margin-bottom:4px;">{{ $review->title }}</h4>
      @if($review->body)<p style="font-size:var(--text-sm);color:var(--color-text-light);line-height:1.6;">{{ $review->body }}</p>@endif
      @if($review->is_approved)<span style="font-size:11px;color:var(--color-success);margin-top:var(--space-sm);display:block;">✨ +50 pts gagnés</span>@endif
    </div>
    @endforeach
  </div>
  @else
  <div style="text-align:center;padding:4rem;color:var(--color-text-muted);">
    <div style="font-size:48px;margin-bottom:var(--space-md);">⭐</div>
    <p style="font-size:var(--text-lg);color:var(--color-dark);margin-bottom:var(--space-sm);">Aucun avis rédigé</p>
    <p>Lorsque vous achèterez un produit, il apparaîtra ci-dessus pour que vous puissiez donner votre avis.</p>
    <a href="{{ url('boutique') }}" class="btn-katuiscia" style="margin-top:var(--space-lg);">Découvrir la boutique</a>
  </div>
  @endif
</div>

<!-- MODAL CRÉATION D'AVIS -->
<dialog id="modal-create" class="admin-modal" style="max-width:500px;">
  <div class="admin-modal__content">
    <div class="admin-modal__header">
      <h2 style="font-family:var(--font-heading);font-size:var(--text-xl);">Donner mon avis</h2>
      <button onclick="document.getElementById('modal-create').close()" class="admin-modal__close">&times;</button>
    </div>
    <form method="POST" action="{{ route('reviews.store', 0) }}" id="create-form" class="admin-modal__body">
      @csrf
      <input type="hidden" name="product_id" id="create-product-id" value="">
      <input type="hidden" name="rating" id="create-rating" value="5">
      <div style="display:flex;gap:var(--space-md);align-items:center;margin-bottom:var(--space-lg);">
        <img src="" id="create-img" style="width:80px;height:80px;border-radius:var(--radius-md);object-fit:cover;background:var(--color-gray-k);">
        <strong id="create-name" style="font-size:var(--text-md);"></strong>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Note</label>
        <div style="display:flex;gap:6px;font-size:30px;cursor:pointer;" id="create-stars">
          @for($i=1;$i<=5;$i++)<span data-star="{{ $i }}" style="color:var(--color-border);transition:color 0.2s;" onclick="setStarsCreate({{ $i }})">★</span>@endfor
        </div>
      </div>
      <div class="admin-form-group"><label class="admin-label">Titre *</label><input type="text" name="title" id="create-title" class="admin-input" placeholder="Résumez votre expérience..." required></div>
      <div class="admin-form-group"><label class="admin-label">Votre avis</label><textarea name="body" id="create-body" class="admin-input" rows="4" placeholder="Décrivez votre expérience..." style="resize:vertical;"></textarea></div>
      <div style="background:var(--color-peach);padding:var(--space-md);border-radius:var(--radius-sm);text-align:center;margin-bottom:var(--space-md);">
        <span style="font-size:var(--text-sm);">🎁 Vous gagnerez <strong>50 points</strong> en publiant cet avis !</span>
      </div>
      <div style="display:flex;gap:var(--space-sm);justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modal-create').close()" style="padding:10px 20px;border:1px solid var(--color-border);border-radius:var(--radius-sm);background:transparent;cursor:pointer;">Annuler</button>
        <button type="submit" class="btn-primary">Publier mon avis</button>
      </div>
    </form>
  </div>
</dialog>

<!-- MODAL ÉDITION D'AVIS -->
<dialog id="modal-edit" class="admin-modal" style="max-width:500px;">
  <div class="admin-modal__content">
    <div class="admin-modal__header">
      <h2 style="font-family:var(--font-heading);font-size:var(--text-xl);">Modifier mon avis</h2>
      <button onclick="document.getElementById('modal-edit').close()" class="admin-modal__close">&times;</button>
    </div>
    <form method="POST" id="edit-form" class="admin-modal__body">
      @csrf @method('PUT')
      <div class="admin-form-group">
        <label class="admin-label">Note</label>
        <div style="display:flex;gap:6px;font-size:30px;cursor:pointer;" id="edit-stars">
          @for($i=1;$i<=5;$i++)<span data-star="{{ $i }}" style="color:var(--color-border);transition:color 0.2s;" onclick="setStarsEdit({{ $i }})">★</span>@endfor
        </div>
        <input type="hidden" name="rating" id="edit-rating" value="5">
      </div>
      <div class="admin-form-group"><label class="admin-label">Titre</label><input type="text" name="title" id="edit-title" class="admin-input" required></div>
      <div class="admin-form-group"><label class="admin-label">Votre avis</label><textarea name="body" id="edit-body" class="admin-input" rows="4" style="resize:vertical;"></textarea></div>
      <div style="display:flex;gap:var(--space-sm);justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modal-edit').close()" style="padding:10px 20px;border:1px solid var(--color-border);border-radius:var(--radius-sm);background:transparent;cursor:pointer;">Annuler</button>
        <button type="submit" class="btn-primary">Sauvegarder</button>
      </div>
    </form>
  </div>
</dialog>
@endsection

@section('scripts')
<script>
document.getElementById('mobileToggle')?.addEventListener('click',()=>document.getElementById('sidebar').classList.toggle('open'));
document.querySelectorAll('.sidebar-link[data-page]').forEach(l=>{if(l.dataset.page==='compte-avis')l.classList.add('active');});

// CRÉATION
function setStarsCreate(n){
  document.getElementById('create-rating').value = n;
  document.querySelectorAll('#create-stars span').forEach((s,i)=>{ s.style.color=i<n?'var(--color-warm)':'var(--color-border)'; });
}
function openCreateModal(productId, name, imgUrl){
  document.getElementById('create-form').action='/produits/'+productId+'/reviews';
  document.getElementById('create-product-id').value = productId;
  document.getElementById('create-img').src=imgUrl||'{{ asset('assets/images/K ICONE.png') }}';
  document.getElementById('create-name').textContent=name;
  document.getElementById('create-title').value='';
  document.getElementById('create-body').value='';
  document.querySelectorAll('#create-stars span').forEach((s,i)=>{ s.style.color=i<5?'var(--color-warm)':'var(--color-border)'; });
  document.getElementById('create-rating').value = 5;
  setStarsCreate(5);
  document.getElementById('modal-create').showModal();
}
setStarsCreate(5);

// ÉDITION
var editRating=5;
function setStarsEdit(n){ editRating=n;document.getElementById('edit-rating').value=n;document.querySelectorAll('#edit-stars span').forEach((s,i)=>{s.style.color=i<n?'var(--color-warm)':'var(--color-border)';}); }
function openEditModal(id,title,body,rating){
  document.getElementById('edit-form').action='/compte/reviews/'+id;
  document.getElementById('edit-title').value=title;
  document.getElementById('edit-body').value=body||'';
  editRating=rating;
  document.getElementById('edit-rating').value=rating;
  document.querySelectorAll('#edit-stars span').forEach((s,i)=>{s.style.color=i<rating?'var(--color-warm)':'var(--color-border)';});
  document.getElementById('modal-edit').showModal();
}
</script>
@endsection
