@extends('layouts.account')

@section('title', 'Mes Récompenses')

@section('content')
<div class="dashboard-content">
  @if(!$isEnabled)
    <!-- Premium Suspension Message -->
    <div style="background: var(--color-bg-light); text-align: center; padding: var(--space-3xl) var(--space-xl); border: 1px solid var(--color-border); border-radius: var(--radius-xl); max-width: 600px; margin: var(--space-3xl) auto; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
      <div style="font-size: 3.5rem; margin-bottom: var(--space-md); filter: drop-shadow(0 2px 5px rgba(196,150,122,0.3));">✨</div>
      <h2 style="font-family: var(--font-heading); color: var(--color-dark); margin-bottom: var(--space-md); font-size: var(--text-2xl); font-weight: 400; letter-spacing: 0.02em;">Club Privilège KATUISCIA</h2>
      <p style="color: var(--color-text-muted); line-height: 1.6; font-size: var(--text-md); margin-bottom: var(--space-xl);">
        Notre programme de fidélité fait peau neuve pour vous offrir une expérience encore plus exclusive et raffinée. Vos points acquis restent précieusement conservés et seront à nouveau disponibles très prochainement.
      </p>
      <div style="width: 50px; height: 1px; background: var(--color-warm); margin: 0 auto var(--space-md);"></div>
      <p style="font-size: var(--text-sm); font-style: italic; color: var(--color-warm);">Merci pour votre fidélité et votre compréhension.</p>
    </div>
  @else
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
      
      $ptsPerEuro = \App\Models\Setting::get('loyalty_points_per_euro', 1);
      $ptsPerReview = \App\Models\Setting::get('loyalty_points_per_review', 50);
      $ptsSignup = \App\Models\Setting::get('loyalty_points_for_signup', 100);
    @endphp

    <div class="card" style="background:linear-gradient(135deg, #2a1f1f 0%, #3D2B2B 40%, #C4967A 100%); color:white; padding:var(--space-3xl); margin-bottom:var(--space-2xl); border-radius:var(--radius-xl); position:relative; overflow:hidden;">
      <div style="position:absolute; top:-40px; right:-20px; font-size:180px; opacity:0.06; pointer-events:none;">✦</div>
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:var(--space-2xl); align-items:center;">
        <!-- Points display -->
        <div>
          <p style="font-size:var(--text-xs); text-transform:uppercase; letter-spacing:0.15em; opacity:0.7; margin-bottom:var(--space-xs);">Votre solde de points</p>
          <div style="display:flex; align-items:baseline; gap:var(--space-sm);">
            <span style="font-size:5rem; font-family:var(--font-display); line-height:1; font-weight:300;" id="points-counter">{{ number_format($points, 0, ',', ' ') }}</span>
            <span style="font-size:var(--text-lg); opacity:0.7; text-transform:uppercase; letter-spacing:0.1em;">pts</span>
          </div>
          <p style="font-size:var(--text-sm); opacity:0.6; margin-top:var(--space-sm);">Faites-les valoir sur vos prochains achats</p>
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
      @if($ptsPerEuro > 0)
      <div class="card" style="text-align:center; padding:var(--space-xl); border:1px solid var(--color-border); transition:transform 0.3s, box-shadow 0.3s;">
        <div style="width:52px; height:52px; border-radius:var(--radius-full); background:var(--color-peach); display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto var(--space-sm);">🛒</div>
        <strong style="font-size:var(--text-md); color:var(--color-dark); display:block; margin-bottom:2px;">1 € = {{ $ptsPerEuro }} {{ $ptsPerEuro > 1 ? 'points' : 'point' }}</strong>
        <p style="font-size:11px; color:var(--color-text-muted);">Sur chaque achat</p>
      </div>
      @endif
      @if($ptsPerReview > 0)
      <div class="card" style="text-align:center; padding:var(--space-xl); border:1px solid var(--color-border); transition:transform 0.3s, box-shadow 0.3s;">
        <div style="width:52px; height:52px; border-radius:var(--radius-full); background:var(--color-mint); display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto var(--space-sm);">⭐</div>
        <strong style="font-size:var(--text-md); color:var(--color-dark); display:block; margin-bottom:2px;">+{{ $ptsPerReview }} points</strong>
        <p style="font-size:11px; color:var(--color-text-muted);">Par avis produit approuvé</p>
      </div>
      @endif
      @if($ptsSignup > 0)
      <div class="card" style="text-align:center; padding:var(--space-xl); border:1px solid var(--color-border); transition:transform 0.3s, box-shadow 0.3s;">
        <div style="width:52px; height:52px; border-radius:var(--radius-full); background:#ffe0cc; display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto var(--space-sm);">🎉</div>
        <strong style="font-size:var(--text-md); color:var(--color-dark); display:block; margin-bottom:2px;">+{{ $ptsSignup }} points</strong>
        <p style="font-size:11px; color:var(--color-text-muted);">À l'inscription</p>
      </div>
      @endif
      <div class="card" style="text-align:center; padding:var(--space-xl); border:1px solid var(--color-border); transition:transform 0.3s, box-shadow 0.3s;">
        <div style="width:52px; height:52px; border-radius:var(--radius-full); background:#e0d8ff; display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto var(--space-sm);">🎂</div>
        <strong style="font-size:var(--text-md); color:var(--color-dark); display:block; margin-bottom:2px;">Cadeau Spécial</strong>
        <p style="font-size:11px; color:var(--color-text-muted);">Jour de votre anniversaire</p>
      </div>
    </div>

    <!-- Récompenses disponibles -->
    <h3 style="font-family:var(--font-heading); font-size:var(--text-xl); margin-bottom:var(--space-md); color:var(--color-dark);">Récompenses à échanger</h3>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:var(--space-lg); margin-bottom:var(--space-2xl);">
      @forelse($rewardsOptions as $reward)
        @php
          $rewardPoints = (int) ($reward['points'] ?? 0);
          $isAvailable = $points >= $rewardPoints;
          $rewardIcon = match($reward['type'] ?? '') {
              'free_shipping' => '🚚',
              'fixed' => '💶',
              'percent' => '🏷️',
              default => '🎁'
          };
          $rewardName = $reward['name'] ?? 'Récompense';
          $rewardDesc = match($reward['type'] ?? '') {
              'free_shipping' => 'Frais de livraison standard offerts sur votre prochaine commande.',
              'fixed' => 'Réduction immédiate de ' . ($reward['value'] ?? 0) . ' € sur votre prochaine commande.',
              'percent' => 'Réduction immédiate de ' . ($reward['value'] ?? 0) . '% sur votre commande.',
              default => 'Récompense exclusive.'
          };
        @endphp
        <div class="card" style="border:2px solid {{ $isAvailable ? 'var(--color-warm)' : 'var(--color-border)' }}; padding:var(--space-xl); {{ !$isAvailable ? 'opacity:0.65;' : '' }} display:flex; flex-direction:column; justify-content:space-between; transition:transform 0.2s, box-shadow 0.2s;">
          <div>
            <div style="display:flex; align-items:center; gap:var(--space-md); margin-bottom:var(--space-md);">
              <span style="font-size:32px;">{{ $rewardIcon }}</span>
              <div>
                <strong style="font-size:var(--text-lg); color:var(--color-dark); display:block;">{{ $rewardName }}</strong>
                <span style="font-size:var(--text-sm); color:var(--color-warm); font-weight:600;">{{ number_format($rewardPoints, 0, ',', ' ') }} pts</span>
              </div>
            </div>
            <p style="color:var(--color-text-muted); font-size:var(--text-sm); margin-bottom:var(--space-lg); line-height:1.5;">{{ $rewardDesc }}</p>
          </div>
          
          <div>
            @if($isAvailable)
              <button onclick="exchangePoints({{ $reward['id'] }}, '{{ addslashes($rewardName) }}', {{ $rewardPoints }})" class="btn-primary" style="width:100%; padding:12px 24px; font-size:var(--text-sm); font-weight:600; letter-spacing:0.05em; cursor:pointer;">Échanger mes points</button>
            @else
              <div style="width:100%; padding:12px 0; text-align:center; font-size:var(--text-xs); color:var(--color-text-muted); text-transform:uppercase; letter-spacing:0.1em;">
                <div style="width:100%; height:4px; background:var(--color-gray-medium); border-radius:2px; margin-bottom:6px; overflow:hidden;">
                  <div style="width:{{ $points > 0 ? min(100, round($points / $rewardPoints * 100)) : 0 }}%; height:100%; background:var(--color-warm); border-radius:2px;"></div>
                </div>
                {{ $points > 0 ? min(100, round($points / $rewardPoints * 100)) : 0 }}% — Encore {{ number_format($rewardPoints - $points, 0, ',', ' ') }} pts
              </div>
            @endif
          </div>
        </div>
      @empty
        <div style="grid-column: 1 / -1;" class="card">
          <p style="text-align:center; color:var(--color-text-muted);">Aucune règle d'échange disponible pour le moment.</p>
        </div>
      @endforelse
    </div>

    <!-- Mes bons de réduction -->
    <h3 style="font-family:var(--font-heading); font-size:var(--text-xl); margin-bottom:var(--space-md); color:var(--color-dark);">Mes bons de réduction</h3>
    @if($coupons->isEmpty())
      <div class="card" style="text-align:center; padding:var(--space-2xl); border:1px dashed var(--color-border); color:var(--color-text-muted); margin-bottom:var(--space-2xl);">
        <p style="font-size:var(--text-sm);">Vous n'avez pas encore de bons de réduction obtenus par échange de points.</p>
      </div>
    @else
      <div class="card" style="padding:0; overflow-x:auto; margin-bottom:var(--space-2xl);">
        <table class="admin-table" style="min-width: 600px;">
          <thead>
            <tr>
              <th>Date d'obtention</th>
              <th>Code promo</th>
              <th>Description</th>
              <th>Validité / Utilisation</th>
            </tr>
          </thead>
          <tbody>
            @foreach($coupons as $coupon)
              <tr>
                <td style="color:var(--color-text-muted); font-size:var(--text-sm);">{{ $coupon->created_at->format('d M Y') }}</td>
                <td>
                  <div style="display:flex; align-items:center; gap:var(--space-sm);">
                    <code style="background:var(--color-bg-light); border:1px solid var(--color-border); padding:4px 8px; border-radius:var(--radius-sm); font-family:monospace; font-weight:600; color:var(--color-dark);">{{ $coupon->code }}</code>
                    <button onclick="copyToClipboard('{{ $coupon->code }}')" class="btn-secondary" style="padding:4px 8px; font-size:10px; cursor:pointer;" title="Copier le code">📋 Copier</button>
                  </div>
                </td>
                <td>
                  <strong style="font-size:var(--text-sm); color:var(--color-dark);">{{ $coupon->description }}</strong>
                </td>
                <td>
                  @if($coupon->used_count > 0)
                    <span style="display:inline-block; padding:2px 10px; border-radius:var(--radius-full); font-size:10px; font-weight:600; text-transform:uppercase; background:rgba(90,143,110,0.1); color:var(--color-success);">
                      Utilisé le {{ $coupon->last_used_at ? $coupon->last_used_at->format('d/m/Y') : '' }}
                    </span>
                  @elseif($coupon->expires_at && now()->gt($coupon->expires_at))
                    <span style="display:inline-block; padding:2px 10px; border-radius:var(--radius-full); font-size:10px; font-weight:600; text-transform:uppercase; background:rgba(199,80,80,0.1); color:var(--color-error);">
                      Expiré
                    </span>
                  @else
                    <span style="display:inline-block; padding:2px 10px; border-radius:var(--radius-full); font-size:10px; font-weight:600; text-transform:uppercase; background:rgba(196,150,122,0.1); color:var(--color-warm);">
                      Actif — Expire le {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : '' }}
                    </span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif

    <!-- Historique des points -->
    <h3 style="font-family:var(--font-heading); font-size:var(--text-xl); margin-bottom:var(--space-md); color:var(--color-dark);">Historique des transactions</h3>
    <div class="card" style="padding:0; overflow-x:auto; margin-bottom: var(--space-2xl);">
      <table class="admin-table" style="min-width: 600px;">
        <thead>
          <tr>
            <th style="width:130px;">Date</th>
            <th>Description</th>
            <th style="width:120px; text-align:right;">Points</th>
            <th style="width:120px; text-align:center;">Type</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $tx)
            <tr>
              <td style="color:var(--color-text-muted); font-size:var(--text-sm);">{{ $tx->created_at->format('d M Y') }}</td>
              <td>
                <div style="display:flex; align-items:center; gap:var(--space-sm);">
                  <span style="font-size:16px;">
                    @if($tx->points > 0)
                      @if($tx->type === 'signup') 🎉 @elseif($tx->type === 'review') ⭐ @else 🛒 @endif
                    @else
                      🎁
                    @endif
                  </span>
                  <div>
                    <strong style="font-size:var(--text-sm); color: var(--color-dark);">{{ $tx->description }}</strong>
                  </div>
                </div>
              </td>
              <td style="text-align:right;">
                @if($tx->points > 0)
                  <span style="color:var(--color-success); font-weight:600; font-family:var(--font-display); font-size:var(--text-md);">+{{ $tx->points }}</span>
                @else
                  <span style="color:var(--color-error); font-weight:600; font-family:var(--font-display); font-size:var(--text-md);">{{ $tx->points }}</span>
                @endif
              </td>
              <td style="text-align:center;">
                @php
                  $badgeStyles = match($tx->type) {
                      'purchase' => 'background:rgba(90,143,110,0.1); color:var(--color-success);',
                      'review' => 'background:rgba(196,150,122,0.1); color:var(--color-warm);',
                      'signup' => 'background:rgba(70,120,180,0.1); color:#4678b4;',
                      'exchange' => 'background:rgba(199,80,80,0.1); color:var(--color-error);',
                      default => 'background:var(--color-bg-light); color:var(--color-text-muted);'
                  };
                  $typeLabels = [
                      'purchase' => 'Achat',
                      'review' => 'Avis',
                      'signup' => 'Inscription',
                      'exchange' => 'Échange',
                      'admin' => 'Ajustement',
                  ];
                @endphp
                <span style="display:inline-block; padding:2px 10px; border-radius:var(--radius-full); font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; {{ $badgeStyles }}">{{ $typeLabels[$tx->type] ?? $tx->type }}</span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" style="text-align:center; padding:var(--space-2xl); color:var(--color-text-muted);">
                Aucune transaction fidélité dans votre historique.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Custom Exchange Confirm Modal -->
    <div id="exchange-confirm-modal" style="display:none; position:fixed; inset:0; z-index:99999; align-items:center; justify-content:center;">
      <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5);" onclick="closeExchangeConfirm()"></div>
      <div style="position:relative; background:#fff; border-radius:14px; padding:2rem; max-width:420px; width:90%; box-shadow:0 15px 50px rgba(0,0,0,0.15); text-align:center;">
        <div style="font-size:2.5rem; margin-bottom:1rem;">🎁</div>
        <h4 style="font-family:var(--font-heading); font-size:1.25rem; color:var(--color-dark); margin-bottom:0.75rem;">Confirmer l'échange</h4>
        <p id="exchange-confirm-text" style="font-size:14px; color:var(--color-text-muted); margin-bottom:1.5rem; line-height:1.6;"></p>
        <div style="display:flex; gap:0.75rem; justify-content:center;">
          <button class="action-btn" onclick="closeExchangeConfirm()" style="width:auto; height:auto; padding:8px 20px; font-size:13px; border:1px solid var(--color-border); background:#fff; cursor:pointer;">Annuler</button>
          <button class="btn-primary" id="exchange-confirm-ok" style="font-size:13px; padding:8px 20px; cursor:pointer;">Confirmer</button>
        </div>
      </div>
    </div>

    <!-- Custom Exchange Success Modal -->
    <div id="exchange-success-modal" style="display:none; position:fixed; inset:0; z-index:99999; align-items:center; justify-content:center;">
      <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5);" onclick="closeExchangeSuccess()"></div>
      <div style="position:relative; background:#fff; border-radius:14px; padding:2.5rem 2rem; max-width:440px; width:90%; box-shadow:0 15px 50px rgba(0,0,0,0.15); text-align:center;">
        <div style="font-size:3rem; margin-bottom:1rem;">🎉</div>
        <h4 style="font-family:var(--font-heading); font-size:1.4rem; color:var(--color-dark); margin-bottom:0.75rem;">Félicitations !</h4>
        <p id="exchange-success-text" style="font-size:14px; color:var(--color-text-muted); margin-bottom:1.5rem; line-height:1.6;"></p>
        
        <!-- Generated Coupon Box -->
        <div style="background:var(--color-bg-light); border:1px dashed var(--color-warm); padding:1rem; border-radius:8px; margin-bottom:1.5rem;">
          <span style="font-size:11px; text-transform:uppercase; letter-spacing:0.1em; color:var(--color-text-muted); display:block; margin-bottom:0.5rem;">Votre code de réduction unique</span>
          <div style="display:flex; justify-content:center; align-items:center; gap:0.5rem;">
            <code id="success-coupon-code" style="font-family:monospace; font-size:1.3rem; font-weight:700; color:var(--color-dark);">K-LOY-XXXXXX</code>
            <button id="success-copy-btn" class="btn-secondary" style="padding:4px 8px; font-size:11px; cursor:pointer;">📋 Copier</button>
          </div>
        </div>
        
        <button class="btn-primary" onclick="closeExchangeSuccess()" style="width:100%; padding:12px 0; cursor:pointer;">Fermer et actualiser</button>
      </div>
    </div>
  @endif
