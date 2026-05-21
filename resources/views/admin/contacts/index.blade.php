@extends('layouts.admin')
@section('title','Messages')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);flex-wrap:wrap;gap:1rem;">
    <div><h1 class="page-title">Messages</h1><p class="page-subtitle">Messages reçus via le formulaire de contact.</p></div>
    <div style="display:flex;align-items:center;gap:1rem;">
      <span style="font-size:var(--text-xs);color:var(--color-text-muted);">{{ $unread }} non lu(s)</span>
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
                <button type="submit" class="action-btn" title="{{ $m->is_read ? 'Marquer non lu' : 'Marquer lu' }}">{{ $m->is_read ? '📩' : '📬' }}</button>
              </form>
              <button type="button" class="action-btn" title="Répondre" onclick="openReply({{ $m->id }})">✉️</button>
              <form method="POST" action="{{ route('admin.contacts.destroy', $m) }}" style="display:inline;" onsubmit="return confirm('Supprimer ce message ?')">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn" title="Supprimer">🗑</button>
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
      <div style="display:flex;gap:0.75rem;justify-content:flex-end;padding:1rem 1.5rem;border-top:1px solid var(--color-border);">
        <button type="button" onclick="closeReply({{ $m->id }})" class="action-btn" style="padding:0.5rem 1rem;">Annuler</button>
        <button type="submit" class="btn-primary">Envoyer la réponse</button>
      </div>
    </form>
  </div>
</dialog>
    </form>
  </div>
</div>
@endforeach

<style>
.msg-unread { font-weight:600; background:rgba(196,150,122,0.04); }
.msg-expanded { white-space:normal !important; overflow:visible !important; text-overflow:unset !important; max-width:none !important; }
</style>

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
  if (!ids.length || !confirm('Supprimer les '+ids.length+' message(s) sélectionné(s) ?')) return;
  document.getElementById('bulkIds').value = ids.join(',');
  document.getElementById('bulkDeleteForm').submit();
}

function openReply(id) {
  document.getElementById('replyModal-'+id).showModal();
}
function closeReply(id) {
  document.getElementById('replyModal-'+id).close();
}
</script>
@endsection
