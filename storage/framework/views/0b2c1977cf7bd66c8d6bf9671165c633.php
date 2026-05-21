<?php $__env->startSection('title', 'Mot de passe oublié'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-split">
  <div class="auth-image">
    <div style="position:absolute;inset:0;background:linear-gradient(135deg, var(--color-warm), var(--color-dark));z-index:0;"></div>
    <div class="auth-slideshow">
      <div class="auth-slide active" data-slide="0">
        <div class="auth-slide__icon">🔐</div>
        <h2 class="auth-slide__title">Mot de passe oublié ?</h2>
        <p class="auth-slide__text">Saisissez votre email pour recevoir un lien de réinitialisation.</p>
      </div>
      <div class="auth-slide" data-slide="1">
        <div class="auth-slide__icon">📩</div>
        <h2 class="auth-slide__title">Email envoyé</h2>
        <p class="auth-slide__text">Vérifiez votre boîte mail et vos spams.</p>
      </div>
    </div>
    <div class="auth-slide-dots">
      <button class="auth-slide-dot active" data-dot="0"></button>
      <button class="auth-slide-dot" data-dot="1"></button>
    </div>
  </div>

  <div class="auth-form-container">
    <a href="<?php echo e(url('connexion')); ?>" class="back-home">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m15 18-6-6 6-6"/></svg>
      Retour
    </a>
    <div class="auth-form fade-in-up delay-1">
      <a href="<?php echo e(url('/')); ?>">
        <img src="<?php echo e(asset('assets/images/K LOGO.png')); ?>" alt="KATUISCIA" class="auth-logo">
      </a>
      <div class="auth-header">
        <h1 class="auth-title">Mot de passe oublié</h1>
        <p class="auth-subtitle">Entrez votre email pour recevoir un lien de réinitialisation.</p>
      </div>

      <?php if(session('success')): ?>
      <div style="padding:16px;background:rgba(90,143,110,0.08);border:1px solid var(--color-success);border-radius:var(--radius-md);color:var(--color-success);margin-bottom:16px;font-size:14px;line-height:1.6;">
        <?php echo session('success'); ?>

        <?php if(!session('reset_url')): ?>
        <p style="font-size:12px;color:var(--color-text-muted);margin-top:6px;">⚠️ Vérifiez vos spams si vous ne trouvez pas l'email.</p>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      <?php if(session('reset_url') && app()->environment('local')): ?>
      <div style="padding:16px;background:rgba(196,150,122,0.1);border:1px dashed var(--color-warm);border-radius:var(--radius-md);color:var(--color-dark);margin-bottom:16px;font-size:14px;word-break:break-all;line-height:1.8;">
        <strong>🔧 Mode développement — Lien de reset :</strong><br>
        <a href="<?php echo e(session('reset_url')); ?>" style="color:var(--color-warm);"><?php echo e(session('reset_url')); ?></a>
      </div>
      <?php endif; ?>

      <?php if($errors->any()): ?>
      <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:var(--radius-md);color:var(--color-error);margin-bottom:16px;font-size:14px;">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <p style="margin:0;"><?php echo e($error); ?></p> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="<?php echo e(url('mot-de-passe-oublie')); ?>" id="forgot-form">
        <?php echo csrf_field(); ?>
        <div class="form-group fade-in-up delay-3">
          <label class="form-label" for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com"
                 value="<?php echo e(old('email', session('__previous_email'))); ?>" required autocomplete="email" autofocus>
        </div>

        <?php if(!session('email_sent')): ?>
        
        <button type="submit" class="btn-auth fade-in-up delay-4">Envoyer le lien</button>
        <?php else: ?>
        
        <p style="text-align:center;font-size:13px;color:var(--color-text-muted);margin-bottom:var(--space-sm);">Vous n'avez pas reçu l'email ?</p>
        <button type="button" id="resend-btn" class="btn-auth" style="width:100%;opacity:0.5;cursor:not-allowed;" disabled>
          <span id="resend-text">Renvoyer le lien</span>
          <span id="resend-timer"></span>
        </button>
        <?php endif; ?>
      </form>

      <p class="auth-footer fade-in-up delay-5" style="margin-top:var(--space-lg);">
        <a href="<?php echo e(url('connexion')); ?>">← Retour à la connexion</a>
      </p>
    </div>
  </div>
</div>

<script>
let current = 0;
const slides = document.querySelectorAll('.auth-slide'), dots = document.querySelectorAll('.auth-slide-dot');
function showSlide(n) {
  slides.forEach(s => s.classList.remove('active'));
  dots.forEach(d => d.classList.remove('active'));
  current = n % slides.length;
  slides[current].classList.add('active');
  dots[current].classList.add('active');
}
dots.forEach(d => d.addEventListener('click', () => showSlide(parseInt(d.dataset.dot))));
setInterval(() => showSlide(current + 1), 5000);

// Timer de renvoi
var cooldown = <?php echo e(session('cooldown', 0)); ?>;
var resendBtn = document.getElementById('resend-btn');

if (resendBtn && cooldown > 0) {
  showSlide(1);
  var remaining = cooldown;
  
  function updateResendTimer() {
    var min = Math.floor(remaining / 60);
    var sec = remaining % 60;
    document.getElementById('resend-timer').textContent = ' (' + (min > 0 ? min + ' min ' + sec + ' s' : sec + ' s') + ')';
  }
  
  updateResendTimer();
  var interval = setInterval(function() {
    remaining--;
    if (remaining <= 0) {
      clearInterval(interval);
      resendBtn.disabled = false;
      resendBtn.style.opacity = '1';
      resendBtn.style.cursor = 'pointer';
      document.getElementById('resend-text').textContent = 'Renvoyer le lien';
      document.getElementById('resend-timer').textContent = '';
      return;
    }
    updateResendTimer();
  }, 1000);

  resendBtn.addEventListener('click', function() {
    if (this.disabled) return;
    this.disabled = true;
    this.textContent = 'Envoi en cours...';
    document.getElementById('forgot-form').submit();
  });
}

if (<?php echo e(session('email_sent', false) ? 'true' : 'false'); ?>) {
  showSlide(1);
}
</script>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>