<?php $__env->startSection('title', 'Mon Compte'); ?>

<?php $__env->startSection('head'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
  .hero-welcome { background:linear-gradient(135deg, var(--color-dark) 0%, #5a3d3d 50%, var(--color-warm) 100%); color:white; padding:var(--space-2xl) var(--space-xl); border-radius:var(--radius-xl); position:relative; overflow:hidden; margin-bottom:var(--space-xl); }
  .hero-welcome::after { content:''; position:absolute; top:-40px; right:-30px; font-size:160px; opacity:0.04; }
  .hero-welcome h1 { font-family:var(--font-display); font-size:2rem; margin-bottom:4px; color:white; }
  .hero-welcome p { opacity:0.8; margin:0; }
  .points-orb { width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,0.15); backdrop-filter:blur(8px); display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px solid rgba(255,255,255,0.3); animation:pulse-orb 3s ease-in-out infinite; }
  @keyframes pulse-orb { 0%,100% { transform:scale(1); box-shadow:0 0 0 0 rgba(255,255,255,0.2); } 50% { transform:scale(1.05); box-shadow:0 0 20px 5px rgba(255,255,255,0.1); } }
  .stat-icon-card { display:flex; align-items:center; gap:var(--space-md); padding:var(--space-lg); border-radius:var(--radius-lg); border:1px solid var(--color-border); background:white; transition:all 0.3s; }
  .stat-icon-card:hover { transform:translateY(-3px); box-shadow:var(--shadow-md); border-color:var(--color-warm); }
  .order-timeline { position:relative; padding-left:28px; }
  .order-timeline::before { content:''; position:absolute; left:10px; top:0; bottom:0; width:2px; background:var(--color-border); }
  .order-dot { position:absolute; left:-26px; top:4px; width:14px; height:14px; border-radius:50%; border:2px solid var(--color-warm); background:white; }
  .order-dot.active { background:var(--color-warm); }
  .chart-container { position:relative; width:100%; min-height:200px; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <?php $pts = auth()->user()->loyalty_points ?? 0; ?>

  <!-- HERO WELCOME -->
  <div class="hero-welcome">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:var(--space-xl);">
      <div>
        <span style="font-size:var(--text-xs); text-transform:uppercase; letter-spacing:0.15em; opacity:0.6;">Bon retour</span>
        <h1><?php echo e(auth()->user()->firstname ?? ''); ?> <?php echo e(auth()->user()->lastname ?? ''); ?></h1>
        <p>Votre espace bien-être et récompenses.</p>
      </div>
      <div class="points-orb">
        <span style="font-size:1.6rem; font-family:var(--font-display); font-weight:600; line-height:1;"><?php echo e($pts > 999 ? floor($pts/1000).'k' : $pts); ?></span>
        <span style="font-size:9px; text-transform:uppercase; opacity:0.7;">points</span>
      </div>
    </div>
  </div>

  <!-- STATS CARDS -->
  <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:var(--space-md); margin-bottom:var(--space-xl);">
    <div class="stat-icon-card">
      <div style="width:44px; height:44px; border-radius:var(--radius-md); background:var(--color-peach); display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;">✨</div>
      <div>
        <span style="font-size:var(--text-xs); color:var(--color-text-muted); text-transform:uppercase; letter-spacing:0.05em;">Fidélité</span>
        <strong style="font-size:var(--text-lg); color:var(--color-dark); display:block;"><?php echo e(number_format($pts, 0, ',', ' ')); ?> pts</strong>
        <span style="font-size:10px; color:var(--color-warm);"><?php echo e($pts >= 1500 ? 'Or ⭐' : 'Essentiel 🌱'); ?></span>
      </div>
    </div>
    <div class="stat-icon-card">
      <div style="width:44px; height:44px; border-radius:var(--radius-md); background:var(--color-mint); display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;">📦</div>
      <div>
        <span style="font-size:var(--text-xs); color:var(--color-text-muted); text-transform:uppercase; letter-spacing:0.05em;">Commandes</span>
        <strong style="font-size:var(--text-lg); color:var(--color-dark); display:block;">0</strong>
        <span style="font-size:10px; color:var(--color-text-muted);">En cours</span>
      </div>
    </div>
    <div class="stat-icon-card">
      <div style="width:44px; height:44px; border-radius:var(--radius-md); background:#e0d8ff; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;">⭐</div>
      <div>
        <span style="font-size:var(--text-xs); color:var(--color-text-muted); text-transform:uppercase; letter-spacing:0.05em;">Avis</span>
        <strong style="font-size:var(--text-lg); color:var(--color-dark); display:block;">0</strong>
        <span style="font-size:10px; color:var(--color-text-muted);">Publiés</span>
      </div>
    </div>
    <div class="stat-icon-card">
      <div style="width:44px; height:44px; border-radius:var(--radius-md); background:#ffe0cc; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;">🎁</div>
      <div>
        <span style="font-size:var(--text-xs); color:var(--color-text-muted); text-transform:uppercase; letter-spacing:0.05em;">Récompenses</span>
        <strong style="font-size:var(--text-lg); color:var(--color-dark); display:block;"><?php echo e($pts >= 500 ? 1 : 0); ?></strong>
        <span style="font-size:10px; color:var(--color-text-muted);">Disponibles</span>
      </div>
    </div>
  </div>

  <!-- PROGRESS TO TIER + ORDERS -->
  <div style="display:grid; grid-template-columns:1fr 1fr; gap:var(--space-xl); margin-bottom:var(--space-xl);">
    <!-- Loyalty Progress -->
    <div class="card" style="background:linear-gradient(145deg, var(--color-peach), rgba(196,150,122,0.08)); padding:var(--space-xl);">
      <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-md);">🍀 Progression fidélité</h3>
      <?php $progress = min(100, round($pts / 1500 * 100)); $remaining = max(0, 1500 - $pts); ?>
      <div style="display:flex; justify-content:space-between; margin-bottom:var(--space-xs); font-size:var(--text-sm);">
        <span>🌱 Essentiel</span><span><?php echo e($pts >= 1500 ? '✅' : '🎯'); ?> Or (1500 pts)</span>
      </div>
      <div style="width:100%; height:12px; background:rgba(255,255,255,0.6); border-radius:6px; overflow:hidden;">
        <div class="progress-fill" data-progress="<?php echo e($progress); ?>" style="width:0%; height:100%; background:linear-gradient(90deg, var(--color-warm), var(--color-dark)); border-radius:6px; transition:width 1.5s ease;"></div>
      </div>
      <p style="text-align:right; font-size:var(--text-xs); color:var(--color-text-muted); margin-top:var(--space-xs);"><?php echo e(number_format($pts, 0, ',', ' ')); ?> / 1 500 pts</p>
      <?php if($remaining > 0): ?>
      <p style="text-align:center; margin-top:var(--space-md); font-size:var(--text-sm); color:var(--color-dark);">🔥 Plus que <strong><?php echo e(number_format($remaining, 0, ',', ' ')); ?> pts</strong> pour le statut Or !</p>
      <?php endif; ?>
      <a href="<?php echo e(url('compte/recompenses')); ?>" class="btn-katuiscia" style="display:block; text-align:center; margin-top:var(--space-md);">Voir mes récompenses</a>
    </div>

    <!-- Order timeline -->
    <div class="card" style="padding:var(--space-xl);">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:var(--space-md);">
        <h3 style="font-family:var(--font-heading); font-size:var(--text-lg);">📋 Commandes récentes</h3>
        <a href="<?php echo e(url('compte/commandes')); ?>" style="font-size:var(--text-xs); color:var(--color-warm); text-decoration:none;">Tout voir →</a>
      </div>
      <div class="order-timeline">
        <div style="position:relative; margin-bottom:var(--space-lg);">
          <div class="order-dot active"></div>
          <strong style="font-size:var(--text-sm);">Commande #KAT-10512</strong>
          <span style="display:block; font-size:11px; color:var(--color-success);">En transit — Arrivée prévue demain</span>
          <span style="display:block; font-size:10px; color:var(--color-text-muted);">Nectar Lumineux x2 • 250 €</span>
        </div>
        <div style="position:relative;">
          <div class="order-dot"></div>
          <strong style="font-size:var(--text-sm);">Commande #KAT-10495</strong>
          <span style="display:block; font-size:11px; color:var(--color-text-muted);">Livrée le 24 Oct</span>
          <span style="display:block; font-size:10px; color:var(--color-text-muted);">Sérum Botanique Éclat • 110 €</span>
        </div>
      </div>
    </div>
  </div>

  <!-- ACTIVITY CHART -->
  <div class="card" style="padding:var(--space-xl);">
    <h3 style="font-family:var(--font-heading); font-size:var(--text-lg); margin-bottom:var(--space-lg);">📊 Votre activité</h3>
    <div class="chart-container"><canvas id="activityChart"></canvas></div>
  </div>
</div>

<script>
document.getElementById('mobileToggle')?.addEventListener('click', () => document.getElementById('sidebar').classList.toggle('open'));
document.querySelectorAll('.sidebar-link[data-page]').forEach(l => { if(l.dataset.page === 'compte') l.classList.add('active'); });

// Animate progress bar
setTimeout(() => {
  document.querySelectorAll('.progress-fill').forEach(el => {
    el.style.width = el.dataset.progress + '%';
  });
}, 300);

// Activity Chart
new Chart(document.getElementById('activityChart').getContext('2d'), {
  type: 'line',
  data: {
    labels: ['Sep', 'Oct', 'Nov', 'Déc', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai'],
    datasets: [{
      label: 'Points cumulés',
      data: [350, 460, 510, 760, 890, 1020, 1150, 1320, 1450],
      borderColor: '#C4967A',
      backgroundColor: ctx => {
        const g = ctx.chart.ctx.createLinearGradient(0,0,0,200);
        g.addColorStop(0,'rgba(196,150,122,0.3)');
        g.addColorStop(1,'rgba(196,150,122,0)');
        return g;
      },
      fill: true,
      tension: 0.4,
      borderWidth: 2.5,
      pointBackgroundColor: '#C4967A',
      pointRadius: 4,
      pointHoverRadius: 7,
    }]
  },
  options: {
    responsive:true, maintainAspectRatio:false,
    plugins: { legend:{display:false} },
    scales: {
      y: { grid:{color:'rgba(0,0,0,0.04)'}, ticks:{font:{size:11}} },
      x: { grid:{display:false}, ticks:{font:{size:11}} }
    },
    animation: { duration: 1500, easing:'easeOutQuart' }
  }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.account', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\account\dashboard.blade.php ENDPATH**/ ?>