@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="dashboard-content">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-2xl);">
    <div>
      <h1 class="page-title">Gestion des Utilisateurs</h1>
      <p class="page-subtitle">Consultez, filtrez et gérez tous les comptes utilisateurs.</p>
    </div>
    <span style="font-size:var(--text-xs);color:var(--color-text-muted);">{{ $stats['total'] }} utilisateur(s)</span>
  </div>

  @if(session('success'))
  <div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);">{{ session('success') }}</div>
  @endif
  @if(session('error'))
  <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:var(--space-lg);">{{ session('error') }}</div>
  @endif

  <!-- Stats -->
  <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:var(--space-xl);">
    <div class="card stat-card">
      <span class="stat-title">Total</span>
      <span class="stat-value">{{ $stats['total'] }}</span>
    </div>
    <div class="card stat-card">
      <span class="stat-title">Admins</span>
      <span class="stat-value">{{ $stats['admins'] }}</span>
    </div>
    <div class="card stat-card">
      <span class="stat-title">Actifs</span>
      <span class="stat-value">{{ $stats['active'] }}</span>
    </div>
    <div class="card stat-card">
      <span class="stat-title">Newsletter</span>
      <span class="stat-value">{{ $stats['newsletter'] }}</span>
    </div>
  </div>

  {{-- Filters bar --}}
  <div style="display:flex;gap:0.75rem;margin-bottom:1rem;flex-wrap:wrap;align-items:center;">
    <form method="GET" style="flex:1;min-width:200px;max-width:350px;display:flex;gap:0.5rem;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher (nom, email)..." class="admin-input" style="padding:8px 14px;">
      <button type="submit" class="action-btn" style="width:auto;padding:0 14px;height:40px;">🔍</button>
      @if(request()->anyFilled(['search','role','status','country']))<a href="?" class="action-btn" style="width:auto;padding:0 12px;text-decoration:none;height:40px;display:flex;align-items:center;">✕</a>@endif
    </form>
    <select name="role" class="admin-input" style="width:auto;min-width:130px;padding:8px 14px;" onchange="this.form.submit()">
      <option value="">Tous les rôles</option>
      <option value="admin" @selected(request('role')=='admin')>Admin</option>
      <option value="client" @selected(request('role')=='client')>Client</option>
    </select>
    <select name="status" class="admin-input" style="width:auto;min-width:130px;padding:8px 14px;" onchange="this.form.submit()">
      <option value="">Tous les statuts</option>
      <option value="active" @selected(request('status')=='active')>Actif</option>
      <option value="inactive" @selected(request('status')=='inactive')>Inactif</option>
    </select>
    <select name="country" class="admin-input" style="width:auto;min-width:120px;padding:8px 14px;" onchange="this.form.submit()">
      <option value="">Tous les pays</option>
      @foreach($stats['countries'] as $code)
      <option value="{{ $code }}" @selected(request('country')==$code)>{{ $code }}</option>
      @endforeach
    </select>
  </div>

  <!-- Table -->
  <div class="card" style="padding:0;overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th style="width:80px;">Pays</th>
          <th style="width:80px;">Points</th>
          <th style="width:90px;">Rôle</th>
          <th style="width:70px;">Statut</th>
          <th style="width:120px;">Inscrit le</th>
          <th style="width:120px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:12px;">
              <div style="width:36px;height:36px;border-radius:50%;background:{{ $user->is_admin ? 'var(--color-dark)' : 'var(--color-warm)' }};color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:bold;flex-shrink:0;">
                {{ substr($user->firstname ?? substr($user->email,0,1), 0, 1) }}{{ substr($user->lastname ?? '', 0, 1) }}
              </div>
              <div>
                <strong style="font-size:var(--text-sm);">{{ $user->firstname }} {{ $user->lastname }}</strong>
              </div>
            </div>
          </td>
          <td style="font-size:var(--text-sm);color:var(--color-text-muted);">{{ $user->email }}</td>
          <td style="font-size:var(--text-sm);">{{ $user->country ?? '—' }}</td>
          <td style="font-size:var(--text-sm);">{{ number_format($user->loyalty_points ?? 0, 0, ',', ' ') }}</td>
          <td>
            @if($user->is_admin)
            <span style="display:inline-block;padding:2px 10px;border-radius:var(--radius-full);font-size:10px;font-weight:600;background:rgba(61,43,43,0.1);color:var(--color-dark);">👑 Admin</span>
            @else
            <span style="display:inline-block;padding:2px 10px;border-radius:var(--radius-full);font-size:10px;background:var(--color-gray-k);color:var(--color-text-muted);">👤 Client</span>
            @endif
          </td>
          <td>
            @if($user->is_active)
            <span class="status-badge status-badge--success">Actif</span>
            @else
            <span class="status-badge" style="background:var(--color-gray-medium);color:var(--color-text-muted);">Inactif</span>
            @endif
          </td>
          <td style="font-size:var(--text-xs);color:var(--color-text-muted);">{{ $user->created_at->format('d/m/Y') }}</td>
          <td>
            <div style="display:flex;gap:4px;flex-wrap:wrap;">
              <a href="{{ route('admin.users.edit', $user) }}" class="action-btn" title="Modifier">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>

              <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" style="display:inline;">
                @csrf @method('PUT')
                <button type="submit" class="action-btn" title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}" style="font-size:11px;">
                  {{ $user->is_active ? '🔒' : '🔓' }}
                </button>
              </form>

              @if(!$user->is_admin || auth()->id() !== $user->id)
              <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" style="display:inline;">
                @csrf @method('PUT')
                <button type="submit" class="action-btn" title="{{ $user->is_admin ? 'Retirer admin' : 'Promouvoir admin' }}">
                  {{ $user->is_admin ? '⬇' : '⬆' }}
                </button>
              </form>
              @endif

              @if(auth()->id() !== $user->id)
              <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur définitivement ?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="action-btn action-btn--danger" title="Supprimer">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--color-text-muted);">Aucun utilisateur trouvé.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top:var(--space-xl);">
    {{ $users->links() }}
  </div>
</div>
@endsection
