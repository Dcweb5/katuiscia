@extends('layouts.auth')

@section('title', 'Nouveau mot de passe')

@section('content')
<div class="auth-split">
  <div class="auth-image">
    <div style="position:absolute;inset:0;background:linear-gradient(135deg, var(--color-warm), var(--color-dark));z-index:0;"></div>
    <div class="auth-slideshow">
      <div class="auth-slide active"><div class="auth-slide__icon">🔑</div><h2 class="auth-slide__title">Nouveau mot de passe</h2><p class="auth-slide__text">Choisissez un mot de passe sécurisé. Minimum 8 caractères.</p></div>
      <div class="auth-slide"><div class="auth-slide__icon">🎉</div><h2 class="auth-slide__title">Mot de passe réinitialisé</h2><p class="auth-slide__text">Votre mot de passe a bien été modifié.</p><a href="{{ url('connexion') }}" class="auth-slide__cta">Se connecter</a></div>
    </div>
    <div class="auth-slide-dots"><button class="auth-slide-dot active" data-dot="0"></button><button class="auth-slide-dot" data-dot="1"></button></div>
  </div>

  <div class="auth-form-container">
    <a href="{{ url('connexion') }}" class="back-home"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m15 18-6-6 6-6"/></svg>Retour</a>
    <div class="auth-form fade-in-up delay-1">
      <a href="{{ url('/') }}"><img src="{{ asset('assets/images/K LOGO.png') }}" alt="KATUISCIA" class="auth-logo"></a>
      <div class="auth-header"><h1 class="auth-title">Nouveau mot de passe</h1><p class="auth-subtitle">Choisissez un mot de passe sécurisé pour votre compte.</p></div>

      @if($errors->any())
      <div style="padding:12px 16px; background:rgba(199,80,80,0.1); border:1px solid var(--color-error); border-radius:8px; color:var(--color-error); margin-bottom:16px;">
        @foreach($errors->all() as $error)<p style="margin:0;">{{ $error }}</p>@endforeach
      </div>
      @endif

      <form method="POST" action="{{ url('reinitialisation') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token ?? '' }}">
        <input type="hidden" name="email" value="{{ request('email') }}">
        <div class="form-group fade-in-up delay-2">
          <label class="form-label">Adresse e-mail</label>
          <input type="email" class="form-input" value="{{ request('email') }}" readonly style="background:var(--color-gray); cursor:not-allowed;">
        </div>
        <div class="form-group fade-in-up delay-3">
          <label class="form-label" for="password">Nouveau mot de passe</label>
          <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required minlength="8" autocomplete="new-password">
        </div>
        <div class="form-group fade-in-up delay-3">
          <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn-auth fade-in-up delay-4">Réinitialiser mon mot de passe</button>
      </form>
      <p class="auth-footer fade-in-up delay-5"><a href="{{ url('connexion') }}" style="text-transform:uppercase; letter-spacing:0.05em; font-size:12px;">← Retour à la connexion</a></p>
    </div>
  </div>
</div>
<script>let c=0;const s=document.querySelectorAll('.auth-slide'),d=document.querySelectorAll('.auth-slide-dot');function show(n){s.forEach(e=>e.classList.remove('active'));d.forEach(e=>e.classList.remove('active'));c=n%s.length;s[c].classList.add('active');d[c].classList.add('active');}d.forEach(e=>e.addEventListener('click',()=>show(parseInt(e.dataset.dot))));setInterval(()=>show(c+1),5000);</script>
</div>
@endsection
