@extends('layouts.auth')

@section('title', 'Connexion')

@section('description', 'Connectez-vous à votre compte Katuiscia.')

@section('content')
<div class="auth-split">

  <!-- SLIDESHOW GAUCHE -->
  <div class="auth-image">
    <img src="{{ asset('assets/images/beaute-hero.webp') }}" alt="Ambiance Katuiscia" class="bg" onerror="this.style.display='none';this.nextElementSibling.style.background='linear-gradient(135deg, var(--color-warm), var(--color-dark))';">
    <div style="position:absolute;inset:0;z-index:1;display:none;"></div>

    <div class="auth-slideshow">
      <div class="auth-slide active" data-slide="0">
        <div class="auth-slide__icon">✨</div>
        <h2 class="auth-slide__title">Votre Rituel Beauté</h2>
        <p class="auth-slide__text">Découvrez notre sélection de soins naturels et artisanaux, conçus pour sublimer votre beauté au naturel.</p>
        <a href="{{ url('boutique') }}" class="auth-slide__cta">Explorer la boutique</a>
      </div>
      <div class="auth-slide" data-slide="1">
        <div class="auth-slide__icon">🎓</div>
        <h2 class="auth-slide__title">Formations Beauté</h2>
        <p class="auth-slide__text">Apprenez les secrets de la cosmétique artisanale avec nos experts.</p>
        <a href="{{ url('formation') }}" class="auth-slide__cta">Découvrir les formations</a>
      </div>
      <div class="auth-slide" data-slide="2">
        <div class="auth-slide__icon">🤝</div>
        <h2 class="auth-slide__title">Partenariat Grossiste</h2>
        <p class="auth-slide__text">Revendez nos produits dans votre salon ou boutique.</p>
        <a href="{{ url('grossiste') }}" class="auth-slide__cta">Devenir partenaire</a>
      </div>
      <div class="auth-slide" data-slide="3">
        <div class="auth-slide__icon">👑</div>
        <h2 class="auth-slide__title">Club Privilège</h2>
        <p class="auth-slide__text">Cumulez des points, débloquez des récompenses exclusives.</p>
        <a href="{{ url('inscription') }}" class="auth-slide__cta">Rejoindre le club</a>
      </div>
    </div>
    <div class="auth-slide-dots">
      <button class="auth-slide-dot active" data-dot="0"></button>
      <button class="auth-slide-dot" data-dot="1"></button>
      <button class="auth-slide-dot" data-dot="2"></button>
      <button class="auth-slide-dot" data-dot="3"></button>
    </div>
  </div>

  <!-- FORMULAIRE -->
  <div class="auth-form-container">
    <a href="{{ url('/') }}" class="back-home">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      Retour
    </a>

    <div class="auth-form fade-in-up delay-1">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/images/K LOGO.png') }}" alt="KATUISCIA" class="auth-logo">
      </a>

      <div class="auth-header">
        <h1 class="auth-title">Bon retour</h1>
        <p class="auth-subtitle">Connectez-vous pour accéder à votre espace.</p>
      </div>

      <!-- Social Login -->
      <div class="social-login fade-in-up delay-2">
        <a href="{{ url('auth/google') }}" class="social-btn">
          <svg viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          Continuer avec Google
        </a>
        <a href="{{ url('auth/facebook') }}" class="social-btn">
          <svg viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          Continuer avec Facebook
        </a>
      </div>

      <div class="auth-divider fade-in-up delay-2">ou</div>

      @if($errors->any())
      <div style="padding:12px 16px; background:rgba(199,80,80,0.1); border:1px solid var(--color-error); border-radius:8px; color:var(--color-error); margin-bottom:16px; font-size:14px;">
        @foreach($errors->all() as $error)
          <p style="margin:0;">{{ $error }}</p>
        @endforeach
      </div>
      @endif

      <form method="POST" action="{{ url('connexion') }}">
        @csrf
        <div class="form-group fade-in-up delay-3">
          <label class="form-label" for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com" value="{{ old('email') }}" required autocomplete="email">
        </div>
        <div class="form-group fade-in-up delay-3">
          <label class="form-label" for="password">Mot de passe</label>
          <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required autocomplete="current-password">
        </div>
        <div class="form-options fade-in-up delay-4">
          <label class="form-checkbox-label">
            <input type="checkbox" name="remember">
            <span>Se souvenir de moi</span>
          </label>
          <a href="{{ url('mot-de-passe-oublie') }}" class="forgot-link">Mot de passe oublié ?</a>
        </div>
        <button type="submit" class="btn-auth fade-in-up delay-4">Se connecter</button>
      </form>

      <p class="auth-footer fade-in-up delay-5">
        Nouveau chez Katuiscia ? <a href="{{ url('inscription') }}">Créer un compte</a>
      </p>
    </div>
  </div>
</div>

<script>
let current = 0;
const slides = document.querySelectorAll('.auth-slide');
const dots = document.querySelectorAll('.auth-slide-dot');
function showSlide(n) {
  slides.forEach(s => s.classList.remove('active'));
  dots.forEach(d => d.classList.remove('active'));
  current = n % slides.length;
  slides[current].classList.add('active');
  dots[current].classList.add('active');
}
dots.forEach(d => d.addEventListener('click', () => showSlide(parseInt(d.dataset.dot))));
setInterval(() => showSlide(current + 1), 5000);
</script>
</div>
@endsection
