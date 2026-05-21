@extends('layouts.admin')

@section('title', 'Modifier — ' . $user->firstname . ' ' . $user->lastname)

@section('content')
<div class="dashboard-content">
  <a href="{{ route('admin.users.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--color-text-muted);font-size:14px;margin-bottom:var(--space-lg);text-decoration:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width:16px;height:16px;"><path d="m15 18-6-6 6-6"/></svg> Retour aux utilisateurs
  </a>

  <h1 class="page-title">Modifier — {{ $user->firstname }} {{ $user->lastname }}</h1>

  @if(session('success'))
  <div style="padding:12px 16px;background:rgba(90,143,110,0.1);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:var(--space-lg);">{{ session('success') }}</div>
  @endif
  @if($errors->any())
  <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:var(--space-lg);"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-xl);align-items:start;">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
      @csrf @method('PUT')
      <div class="card">
        <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Informations</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-md);">
          <div class="admin-form-group"><label class="admin-label">Prénom</label><input type="text" name="firstname" class="admin-input" value="{{ old('firstname', $user->firstname) }}"></div>
          <div class="admin-form-group"><label class="admin-label">Nom</label><input type="text" name="lastname" class="admin-input" value="{{ old('lastname', $user->lastname) }}"></div>
          <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-label">Email *</label><input type="email" name="email" class="admin-input" value="{{ old('email', $user->email) }}" required></div>
          <div class="admin-form-group"><label class="admin-label">Téléphone</label><input type="text" name="phone" class="admin-input" value="{{ old('phone', $user->phone) }}"></div>
          <div class="admin-form-group"><label class="admin-label">Ville</label><input type="text" name="city" class="admin-input" value="{{ old('city', $user->city) }}"></div>
          <div class="admin-form-group"><label class="admin-label">Code postal</label><input type="text" name="postal_code" class="admin-input" value="{{ old('postal_code', $user->postal_code) }}"></div>
          <div class="admin-form-group"><label class="admin-label">Pays</label><input type="text" name="country" class="admin-input" value="{{ old('country', $user->country) }}"></div>
        </div>
        <div class="admin-form-group"><label class="admin-label">Points fidélité</label><input type="number" name="loyalty_points" class="admin-input" value="{{ old('loyalty_points', $user->loyalty_points) }}" min="0"></div>
        <div style="display:flex;gap:var(--space-lg);">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="checkbox" name="is_admin" value="1" @checked(old('is_admin', $user->is_admin)) style="accent-color:var(--color-warm);"> Admin</label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active)) style="accent-color:var(--color-warm);"> Actif</label>
        </div>
        <div style="margin-top:var(--space-lg);">
          <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
      </div>
    </form>

    <div class="card">
      <h3 style="font-family:var(--font-heading);font-size:var(--text-lg);margin-bottom:var(--space-lg);">Résumé</h3>
      <div style="display:flex;flex-direction:column;gap:var(--space-md);">
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">ID</span><span>{{ $user->id }}</span></div>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">Inscrit le</span><span>{{ $user->created_at->format('d/m/Y H:i') }}</span></div>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">Dernière mise à jour</span><span>{{ $user->updated_at->format('d/m/Y H:i') }}</span></div>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">Newsletter</span><span>{{ $user->newsletter ? '✅ Oui' : 'Non' }}</span></div>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">Anniversaire</span><span>{{ $user->birthday ? $user->birthday->format('d/m') : '—' }}</span></div>
        <div style="display:flex;justify-content:space-between;padding:var(--space-sm) 0;border-bottom:1px solid var(--color-border);"><span class="admin-label">Tokens API</span><span>{{ $user->tokens_count ?? 0 }}</span></div>

        <div style="display:flex;gap:var(--space-sm);margin-top:var(--space-md);flex-wrap:wrap;">
          <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
            @csrf @method('PUT')
            <button type="submit" class="btn-katuiscia {{ $user->is_active ? '' : 'btn-katuiscia-filled' }}" style="font-size:12px;">
              {{ $user->is_active ? '🔒 Désactiver' : '🔓 Activer' }}
            </button>
          </form>
          <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
            @csrf @method('PUT')
            <button type="submit" class="btn-katuiscia" style="font-size:12px;">
              {{ $user->is_admin ? '⬇ Retirer admin' : '⬆ Promouvoir admin' }}
            </button>
          </form>
          @if(auth()->id() !== $user->id)
          <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-katuiscia" style="color:var(--color-error);border-color:var(--color-error);font-size:12px;">Supprimer</button>
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