</div>
@endsection

@section('scripts')
<script>
document.getElementById('mobileToggle')?.addEventListener('click', () => document.getElementById('sidebar').classList.toggle('open'));
document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'compte-recompenses') l.classList.add('active'); });

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        if (window.showToast) {
            window.showToast('Code promo copié ! Vous pouvez l\'utiliser dans votre panier.', 'success');
        } else {
            alert('Code promo copié !');
        }
    }).catch(err => {
        console.error('Erreur lors de la copie : ', err);
    });
}

var currentRewardId = null;
function exchangePoints(rewardId, rewardName, points) {
    currentRewardId = rewardId;
    document.getElementById('exchange-confirm-text').innerHTML = `Voulez-vous vraiment échanger <strong>${points} points</strong> contre la récompense <br>"<strong>${rewardName}</strong>" ?`;
    document.getElementById('exchange-confirm-modal').style.display = 'flex';
}

function closeExchangeConfirm() {
    document.getElementById('exchange-confirm-modal').style.display = 'none';
    currentRewardId = null;
}

document.getElementById('exchange-confirm-ok').addEventListener('click', function() {
    if (!currentRewardId) return;
    
    const okBtn = this;
    okBtn.disabled = true;
    okBtn.innerText = 'Traitement...';
    
    fetch('{{ route('account.rewards.exchange') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSR-Token': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            reward_id: currentRewardId
        })
    })
    .then(response => response.json().then(data => ({ status: response.status, body: data })))
    .then(res => {
        okBtn.disabled = false;
        okBtn.innerText = 'Confirmer';
        closeExchangeConfirm();
        
        if (res.status === 200 && res.body.success) {
            document.getElementById('exchange-success-text').innerText = res.body.message;
            document.getElementById('success-coupon-code').innerText = res.body.coupon.code;
            
            document.getElementById('success-copy-btn').onclick = function() {
                copyToClipboard(res.body.coupon.code);
            };
            
            document.getElementById('exchange-success-modal').style.display = 'flex';
        } else {
            if (window.showToast) {
                window.showToast('Erreur : ' + (res.body.error || 'Impossible d\'échanger vos points.'), 'error');
            } else {
                alert('Erreur : ' + (res.body.error || 'Impossible d\'échanger vos points.'));
            }
        }
    })
    .catch(err => {
        okBtn.disabled = false;
        okBtn.innerText = 'Confirmer';
        closeExchangeConfirm();
        console.error('Exchange error:', err);
        if (window.showToast) {
            window.showToast('Une erreur réseau est survenue.', 'error');
        } else {
            alert('Une erreur réseau est survenue.');
        }
    });
});

function closeExchangeSuccess() {
    document.getElementById('exchange-success-modal').style.display = 'none';
    window.location.reload();
}
</script>
@endsection
