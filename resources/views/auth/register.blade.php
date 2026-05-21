@extends('layouts.auth')

@section('title', 'Inscription')

@section('description', 'Créez votre compte Katuiscia et rejoignez le Club Privilège.')

@section('content')
<div class="auth-split">

  <!-- FORMULAIRE GAUCHE -->
  <div class="auth-form-container" style="order:1;">
    <a href="{{ url('/') }}" class="back-home">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      Retour
    </a>
    
    <div class="auth-form fade-in-up delay-1" style="max-width:440px;">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/images/K LOGO.png') }}" alt="KATUISCIA" class="auth-logo">
      </a>
      
      <div class="auth-header">
        <h1 class="auth-title">Créer un compte</h1>
        <p class="auth-subtitle">Rejoignez l'univers Katuiscia pour une expérience personnalisée.</p>
      </div>

      <div class="social-login fade-in-up delay-2">
        <a href="{{ url('auth/google') }}" class="social-btn">
          <svg viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          S'inscrire avec Google
        </a>
        <a href="{{ url('auth/facebook') }}" class="social-btn">
          <svg viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          S'inscrire avec Facebook
        </a>
      </div>

      <div class="auth-divider fade-in-up delay-2">ou créer avec votre email</div>

      @if($errors->any())
      <div style="padding:12px 16px; background:rgba(199,80,80,0.1); border:1px solid var(--color-error); border-radius:8px; color:var(--color-error); margin-bottom:16px; font-size:13px;">
        <ul style="margin:0; padding-left:20px;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form method="POST" action="{{ url('inscription') }}">
        @csrf

        <div style="display:flex; gap:var(--space-sm);">
          <div class="form-group fade-in-up delay-2" style="flex:1;">
            <label class="form-label" for="firstname">Prénom *</label>
            <input type="text" id="firstname" name="firstname" class="form-input" placeholder="Sophie" value="{{ old('firstname') }}" required>
          </div>
          <div class="form-group fade-in-up delay-2" style="flex:1;">
            <label class="form-label" for="lastname">Nom *</label>
            <input type="text" id="lastname" name="lastname" class="form-input" placeholder="Dubois" value="{{ old('lastname') }}" required>
          </div>
        </div>

        <div class="form-group fade-in-up delay-3">
          <label class="form-label" for="email">Adresse e-mail *</label>
          <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com" value="{{ old('email') }}" required autocomplete="email">
        </div>

        <div class="form-group fade-in-up delay-3">
          <label class="form-label" for="phone">Numéro de téléphone</label>
          <input type="tel" id="phone" name="phone" class="form-input" placeholder="+33 6 12 34 56 78" value="{{ old('phone') }}">
        </div>

        <div style="display:flex; gap:var(--space-sm);">
          <div class="form-group fade-in-up delay-3" style="flex:1;">
            <label class="form-label" for="city">Ville</label>
            <input type="text" id="city" name="city" class="form-input" placeholder="Paris" value="{{ old('city') }}">
          </div>
          <div class="form-group fade-in-up delay-3" style="flex:1;">
            <label class="form-label" for="postal_code">Boîte Postale</label>
            <input type="text" id="postal_code" name="postal_code" class="form-input" placeholder="BP 12345" value="{{ old('postal_code') }}">
          </div>
        </div>

        <div class="form-group fade-in-up delay-4">
          <label class="form-label" for="country">Pays</label>
          <select id="country" name="country" class="form-input">
            <option value="">Sélectionnez votre pays...</option>
            <option value="FR" @selected(old('country', 'FR')=='FR')>France</option>
            <option value="BE">Belgique</option>
            <option value="CH">Suisse</option>
            <option value="CA">Canada</option>
            <option value="SN">Sénégal</option>
            <option value="CI">Côte d'Ivoire</option>
            <option value="CM">Cameroun</option>
            <option value="CD">RD Congo</option>
            <option value="GA">Gabon</option>
            <option value="MA">Maroc</option>
            <option value="TN">Tunisie</option>
            <option value="LU">Luxembourg</option>
            <option value="OTHER">Autre</option>
          </select>
        </div>
        
        <div class="form-group fade-in-up delay-4">
          <label class="form-label" for="password">Mot de passe *</label>
          <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required minlength="8" autocomplete="new-password">
          <small style="font-size:11px; color:var(--color-text-muted); margin-top:4px; display:block;">Minimum 8 caractères, 1 majuscule, 1 chiffre.</small>
        </div>

        <div class="form-group fade-in-up delay-4">
          <label class="form-label" for="password_confirmation">Confirmer le mot de passe *</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required autocomplete="new-password">
        </div>

        <div class="form-options fade-in-up delay-5" style="flex-direction:column; align-items:flex-start; gap:var(--space-sm);">
          <label class="form-checkbox-label" style="align-items:flex-start;">
            <input type="checkbox" name="terms" required style="margin-top:4px;">
            <span style="font-size:0.8rem; line-height:1.4;">J'accepte la <a href="#" style="color:var(--color-dark); text-decoration:underline;">politique de confidentialité</a> et les <a href="#" style="color:var(--color-dark); text-decoration:underline;">conditions générales</a>.</span>
          </label>
          <label class="form-checkbox-label">
            <input type="checkbox" name="newsletter" value="1">
            <span style="font-size:0.8rem;">Je souhaite recevoir les offres et actualités KATUISCIA.</span>
          </label>
        </div>

        <button type="submit" class="btn-auth fade-in-up delay-5">Créer mon compte</button>
      </form>

      <p class="auth-footer fade-in-up delay-5">
        Déjà un compte ? <a href="{{ url('connexion') }}">Se connecter</a>
      </p>
    </div>
  </div>

  <!-- SLIDESHOW DROITE -->
  <div class="auth-image" style="order:2;">
    <img src="{{ asset('assets/images/hero-1.webp') }}" alt="Ambiance Katuiscia" class="bg" onerror="this.style.display='none';">
    <div class="auth-slideshow">
      <div class="auth-slide active" data-slide="0">
        <div class="auth-slide__icon">🌿</div>
        <h2 class="auth-slide__title">Beauté Naturelle</h2>
        <p class="auth-slide__text">Des ingrédients 100% naturels, formulés en France.</p>
        <a href="{{ url('boutique') }}" class="auth-slide__cta">Notre philosophie</a>
      </div>
      <div class="auth-slide" data-slide="1">
        <div class="auth-slide__icon">🎁</div>
        <h2 class="auth-slide__title">100 Points Offerts</h2>
        <p class="auth-slide__text">Créez votre compte et recevez 100 points de bienvenue.</p>
        <a href="#" class="auth-slide__cta">En savoir plus</a>
      </div>
      <div class="auth-slide" data-slide="2">
        <div class="auth-slide__icon">📦</div>
        <h2 class="auth-slide__title">Livraison Gratuite</h2>
        <p class="auth-slide__text">Profitez de la livraison offerte dès 75 € d'achat.</p>
        <a href="{{ url('boutique') }}" class="auth-slide__cta">Voir la boutique</a>
      </div>
    </div>
    <div class="auth-slide-dots">
      <button class="auth-slide-dot active" data-dot="0"></button>
      <button class="auth-slide-dot" data-dot="1"></button>
      <button class="auth-slide-dot" data-dot="2"></button>
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
