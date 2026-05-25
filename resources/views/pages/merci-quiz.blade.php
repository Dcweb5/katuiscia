@extends('layouts.public')
@section('title', 'Merci — Votre routine personnalisée')

@section('head')
<style>
.merci-container { min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg, #faf7f2 0%, #fff 100%);padding:6rem 1.5rem;text-align:center; }
.merci-card { max-width:560px;width:100%; }
.merci-icon { font-size:4rem;margin-bottom:1rem; }
.merci-title { font-family:var(--font-heading);font-size:2rem;font-weight:400;color:#2d2117;margin-bottom:0.75rem; }
.merci-subtitle { font-size:1.05rem;color:#8b7b6e;line-height:1.6;margin-bottom:1.5rem; }
.merci-box { background:#fff;border:1px solid #ede4db;border-radius:16px;padding:1.5rem;margin-bottom:1.5rem;text-align:left; }
.merci-box strong { display:block;font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:#c4967a;margin-bottom:0.5rem; }
.merci-product { display:flex;align-items:center;gap:1rem;padding:0.75rem 0;border-bottom:1px solid #f0ebe4; }
.merci-product:last-child { border-bottom:none; }
.merci-product-img { width:48px;height:48px;border-radius:10px;background:var(--color-peach);display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0; }
.merci-product-name { font-weight:500;font-size:14px;color:#2d2117; }
.merci-product-desc { font-size:12px;color:#8b7b6e; }
.merci-cta { display:inline-flex;align-items:center;gap:0.5rem;padding:14px 32px;background:var(--color-dark);color:#fff;border:none;border-radius:99px;font-size:15px;font-weight:500;text-decoration:none;transition:all 0.2s; }
.merci-cta:hover { background:var(--color-warm);transform:translateY(-1px); }
</style>
@endsection

@section('content')
<main class="merci-container">
  <div class="merci-card reveal-k-k">
    <div class="merci-icon">🎉</div>
    <h1 class="merci-title">Merci, {{ $lead->firstname }} !</h1>
    <p class="merci-subtitle">Votre profil beauté a été analysé. Voici nos recommandations personnalisées pour votre peau.</p>

    <div class="merci-box">
      <strong>🧬 Votre profil</strong>
      @php $labels=['seche'=>'Sèche','mixte'=>'Mixte','grasse'=>'Grasse','sensible'=>'Sensible','normale'=>'Normale']; @endphp
      <p style="font-size:14px;color:#2d2117;line-height:1.8;">
        Peau : <strong>{{ $labels[$lead->quiz_responses['skin_type'] ?? ''] ?? 'Non renseigné' }}</strong><br>
        Priorité : <strong>{{ $lead->quiz_responses['concern'] ?? 'Non renseigné' }}</strong><br>
        Budget : <strong>{{ $lead->quiz_responses['budget'] ?? 'Non renseigné' }}</strong>
      </p>
    </div>

    <div class="merci-box">
      <strong>✨ Recommandé pour vous</strong>
      @php
        $recs = match($lead->quiz_responses['skin_type'] ?? '') {
          'seche' => [['Nectar Lumineux','Hydratation intense','assets/images/K ICONE.webp'],['Crème Douce','Confort quotidien','assets/images/K ICONE.webp']],
          'grasse' => [['Sérum Éclat','Matifiant purifiant','assets/images/K ICONE.webp'],['Botanique Minuit','Équilibre sébo-régulateur','assets/images/K ICONE.webp']],
          'sensible' => [['Crème Velours','Apaisant sans parfum','assets/images/K ICONE.webp'],['Émulsion Soyeuse','Protection barrière','assets/images/K ICONE.webp']],
          default => [['Nectar Lumineux','Soin universel','assets/images/K ICONE.webp'],['Sérum Éclat','Éclat immédiat','assets/images/K ICONE.webp']]
        };
      @endphp
      @foreach($recs as $r)
      <div class="merci-product">
        <div class="merci-product-img"><img src="{{ asset($r[2]) }}" style="width:100%;height:100%;border-radius:10px;object-fit:cover;"></div>
        <div><div class="merci-product-name">{{ $r[0] }}</div><div class="merci-product-desc">{{ $r[1] }}</div></div>
      </div>
      @endforeach
    </div>

    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <a href="{{ url('boutique') }}" class="merci-cta">🛍 Découvrir la boutique</a>
      <a href="{{ url('/') }}" class="merci-cta" style="background:transparent;color:var(--color-dark);border:2px solid var(--color-dark);">🏠 Retour à l'accueil</a>
    </div>
  </div>
</main>
@endsection
