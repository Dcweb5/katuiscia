@extends('layouts.account')

@section('title', 'Mes Récompenses')

@section('content')
<div class="dashboard-content">
  <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:var(--space-xl);">
    <div>
      <h1 class="page-title">Club Privilège</h1>
      <p class="page-subtitle">Vos points de fidélité et récompenses exclusives.</p>
    </div>
    <div style="background:var(--color-peach); padding:var(--space-sm) var(--space-lg); border-radius:var(--radius-full); font-size:var(--text-xs); font-weight:600; color:var(--color-dark); letter-spacing:0.1em; text-transform:uppercase;">
      {{ auth()->user()->loyalty_points >= 1500 ? '✨ Or' : '🌱 Essentiel' }}
    </div>
  </div>

  <!-- HERO POINTS — Design amélioré -->
  @php
    $points = auth()->user()->loyalty_points ?? 0;
    $nextTier = $points >= 4500 ? ['name' => 'Cercle d\'Or', 'pts' => 5000] : ($points >= 1500 ? ['name' => 'Platine', 'pts' => 4500] : ['name' => 'Or', 'pts' => 1500]);
    $progress = min(100, round($points / $nextTier['pts'] * 100));
    $remaining = max(0, $nextTier['pts'] - $points);
  @endphp

  <div class="card" style="background:linear-gradient(135deg, #2a1f1f 0%, #3D2B2B 40%, #C4967A 100%); color:white; padding:var(--space-3xl); margin-bottom:var(--space-2xl); border-radius:var(--radius-xl); position:relative; overflow:hidden;">
    <div style="position:absolute; top:-40px; right:-20px; font-size:180px; opacity:0.06; pointer-events:none;">✦</div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:var(--space-2xl); align-items:center;">
      <!-- Points display -->
      <div>
        <p style="font-size:var(--text-xs); text-transform:uppercase; letter-spacing:0.15em; opacity:0.7; margin-bottom:var(--space-xs);">Votre solde de points</p>
        <div style="display:flex; align-items:baseline; gap:var(--space-sm);">
          <span style="font-size:5rem; font-family:var(--font-display); line-height:1; font-weight:300;" id="points-counter">{{ number_format($points, 0, ',', ' ') }}</span>
          <span style="font-size:var(--text-lg); opacity:0.7; text-transform:uppercase; letter-spacing:0.1em;">pts</span>
        </div>
        <p style="font-size:var(--text-sm); opacity:0.6; margin-top:var(--space-sm);">Soit environ {{ round($points / 1000 * 10) }} € en réduction</p>
      </div>

      <!-- Progress to next tier -->
      <div>
        <p style="font-size:var(--text-xs); text-transform:uppercase; letter-spacing:0.15em; opacity:0.7; margin-bottom:var(--space-xs);">Votre progression</p>
        <div style="display:flex; justify-content:space-between; align-items:flex-end; gap:var(--space-md); margin-bottom:var(--space-xs);">
          <span style="font-size:var(--text-sm); font-weight:500; opacity:0.9;">{{ $points >= 1500 ? '✨ Statut Or' : '🌱 Essentiel' }}</span>
          <span style="font-size:var(--text-sm); font-weight:500; opacity:0.9;">⭐ {{ $nextTier['name'] }}</span>
        </div>
        <div style="width:100%; height:10px; background:rgba(255,255,255,0.15); border-radius:5px; overflow:hidden;">
          <div style="width:{{ $progress }}%; height:100%; background:linear-gradient(90deg, rgba(255,255,255,0.6), white); border-radius:5px; transition:width 1s ease;"></div>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:11px; margin-top:6px; opacity:0.6;">
          <span>{{ number_format($points, 0, ',', ' ') }}</span>
          <span>{{ number_format($nextTier['pts'], 0, ',', ' ') }}</span>
        </div>
        @if($remaining > 0)
        <p style="text-align:center; font-size:var(--text-xs); margin-top:var(--space-sm); opacity:0.8;">
          🔥 Plus que <strong>{{ number_format($remaining, 0, ',', ' ') }} pts</strong> pour le statut {{ $nextTier['name'] }} !
        </p>
        @else
        <p style="text-align:center; font-size:var(--text-xs); margin-top:var(--space-sm); opacity:0.8;">
          🎉 Vous êtes au statut maximum !
        </p>
        @endif
      </div>
    </div>
  </div>

  <!-- Comment gagner des points — cartes améliorées -->
  <h3 style="font-family:var(--font-heading); font-size:var(--text-xl); margin-bottom:var(--space-md); color:var(--color-dark);">Comment gagner des points</h3>
  <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:var(--space-md); margin-bottom:var(--space-2xl);">
    <div class="card" style="text-align:center; padding:var(--space-xl); border:1px solid var(--color-border); transition:transform 0.3s, box-shadow 0.3s;">
      <div style="width:52px; height:52px; border-radius:var(--radius-full); background:var(--color-peach); display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto var(--space-sm);">🛒</div>
      <strong style="font-size:var(--text-md); color:var(--color-dark); display:block; margin-bottom:2px;">1 € = 1 point</strong>
      <p style="font-size:11px; color:var(--color-text-muted);">Sur chaque achat</p>
    </div>
    <div class="card" style="text-align:center; padding:var(--space-xl); border:1px solid var(--color-border); transition:transform 0.3s, box-shadow 0.3s;">
      <div style="width:52px; height:52px; border-radius:var(--radius-full); background:var(--color-mint); display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto var(--space-sm);">⭐</div>
      <strong style="font-size:var(--text-md); color:var(--color-dark); display:block; margin-bottom:2px;">+50 points</strong>
      <p style="font-size:11px; color:var(--color-text-muted);">Par avis publié</p>
    </div>
    <div class="card" style="text-align:center; padding:var(--space-xl); border:1px solid var(--color-border); transition:transform 0.3s, box-shadow 0.3s;">
      <div style="width:52px; height:52px; border-radius:var(--radius-full); background:#ffe0cc; display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto var(--space-sm);">🎂</div>
      <strong style="font-size:var(--text-md); color:var(--color-dark); display:block; margin-bottom:2px;">+100 points</strong>
      <p style="font-size:11px; color:var(--color-text-muted);">Jour d'anniversaire</p>
    </div>
    <div class="card" style="text-align:center; padding:var(--space-xl); border:1px solid var(--color-border); transition:transform 0.3s, box-shadow 0.3s;">
      <div style="width:52px; height:52px; border-radius:var(--radius-full); background:#e0d8ff; display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto var(--space-sm);">👥</div>
      <strong style="font-size:var(--text-md); color:var(--color-dark); display:block; margin-bottom:2px;">+200 points</strong>
      <p style="font-size:11px; color:var(--color-text-muted);">Par parrainage</p>
    </div>
  </div>

  <!-- Récompenses disponibles -->
  <h3 style="font-family:var(--font-heading); font-size:var(--text-xl); margin-bottom:var(--space-md); color:var(--color-dark);">Récompenses à échanger</h3>
  <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:var(--space-lg); margin-bottom:var(--space-2xl);">
    @php $rewards = [
      ['title' => '10 € de réduction', 'desc' => 'Valable sur toute la boutique dès 50 € d\'achat.', 'pts' => 1000, 'icon' => '💶', 'available' => $points >= 1000],
      ['title' => 'Livraison gratuite', 'desc' => 'Livraison standard offerte en France métropolitaine.', 'pts' => 500, 'icon' => '🚚', 'available' => $points >= 500],
      ['title' => 'Sérum Voyage offert', 'desc' => 'Format découverte 15ml offert pour tout achat.', 'pts' => 2000, 'icon' => '🎁', 'available' => $points >= 2000],
      ['title' => 'Consultation beauté vidéo', 'desc' => 'Session privée de 30 min avec nos experts.', 'pts' => 3000, 'icon' => '📹', 'available' => $points >= 3000],
    ]; @endphp
    @foreach($rewards as $reward)
    <div class="card" style="border:2px solid {{ $reward['available'] ? 'var(--color-warm)' : 'var(--color-border)' }}; padding:var(--space-xl); {{ !$reward['available'] ? 'opacity:0.6;' : '' }}">
      <div style="display:flex; align-items:center; gap:var(--space-md); margin-bottom:var(--space-md);">
        <span style="font-size:32px;">{{ $reward['icon'] }}</span>
        <div>
          <strong style="font-size:var(--text-lg); color:var(--color-dark); display:block;">{{ $reward['title'] }}</strong>
          <span style="font-size:var(--text-sm); color:var(--color-warm); font-weight:600;">{{ number_format($reward['pts'], 0, ',', ' ') }} pts</span>
        </div>
      </div>
      <p style="color:var(--color-text-muted); font-size:var(--text-sm); margin-bottom:var(--space-lg); line-height:1.5;">{{ $reward['desc'] }}</p>
      @if($reward['available'])
      <button class="btn-primary" style="width:100%; padding:12px 24px; font-size:var(--text-sm); font-weight:600; letter-spacing:0.05em; cursor:pointer;">Échanger mes points</button>
      @else
      <div style="width:100%; padding:12px 0; text-align:center; font-size:var(--text-xs); color:var(--color-text-muted); text-transform:uppercase; letter-spacing:0.1em;">
        <div style="width:100%; height:4px; background:var(--color-gray-medium); border-radius:2px; margin-bottom:6px;">
          <div style="width:{{ min(100, round($points / $reward['pts'] * 100)) }}%; height:100%; background:var(--color-warm); border-radius:2px;"></div>
        </div>
        {{ round($points / $reward['pts'] * 100) }}% — Encore {{ number_format($reward['pts'] - $points, 0, ',', ' ') }} pts
      </div>
      @endif
    </div>
    @endforeach
  </div>

  <!-- Historique des points — Design amélioré -->
  <h3 style="font-family:var(--font-heading); font-size:var(--text-xl); margin-bottom:var(--space-md); color:var(--color-dark);">Historique des points</h3>
  <div class="card" style="padding:0; overflow:hidden;">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width:130px;">Date</th>
          <th>Description</th>
          <th style="width:100px; text-align:right;">Points</th>
          <th style="width:80px; text-align:center;">Type</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="color:var(--color-text-muted); font-size:var(--text-sm);">2 Mai 2026</td>
          <td>
            <div style="display:flex; align-items:center; gap:var(--space-sm);">
              <span style="font-size:16px;">🛒</span>
              <div>
                <strong style="font-size:var(--text-sm);">Commande #KAT-10512</strong>
                <br><span style="font-size:11px; color:var(--color-text-muted);">Nectar Lumineux + Sérum Éclat</span>
              </div>
            </div>
          </td>
          <td style="text-align:right;">
            <span style="color:var(--color-success); font-weight:600; font-family:var(--font-display); font-size:var(--text-md);">+250</span>
          </td>
          <td style="text-align:center;">
            <span style="display:inline-block; padding:2px 10px; border-radius:var(--radius-full); font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; background:rgba(90,143,110,0.1); color:var(--color-success);">Achat</span>
          </td>
        </tr>
        <tr>
          <td style="color:var(--color-text-muted); font-size:var(--text-sm);">25 Sep 2026</td>
          <td>
            <div style="display:flex; align-items:center; gap:var(--space-sm);">
              <span style="font-size:16px;">⭐</span>
              <div>
                <strong style="font-size:var(--text-sm);">Avis publié — Gommage Terracotta</strong>
                <br><span style="font-size:11px; color:var(--color-text-muted);">Note : 5/5 ✨</span>
              </div>
            </div>
          </td>
          <td style="text-align:right;">
            <span style="color:var(--color-success); font-weight:600; font-family:var(--font-display); font-size:var(--text-md);">+50</span>
          </td>
          <td style="text-align:center;">
            <span style="display:inline-block; padding:2px 10px; border-radius:var(--radius-full); font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; background:rgba(196,150,122,0.1); color:var(--color-warm);">Avis</span>
          </td>
        </tr>
        <tr>
          <td style="color:var(--color-text-muted); font-size:var(--text-sm);">20 Oct 2026</td>
          <td>
            <div style="display:flex; align-items:center; gap:var(--space-sm);">
              <span style="font-size:16px;">🛒</span>
              <div>
                <strong style="font-size:var(--text-sm);">Commande #KAT-10495</strong>
                <br><span style="font-size:11px; color:var(--color-text-muted);">Sérum Botanique Éclat</span>
              </div>
            </div>
          </td>
          <td style="text-align:right;">
            <span style="color:var(--color-success); font-weight:600; font-family:var(--font-display); font-size:var(--text-md);">+110</span>
          </td>
          <td style="text-align:center;">
            <span style="display:inline-block; padding:2px 10px; border-radius:var(--radius-full); font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; background:rgba(90,143,110,0.1); color:var(--color-success);">Achat</span>
          </td>
        </tr>
        <tr>
          <td style="color:var(--color-text-muted); font-size:var(--text-sm);">1 Nov 2025</td>
          <td>
            <div style="display:flex; align-items:center; gap:var(--space-sm);">
              <span style="font-size:16px;">⭐</span>
              <div>
                <strong style="font-size:var(--text-sm);">Avis publié — Rouleau Quartz Rose</strong>
                <br><span style="font-size:11px; color:var(--color-text-muted);">Note : 4/5</span>
              </div>
            </div>
          </td>
          <td style="text-align:right;">
            <span style="color:var(--color-success); font-weight:600; font-family:var(--font-display); font-size:var(--text-md);">+50</span>
          </td>
          <td style="text-align:center;">
            <span style="display:inline-block; padding:2px 10px; border-radius:var(--radius-full); font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; background:rgba(196,150,122,0.1); color:var(--color-warm);">Avis</span>
          </td>
        </tr>
        <tr style="background:rgba(199,80,80,0.03);">
          <td style="color:var(--color-text-muted); font-size:var(--text-sm);">15 Mar 2026</td>
          <td>
            <div style="display:flex; align-items:center; gap:var(--space-sm);">
              <span style="font-size:16px;">🎁</span>
              <div>
                <strong style="font-size:var(--text-sm);">Échange récompense — Livraison gratuite</strong>
              </div>
            </div>
          </td>
          <td style="text-align:right;">
            <span style="color:var(--color-error); font-weight:600; font-family:var(--font-display); font-size:var(--text-md);">-500</span>
          </td>
          <td style="text-align:center;">
            <span style="display:inline-block; padding:2px 10px; border-radius:var(--radius-full); font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; background:rgba(199,80,80,0.1); color:var(--color-error);">Échange</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('mobileToggle')?.addEventListener('click', () => document.getElementById('sidebar').classList.toggle('open'));
document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'compte-recompenses') l.classList.add('active'); });
</script>
@endsection
