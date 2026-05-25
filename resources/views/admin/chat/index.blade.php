@extends('layouts.admin')
@section('title', 'Chatbot — Conversations')
@section('content')
<div class="dashboard-content" style="height:calc(100vh - 80px);overflow:hidden;display:flex;flex-direction:column;padding:0 0 0 0;">
  {{-- Header --}}
  <div style="display:flex;justify-content:space-between;align-items:center;padding:1rem 1.5rem;border-bottom:1px solid var(--color-border);background:#fff;flex-shrink:0;">
    <div><h1 class="page-title" style="margin:0;">Conversations</h1><p class="page-subtitle" style="margin:0;">Messages chatbot</p></div>
    <span style="font-size:12px;color:var(--color-text-muted);">{{ $unread }} non lu(s)</span>
  </div>

  {{-- Filters --}}
  <div style="padding:0.75rem 1.5rem;border-bottom:1px solid var(--color-border);background:#fff;flex-shrink:0;">
    <div class="filter-pills">
      <div class="search-bar" style="margin-right:auto;max-width:280px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" form="chat-filter" value="{{ request('search') }}" placeholder="Rechercher...">
      </div>
      <a href="?" class="filter-pill {{ !request('filter') ? 'active' : '' }}">Tous</a>
      <a href="?filter=unread" class="filter-pill {{ request('filter') === 'unread' ? 'active' : '' }}">Non lus</a>
    </div>
  </div>

  {{-- Main: sidebar + messages --}}
  <div style="flex:1;display:flex;overflow:hidden;min-height:0;">
    {{-- SIDEBAR: Conversation list --}}
    <div style="width:350px;flex-shrink:0;border-right:1px solid var(--color-border);overflow-y:auto;background:#faf7f2;">
      @forelse($conversations as $conv)
      <a href="?session={{ $conv['session_id'] }}{{ request('filter') ? '&filter='.request('filter') : '' }}{{ request('search') ? '&search='.request('search') : '' }}"
         style="display:block;padding:1rem 1.25rem;border-bottom:1px solid #ede4db;text-decoration:none;color:inherit;transition:background 0.15s;{{ $activeSession === $conv['session_id'] ? 'background:#fff;' : '' }}"
         onmouseover="this.style.background='#fff'" onmouseout="this.style.background='{{ $activeSession === $conv['session_id'] ? '#fff' : '' }}'">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.35rem;">
          <div style="display:flex;align-items:center;gap:0.5rem;">
            <div style="width:40px;height:40px;border-radius:50%;background:{{ $conv['unread'] > 0 ? 'var(--color-warm)' : '#d1d5db' }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:600;flex-shrink:0;">
              {{ strtoupper(substr($conv['name'], 0, 2)) }}
            </div>
            <div style="min-width:0;">
              <strong style="font-size:14px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $conv['name'] }}</strong>
              <span style="font-size:11px;color:var(--color-text-muted);">{{ $conv['email'] ?? 'Anonyme' }}</span>
            </div>
          </div>
          <div style="text-align:right;flex-shrink:0;">
            <span style="font-size:10px;color:var(--color-text-muted);white-space:nowrap;">{{ $conv['last_date']->format('d/m') }}</span>
            @if($conv['unread'] > 0)
            <span style="display:inline-block;background:var(--color-warm);color:#fff;font-size:10px;min-width:18px;height:18px;border-radius:9px;display:flex;align-items:center;justify-content:center;margin-top:3px;">{{ $conv['unread'] }}</span>
            @endif
          </div>
        </div>
        <p style="font-size:12px;color:var(--color-text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:0;">{{ Str::limit($conv['last_message'], 60) }}</p>
      </a>
      @empty
      <div style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucune conversation.</div>
      @endforelse
    </div>

    {{-- MAIN PANEL: Conversation thread --}}
    <div style="flex:1;overflow-y:auto;background:#fff;display:flex;flex-direction:column;">
      @if($activeConversation)
      {{-- Active conversation header --}}
      <div style="padding:0.75rem 1.5rem;border-bottom:1px solid var(--color-border);display:flex;align-items:center;gap:0.75rem;background:#fff;flex-shrink:0;">
        <div style="width:36px;height:36px;border-radius:50%;background:var(--color-warm);color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:600;flex-shrink:0;">
          {{ strtoupper(substr($activeConversation['name'], 0, 2)) }}
        </div>
        <div>
          <strong style="font-size:14px;">{{ $activeConversation['name'] }}</strong>
          <span style="font-size:11px;color:var(--color-text-muted);margin-left:0.5rem;">{{ $activeConversation['total'] }} message(s)</span>
        </div>
      </div>

      {{-- Messages --}}
      <div style="flex:1;overflow-y:auto;padding:1.5rem;">
        @foreach($activeConversation['messages'] as $msg)
        <div style="margin-bottom:1.25rem;">
          {{-- User message --}}
          <div style="display:flex;gap:0.75rem;margin-bottom:0.5rem;">
            <div style="width:32px;height:32px;border-radius:50%;background:var(--color-warm);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;flex-shrink:0;">{{ strtoupper(substr($msg->user?->firstname ?? 'V', 0, 1)) }}</div>
            <div>
              <div style="background:#f0ebe4;padding:10px 14px;border-radius:12px 12px 12px 4px;font-size:14px;max-width:500px;line-height:1.5;">{{ $msg->message }}</div>
              <span style="font-size:10px;color:var(--color-text-muted);margin-top:3px;display:block;">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
            </div>
          </div>

          {{-- Bot reply --}}
          <div style="display:flex;gap:0.75rem;margin-left:1rem;">
            <div style="width:32px;height:32px;border-radius:50%;background:#2d2117;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;flex-shrink:0;">🤖</div>
            <div>
              <div style="background:#faf7f2;padding:10px 14px;border-radius:12px 12px 4px 12px;font-size:14px;max-width:500px;line-height:1.5;">{{ $msg->reply }}</div>
              <span style="font-size:10px;color:var(--color-text-muted);margin-top:3px;display:block;">KATUISCIA Bot · {{ $msg->created_at->format('H:i') }}</span>
            </div>
            @if(!$msg->is_read)
            <form method="POST" action="{{ route('admin.chat.read', $msg) }}" style="flex-shrink:0;align-self:center;">
              @csrf @method('PUT')
              <button type="submit" class="action-btn" title="Marquer lu">✅</button>
            </form>
            @endif
          </div>
        </div>
        @endforeach
      </div>
      @else
      <div style="flex:1;display:flex;align-items:center;justify-content:center;flex-direction:column;color:var(--color-text-muted);padding:2rem;">
        <div style="font-size:3rem;margin-bottom:1rem;">💬</div>
        <p style="font-size:1.1rem;color:var(--color-dark);margin-bottom:0.25rem;">Sélectionnez une conversation</p>
        <p style="font-size:13px;">Cliquez sur une discussion dans la colonne de gauche.</p>
      </div>
      @endif
    </div>
  </div>
</div>

<form id="chat-filter" method="GET" style="display:none;">
  @if(request('session'))<input type="hidden" name="session" value="{{ request('session') }}">@endif
  @if(request('filter'))<input type="hidden" name="filter" value="{{ request('filter') }}">@endif
</form>

<script>
  document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'admin-messages') l.classList.add('active'); });
</script>
@endsection
