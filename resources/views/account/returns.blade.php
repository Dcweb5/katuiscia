@extends('layouts.account')
@section('title', 'Retours & Échanges')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Retours & Échanges</h1><p class="page-subtitle">Gérez vos demandes de retour. Vous avez 30 jours après livraison.</p></div>
    <button class="btn-primary" onclick="document.getElementById('modal-retour').showModal()">+ Nouvelle demande</button>
  </div>

  @if($returns->isEmpty() && $orders->isEmpty())
  <div style="text-align:center;padding:4rem;color:var(--color-text-muted);">
    <p style="font-size:48px;margin-bottom:1rem;">📦</p>
    <p>Aucune commande livrée éligible à un retour.</p>
    <a href="{{ url('boutique') }}" class="btn-katuiscia" style="margin-top:1rem;">Découvrir la boutique</a>
  </div>
  @else
  @foreach($returns as $r)
  <div class="card" style="margin-bottom:var(--space-lg);{{ $r->status === 'pending' ? 'border-left:4px solid var(--color-warm);' : ($r->status === 'rejected' ? 'border-left:4px solid var(--color-error);' : 'opacity:'.($r->status==='completed'||$r->status==='cancelled'?'0.6':'1').';') }}">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-md);flex-wrap:wrap;gap:8px;">
      <div>
        <strong style="font-size:var(--text-lg);">Retour #{{ $r->request_number }}</strong>
        <span style="color:var(--color-text-muted);font-size:var(--text-sm);margin-left:8px;">{{ $r->created_at->format('d/m/Y') }}</span>
      </div>
      @php $colors=['pending'=>'#f59e0b','approved'=>'#3b82f6','received'=>'#8b5cf6','completed'=>'#2e7d32','rejected'=>'#c62828','cancelled'=>'#999']; $labels=['pending'=>'En attente','approved'=>'Approuvé','received'=>'Reçu','completed'=>'Remboursé','rejected'=>'Refusé','cancelled'=>'Annulé']; @endphp
      <span style="font-size:11px;font-weight:600;color:#fff;background:{{ $colors[$r->status] ?? '#ccc' }};padding:4px 12px;border-radius:var(--radius-full);">{{ $labels[$r->status] ?? $r->status }}</span>
    </div>

    <div style="display:flex;gap:1rem;align-items:center;padding:var(--space-md);background:var(--color-bg);border-radius:var(--radius-md);margin-bottom:var(--space-md);">
      @if($r->item)
      <strong>{{ $r->item->product_name }}</strong>
      <span style="font-size:var(--text-sm);color:var(--color-text-muted);">×{{ $r->item->quantity }}</span>
      @else
      <strong>Commande #{{ $r->order->order_number }}</strong>
      @endif
    </div>

    <div style="background:rgba(196,150,122,0.06);border-radius:var(--radius-sm);padding:var(--space-md);margin-bottom:var(--space-md);">
      <p style="font-size:var(--text-sm);margin-bottom:4px;"><strong>Motif :</strong> {{ $r->reason }}</p>
      <p style="font-size:var(--text-sm);color:var(--color-text-muted);"><strong>Type :</strong> {{ $r->type === 'return' ? '↩ Remboursement' : '🔄 Échange' }}</p>
      @if($r->admin_notes)<p style="font-size:var(--text-sm);color:var(--color-text-muted);margin-top:4px;"><strong>Réponse :</strong> {{ $r->admin_notes }}</p>@endif
    </div>

    @if($r->images->isNotEmpty())
    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:var(--space-md);">
      @foreach($r->images as $img)
      <a href="{{ asset('storage/'.$img->path) }}" target="_blank"><img src="{{ asset('storage/'.$img->path) }}" style="width:70px;height:70px;border-radius:8px;object-fit:cover;border:1px solid var(--color-border);"></a>
      @endforeach
    </div>
    @endif

    {{-- Progress bar --}}
    @php $steps = ['Demandé','Approuvé','Reçu','Remboursé']; $statuses = ['pending','approved','received','completed']; $current = array_search($r->status, $statuses); $current = $current !== false ? $current : 0; @endphp
    <div style="display:flex;align-items:center;gap:0;margin-bottom:var(--space-sm);">
      @foreach($steps as $i => $step)
      <div style="flex:1;text-align:center;"><div style="width:12px;height:12px;border-radius:50%;background:{{ $i <= $current ? 'var(--color-warm)' : 'var(--color-border)' }};margin:0 auto 4px;"></div><span style="font-size:10px;color:{{ $i <= $current ? 'var(--color-warm)' : 'var(--color-text-muted)' }};">{{ $step }}</span></div>
      @if($i < 3)<div style="flex:0.5;height:2px;background:{{ $i < $current ? 'var(--color-warm)' : 'var(--color-border)' }};"></div>@endif
      @endforeach
    </div>

    @if(in_array($r->status, ['pending','approved']))
    <form method="POST" action="{{ route('returns.cancel', $r) }}" onsubmit="return confirm('Annuler cette demande ?')">
      @csrf @method('PUT')
      <button type="submit" style="padding:8px 16px;font-size:12px;border:1px solid var(--color-error);border-radius:var(--radius-sm);background:transparent;cursor:pointer;color:var(--color-error);">Annuler la demande</button>
    </form>
    @endif
  </div>
  @endforeach
  @endif
