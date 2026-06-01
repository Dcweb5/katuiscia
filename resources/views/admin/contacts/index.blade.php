@extends('layouts.admin')
@section('title','Messages')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Messages</h1><p class="page-subtitle">Messages reçus via le formulaire de contact.</p></div>
    <div style="display:flex;align-items:center;gap:1rem;">
      <span style="font-size:var(--text-xs);color:var(--color-text-muted);">{{ $unread }} non lu(s)</span>
      <div class="search-bar" style="width:220px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" form="contacts-filter" value="" placeholder="Rechercher...">
      </div>
      <button id="bulkDeleteBtn" class="btn-primary" style="display:none;background-color:var(--color-error);border-color:var(--color-error);" onclick="bulkDelete()">🗑 Supprimer la sélection</button>
    </div>
  </div>

  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width:40px;"><input type="checkbox" id="selectAll" title="Tout sélectionner" onchange="toggleAll(this)"></th>
          <th>Nom</th>
          <th>Email</th>
          <th>Sujet</th>
          <th>Message</th>
          <th>Date</th>
          <th style="width:130px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($messages as $m)
        <tr class="msg-row {{ $m->is_read ? '' : 'msg-unread' }}" data-id="{{ $m->id }}">
          <td><input type="checkbox" class="msg-check" value="{{ $m->id }}" onchange="updateBulkBtn()"></td>
          <td>{{ $m->name }}</td>
          <td style="font-size:var(--text-sm);">{{ $m->email }}</td>
          <td style="font-size:var(--text-sm);">{{ $m->subject ?? '—' }}</td>
          <td style="font-size:var(--text-sm);max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;cursor:pointer;" class="msg-preview" onclick="toggleExpand(this)" data-full="{{ e($m->message) }}">{{ $m->message }}</td>
          <td style="font-size:var(--text-xs);color:var(--color-text-muted);white-space:nowrap;">{{ $m->created_at->format('d/m/Y H:i') }}</td>
          <td>
            <div style="display:flex;gap:4px;">
              <form method="POST" action="{{ route('admin.contacts.read', $m) }}" style="display:inline;">
                @csrf @method('PUT')
                <button type="submit" class="action-btn" title="{{ $m->is_read ? 'Marquer non lu' : 'Marquer lu' }}">
                  @if($m->is_read)
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                  @else
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  @endif
                </button>
              </form>
              <button type="button" class="action-btn" title="Répondre" onclick="openReply({{ $m->id }})">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
              </button>
              <form method="POST" action="{{ route('admin.contacts.destroy', $m) }}" style="display:inline;" onsubmit="event.preventDefault();showConfirm('Supprimer ce message ?',()=>this.submit())">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn" title="Supprimer">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun message.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $messages->links() }}

  {{-- Formulaire caché pour bulk delete --}}
  <form id="bulkDeleteForm" method="POST" action="{{ route('admin.contacts.bulk-destroy') }}" style="display:none;">
    @csrf
    <input type="hidden" name="ids" id="bulkIds" value="">
  </form>
</div>

{{-- Modale de réponse --}}
@foreach($messages as $m)
<dialog id="replyModal-{{ $m->id }}" class="admin-modal" style="max-width:600px;">
  <div class="admin-modal__content">
    <div class="admin-modal__header">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin:0;">Répondre à {{ $m->name }}</h3>
      <button onclick="closeReply({{ $m->id }})" class="admin-modal__close">&times;</button>
    </div>
    <form method="POST" action="{{ route('admin.contacts.reply', $m) }}">
      @csrf
      <div class="admin-modal__body">
        <div style="margin-bottom:1rem;">
          <strong>De :</strong> contact@katuiscia.com<br>
          <strong>À :</strong> {{ $m->email }} ({{ $m->name }})<br>
          <strong>Sujet :</strong> Re: {{ $m->subject ?? 'Votre message' }}
        </div>
        <div style="margin-bottom:0.5rem;">
          <strong>Message original :</strong>
          <div style="background:var(--color-bg);padding:0.75rem;border-radius:8px;margin-top:0.25rem;max-height:120px;overflow-y:auto;font-size:var(--text-sm);color:var(--color-text-muted);">{{ $m->message }}</div>
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Votre réponse</label>
          <textarea name="reply_body" rows="6" class="admin-input" style="width:100%;" placeholder="Écrivez votre réponse..." required></textarea>
        </div>
      </div>
      <div class="admin-form-actions" style="padding:1.5rem;border-top:1px solid var(--color-border);margin-top:0;">
        <button type="button" onclick="closeReply({{ $m->id }})" class="btn-katuiscia">Annuler</button>
        <button type="submit" class="btn-katuiscia-filled">Envoyer la réponse</button>
      </div>
    </form>
  </div>
</dialog>
    </form>
  </div>
</div>
@endforeach



<script>
function toggleExpand(el) {
  el.classList.toggle('msg-expanded');
}

function toggleAll(cb) {
  document.querySelectorAll('.msg-check').forEach(c => c.checked = cb.checked);
  updateBulkBtn();
}

function updateBulkBtn() {
  const checked = document.querySelectorAll('.msg-check:checked').length;
  document.getElementById('bulkDeleteBtn').style.display = checked > 0 ? '' : 'none';
}

function bulkDelete() {
  const ids = [...document.querySelectorAll('.msg-check:checked')].map(c => c.value);
  if (!ids.length) return;
  showConfirm('Supprimer les '+ids.length+' message(s) selectionne(s) ?', function(){
    document.getElementById('bulkIds').value = ids.join(',');
    document.getElementById('bulkDeleteForm').submit();
  });
}

function openReply(id) {
  document.getElementById('replyModal-'+id).showModal();
}
function closeReply(id) {
  document.getElementById('replyModal-'+id).close();
}
</script>
@endsection
