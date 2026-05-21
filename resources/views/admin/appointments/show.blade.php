@extends('layouts.admin')
@section('title','Détail rendez-vous')
@section('content')
<div class="dashboard-content">
  <a href="{{ route('admin.appointments.index') }}" style="color:var(--color-text-muted);font-size:var(--text-sm);text-decoration:none;margin-bottom:1rem;display:inline-block;">&larr; Retour à la liste</a>

  @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
  @endif

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
    <div>
      <div class="card" style="margin-bottom:1.5rem;">
        <h2 style="margin:0 0 1rem;font-size:var(--text-lg);">Informations du demandeur</h2>
        <table style="width:100%;border-collapse:collapse;">
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Nom</td><td style="padding:0.5rem 0;">{{ $appointment->name }}</td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Email</td><td style="padding:0.5rem 0;">{{ $appointment->email }}</td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Téléphone</td><td style="padding:0.5rem 0;">{{ $appointment->phone ?? '—' }}</td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Type</td><td style="padding:0.5rem 0;">{{ $appointment->type === 'entreprise' ? '🏢 Entreprise' : '👤 Particulier' }}</td></tr>
          @if($appointment->company_name)
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Entreprise</td><td style="padding:0.5rem 0;">{{ $appointment->company_name }}</td></tr>
          @endif
          @if($appointment->siret)
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">SIRET</td><td style="padding:0.5rem 0;">{{ $appointment->siret }}</td></tr>
          @endif
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Source</td><td style="padding:0.5rem 0;">{{ $appointment->source === 'formation' ? '🎓 Formation' : '🤝 Grossiste' }}</td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Date souhaitée</td><td style="padding:0.5rem 0;">{{ $appointment->preferred_date ? \Carbon\Carbon::parse($appointment->preferred_date)->format('d/m/Y') : '—' }}</td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Créneau</td><td style="padding:0.5rem 0;">{{ $appointment->preferred_time ?? '—' }}</td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Demande le</td><td style="padding:0.5rem 0;">{{ $appointment->created_at->format('d/m/Y à H:i') }}</td></tr>
        </table>
        @if($appointment->message)
        <div style="margin-top:1rem;padding:0.75rem;background:var(--color-bg);border-radius:8px;font-size:var(--text-sm);">
          <strong>Message :</strong><br>{{ $appointment->message }}
        </div>
        @endif
      </div>
    </div>

    <div>
      <div class="card">
        <h2 style="margin:0 0 1rem;font-size:var(--text-lg);">
          {{ $appointment->status === 'en_attente' ? 'Gérer le rendez-vous' : 'Statut actuel' }}
        </h2>
        <div style="margin-bottom:1.5rem;">
          Statut :
          <span style="font-size:var(--text-xs);padding:4px 12px;border-radius:12px;margin-left:0.5rem;{{ $appointment->status === 'confirme' ? 'background:#e8f5e9;color:#2e7d32;' : ($appointment->status === 'refuse' ? 'background:#ffebee;color:#c62828;' : 'background:#fff8e1;color:#f57f17;') }}">
            {{ $appointment->status === 'confirme' ? '✅ Confirmé' : ($appointment->status === 'refuse' ? '❌ Refusé' : '⏳ En attente') }}
          </span>
        </div>

        @if($appointment->status === 'confirme')
          <div style="background:#e8f5e9;padding:1rem;border-radius:8px;margin-bottom:1rem;">
            <strong>Rendez-vous fixé :</strong><br>
            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }} à {{ $appointment->appointment_time }}
            @if($appointment->admin_instructions)
            <br><br><strong>Instructions envoyées :</strong><br>
            {{ $appointment->admin_instructions }}
            @endif
          </div>
        @endif

        <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}">
          @csrf @method('PUT')

          <div class="form-group">
            <label class="form-label">Statut *</label>
            <select name="status" class="form-input" style="width:100%;" onchange="toggleConfirmFields(this.value)">
              <option value="en_attente" {{ $appointment->status === 'en_attente' ? 'selected' : '' }}>⏳ En attente</option>
              <option value="confirme" {{ $appointment->status === 'confirme' ? 'selected' : '' }}>✅ Confirmer</option>
              <option value="refuse" {{ $appointment->status === 'refuse' ? 'selected' : '' }}>❌ Refuser</option>
            </select>
          </div>

          <div id="confirm-fields" style="{{ $appointment->status === 'confirme' ? '' : 'display:none;' }}">
            <div class="form-group">
              <label class="form-label">Date du rendez-vous *</label>
              <input type="date" name="appointment_date" class="form-input" style="width:100%;" value="{{ $appointment->appointment_date }}">
            </div>
            <div class="form-group">
              <label class="form-label">Horaire *</label>
              <input type="time" name="appointment_time" class="form-input" style="width:100%;" value="{{ $appointment->appointment_time }}">
            </div>
            <div class="form-group">
              <label class="form-label">Instructions (envoyées par email)</label>
              <textarea name="admin_instructions" class="form-input" style="width:100%;" rows="4" placeholder="Lieu, modalités, documents à apporter, lien visio...">{{ $appointment->admin_instructions }}</textarea>
            </div>
          </div>

          <button type="submit" class="btn-primary" style="width:100%;">Mettre à jour</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function toggleConfirmFields(status) {
  document.getElementById('confirm-fields').style.display = status === 'confirme' ? '' : 'none';
}
</script>
@endsection