</div>

{{-- MODAL --}}
<dialog id="modal-retour" class="admin-modal" style="max-width:550px;">
  <div class="admin-modal__content">
    <div class="admin-modal__header">
      <h2 style="font-family:var(--font-heading);font-size:var(--text-xl);">Demande de Retour / Échange</h2>
      <button onclick="document.getElementById('modal-retour').close()" class="admin-modal__close">&times;</button>
    </div>
    <form method="POST" action="{{ route('returns.store') }}" class="admin-modal__body" enctype="multipart/form-data">
      @csrf
      <div class="admin-form-group">
        <label class="admin-label">Commande *</label>
        <select name="order_id" class="admin-input" required onchange="updateOrderItems(this.value)">
          <option value="">Sélectionnez...</option>
          @foreach($orders as $o)
          <option value="{{ $o->id }}">{{ $o->order_number }} — {{ $o->created_at->format('d/m/Y') }} ({{ number_format($o->total,0,',',' ') }} €)</option>
          @endforeach
        </select>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Produit concerné</label>
        <select name="order_item_id" class="admin-input" id="order-items-select">
          <option value="">Toute la commande</option>
        </select>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Type *</label>
        <select name="type" class="admin-input" required>
          <option value="return">↩ Remboursement</option>
          <option value="exchange">🔄 Échange</option>
        </select>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Motif *</label>
        <textarea name="reason" class="admin-input" rows="3" required placeholder="Décrivez le motif de votre retour..."></textarea>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Photos (max 5)</label>
        <div id="drop-zone" style="border:2px dashed #d1d5db;border-radius:10px;padding:1.5rem;text-align:center;cursor:pointer;transition:border-color 0.2s;" onclick="document.getElementById('images-input').click()">
          <div style="font-size:2rem;margin-bottom:0.5rem;">📷</div>
          <p style="font-size:13px;color:var(--color-text-muted);">Glissez-déposez des photos ou <span style="color:var(--color-warm);font-weight:500;">parcourez</span></p>
          <p style="font-size:11px;color:var(--color-text-muted);">Jusqu'à 5 images (JPG, PNG, WebP)</p>
        </div>
        <input type="file" name="images[]" id="images-input" accept="image/*" multiple style="display:none;" onchange="handleFiles(this.files)">
        <div id="preview-list" style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-top:0.75rem;"></div>
      </div>
      <button type="submit" class="btn-primary" style="width:100%;">Envoyer la demande</button>
    </form>
  </div>
</dialog>
<script>
var orderItems = @json($orders->pluck('items','id'));
function updateOrderItems(orderId) {
  var sel = document.getElementById('order-items-select');
  sel.innerHTML = '<option value="">Toute la commande</option>';
  if (orderItems[orderId]) {
    orderItems[orderId].forEach(function(item) {
      sel.innerHTML += '<option value="'+item.id+'">'+item.product_name+' ×'+item.quantity+' ('+item.price+' €)</option>';
    });
  }
}

var returnFiles = [];
var dt = document.getElementById('drop-zone');
['dragenter','dragover','dragleave','drop'].forEach(function(e){ dt.addEventListener(e, function(e){ e.preventDefault(); e.stopPropagation(); }); });
['dragenter','dragover'].forEach(function(e){ dt.addEventListener(e, function(){ dt.style.borderColor='var(--color-warm)'; }); });
['dragleave','drop'].forEach(function(e){ dt.addEventListener(e, function(){ dt.style.borderColor='#d1d5db'; }); });
dt.addEventListener('drop', function(e){ handleFiles(e.dataTransfer.files); });

function handleFiles(files) {
  var remaining = 5 - returnFiles.length;
  Array.from(files).slice(0, remaining).forEach(function(f){
    if (returnFiles.length >= 5) return;
    returnFiles.push(f);
    var reader = new FileReader();
    reader.onload = function(ev){
      var img = document.createElement('img');
      img.src = ev.target.result;
      img.style.cssText = 'width:70px;height:70px;border-radius:8px;object-fit:cover;border:2px solid var(--color-border);';
      img.title = f.name;
      var container = document.createElement('div');
      container.style.position = 'relative';
      var btn = document.createElement('button');
      btn.textContent = '×';
      btn.style.cssText = 'position:absolute;top:-6px;right:-6px;background:var(--color-error);color:#fff;border:none;width:20px;height:20px;border-radius:50%;font-size:12px;cursor:pointer;';
      btn.onclick = function(ev){ ev.stopPropagation(); returnFiles = returnFiles.filter(function(x){ return x !== f; }); container.remove(); updateFileInput(); };
      container.appendChild(img); container.appendChild(btn);
      document.getElementById('preview-list').appendChild(container);
      updateFileInput();
    };
    reader.readAsDataURL(f);
  });
}

function updateFileInput(){
  var input = document.getElementById('images-input');
  var dt = new DataTransfer();
  returnFiles.forEach(function(f){ dt.items.add(f); });
  input.files = dt.files;
}

document.getElementById('mobileToggle')?.addEventListener('click',()=>document.getElementById('sidebar').classList.toggle('open'));
document.querySelectorAll('.sidebar-link[data-page]').forEach(l=>{if(l.dataset.page==='compte-retours')l.classList.add('active');});
</script>
@endsection
