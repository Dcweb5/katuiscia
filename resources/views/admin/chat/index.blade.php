@extends('layouts.admin')
@section('title', 'Conversations Chatbot')
@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);">
    <div><h1 class="page-title">Conversations</h1><p class="page-subtitle">Messages échangés via le chatbot KATUISCIA.</p></div>
    <span style="font-size:var(--text-xs);color:var(--color-text-muted);">{{ $unread }} non lu(s)</span>
  </div>

  @if($conversations->isEmpty())
  <div class="card" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucune conversation.</div>
  @else
  @foreach($conversations as $sessionId => $messages)
  <div class="card" style="margin-bottom:var(--space-lg);padding:0;overflow:hidden;">
    <div style="padding:1rem 1.5rem;background:var(--color-bg);border-bottom:1px solid var(--color-border);display:flex;justify-content:space-between;align-items:center;">
      <div>
        <strong style="font-size:14px;">
          {{ $messages->first()->user?->full_name ?? 'Visiteur anonyme' }}
        </strong>
        <span style="font-size:12px;color:var(--color-text-muted);margin-left:0.75rem;">{{ $messages->first()->created_at->format('d/m/Y H:i') }}</span>
      </div>
      <span style="font-size:12px;color:var(--color-text-muted);">{{ $messages->count() }} message(s)</span>
    </div>
    <div style="padding:1rem 1.5rem;max-height:350px;overflow-y:auto;">
      @foreach($messages->sortBy('created_at') as $msg)
      <div style="margin-bottom:1rem;display:flex;gap:0.75rem;{{ $msg->is_read ? '' : 'background:rgba(196,150,122,0.04);border-radius:8px;padding:8px;' }}">
        <div style="width:32px;height:32px;border-radius:50%;background:{{ $msg->is_read ? 'var(--color-gray-medium)' : 'var(--color-warm)' }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;">👤</div>
        <div style="flex:1;">
          <div style="background:#fff;border:1px solid #ede4db;padding:10px 14px;border-radius:12px 12px 12px 4px;font-size:14px;margin-bottom:0.5rem;">{{ $msg->message }}</div>
          <div style="background:#faf7f2;padding:10px 14px;border-radius:12px 12px 4px 12px;font-size:14px;margin-left:1rem;">🤖 {{ $msg->reply }}</div>
          <div style="font-size:11px;color:var(--color-text-muted);margin-top:0.35rem;">{{ $msg->created_at->format('H:i') }}</div>
        </div>
        @if(!$msg->is_read)
        <form method="POST" action="{{ route('admin.chat.read', $msg) }}" style="flex-shrink:0;">
          @csrf @method('PUT')
          <button type="submit" class="action-btn" title="Marquer lu">✅</button>
        </form>
        @endif
      </div>
      @endforeach
    </div>
  </div>
  @endforeach
  @endif
</div>
<script>
  document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-messages') l.classList.add('active'); });
</script>
@endsection
