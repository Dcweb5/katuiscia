@extends('layouts.admin')
@section('title', 'Nouvelle Campagne — Newsletter')
@section('content')
<div class="dashboard-content" style="font-family: Inter, system-ui, sans-serif;">
  
  {{-- Header row --}}
  <div style="margin-bottom:var(--space-2xl);">
    <a href="{{ route('admin.newsletter.index') }}" style="display:inline-flex; align-items:center; gap:6px; text-decoration:none; color:var(--color-text-muted); font-size:14px; margin-bottom:1rem;">
      🗂️ Retour à la liste
    </a>
    <h1 class="page-title" style="font-family: var(--font-display), serif; font-size: 2rem; font-weight: 300; color: var(--color-dark); margin: 0;">Rédiger une newsletter</h1>
    <p class="page-subtitle" style="color: var(--color-text-muted); font-size: 14px; margin-top: 4px;">Écrivez le message de votre campagne et envoyez-le à vos abonnés.</p>
  </div>

  <div style="display:grid; grid-template-cols: 1fr; lg:grid-template-cols: 3fr 1.5fr; gap: 2rem; align-items: flex-start;">
    
    {{-- Campaign form card --}}
    <div class="card" style="padding:2rem; background:#fff; border: 1px solid #ede4db; border-radius: 12px;">
      <form method="POST" action="{{ route('admin.newsletter.campaign.send') }}">
        @csrf
        
        <div class="admin-form-group" style="margin-bottom:1.5rem; display:flex; flex-direction:column; gap:6px;">
          <label class="admin-label" style="font-size:13px; font-weight:600; color:var(--color-dark);">Objet du message *</label>
          <input type="text" name="subject" class="admin-input" placeholder="Découvrez notre nouvelle collection d'été 🌿" required style="padding:10px 14px; border: 1px solid #d1d5db; border-radius:8px; outline:none; font-size:14px;">
        </div>

        <div class="admin-form-group" style="margin-bottom:1.5rem; display:flex; flex-direction:column; gap:6px;">
          <label class="admin-label" style="font-size:13px; font-weight:600; color:var(--color-dark);">Contenu de l'e-mail (Code HTML ou texte) *</label>
          <textarea name="content" rows="15" class="admin-input" placeholder="<p>Bonjour,</p><p>Nous sommes ravis de vous présenter nos nouveaux soins...</p>" required style="padding:14px; border: 1px solid #d1d5db; border-radius:8px; outline:none; font-size:14px; font-family:var(--font-mono), monospace; line-height:1.5;"></textarea>
          <small style="color:var(--color-text-muted); font-size:11px; margin-top:4px; line-height:1.4;">
            💡 Vous pouvez utiliser des balises HTML pour mettre en forme votre message (ex: <code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;a href="..."&gt;</code>, <code>&lt;img src="..."&gt;</code>).
          </small>
        </div>

        <div style="background:rgba(196,150,122,0.08); border: 1px solid var(--color-warm); padding: 16px; border-radius: 8px; margin-bottom: 2rem;">
          <h4 style="margin: 0 0 4px 0; color:var(--color-dark); font-size:13px; font-weight:600;">⚠️ Validation de l'envoi</h4>
          <p style="margin:0; font-size:12px; color:var(--color-text-light); line-height:1.5;">
            Cette action est irréversible. L'e-mail sera envoyé immédiatement à vos <strong>{{ $activeCount }}</strong> abonnés actifs.
          </p>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px;">
          <a href="{{ route('admin.newsletter.index') }}" class="btn-primary" style="text-decoration:none; background:transparent; border: 1px solid #d1d5db; color:var(--color-dark); padding:10px 20px; border-radius:8px; font-weight:500; font-size:14px; display:inline-flex; align-items:center;">
            Annuler
          </a>
          <button type="submit" class="btn-primary" style="background:var(--color-warm); border:none; color:#fff; padding:10px 24px; border-radius:8px; font-weight:500; font-size:14px; cursor:pointer;" onclick="return confirm('Êtes-vous sûr(e) de vouloir envoyer cette newsletter immédiatement à vos {{ $activeCount }} abonnés ?');">
            🚀 Envoyer la newsletter
          </button>
        </div>

      </form>
    </div>

    {{-- Tips card --}}
    <div class="card" style="padding: 1.5rem; background:#fff; border: 1px solid #ede4db; border-radius: 12px; display:flex; flex-direction:column; gap:1rem;">
      <h3 style="margin-top:0; font-size:14px; font-weight:600; color:var(--color-dark); border-bottom: 1px solid #ede4db; padding-bottom: 8px; margin-bottom:0;">Conseils de rédaction</h3>
      
      <div style="display:flex; flex-direction:column; gap:10px; font-size:13px; color:var(--color-text-light); line-height:1.5;">
        <p><strong>1. Objet percutant :</strong> Choisissez un objet court, engageant et utilisez des emojis pour attirer l'attention dans la boîte de réception.</p>
        <p><strong>2. Liens vers le site :</strong> Si vous présentez un produit, n'oubliez pas d'inclure un bouton ou un lien pointant vers la boutique KATUISCIA pour inciter à l'achat.</p>
        <p><strong>3. Structure HTML simple :</strong> Utilisez des paragraphes simples. Pour insérer une image, importez-la d'abord sur votre hébergement et utilisez son URL absolue.</p>
        <p><strong>4. Mentions Légales :</strong> Un lien de désabonnement personnalisé est automatiquement ajouté en pied de page de chaque email envoyé, conformément au RGPD.</p>
      </div>
    </div>

  </div>

</div>
@endsection
