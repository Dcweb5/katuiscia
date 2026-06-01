@extends('layouts.auth')

@section('title', 'Définir un nouveau mot de passe')

@section('content')
<div class="auth-split">
  <div class="auth-image">
    <div style="position:absolute;inset:0;background:linear-gradient(135deg, var(--color-warm), var(--color-dark));z-index:0;"></div>
    <div class="auth-slideshow">
      <div class="auth-slide active">
        <div class="auth-slide__icon">🔒</div>
        <h2 class="auth-slide__title">Nouveau mot de passe</h2>
        <p class="auth-slide__text">Choisissez un mot de passe fort pour sécuriser votre compte.</p>
      </div>
    </div>
  </div>

  <div class="auth-form-container">
    <div class="auth-form fade-in-up delay-1">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/images/K LOGO.png') }}" alt="KATUISCIA" class="auth-logo">
      </a>
      <div class="auth-header">
        <h1 class="auth-title">Nouveau mot de passe</h1>
        <p class="auth-subtitle">Définissez votre nouveau mot de passe pour <strong>{{ $email }}</strong>.</p>
      </div>

      @if($errors->any())
      <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:var(--radius-md);color:var(--color-error);margin-bottom:16px;font-size:14px;">
        @foreach($errors->all() as $error) <p style="margin:0;">{{ $error }}</p> @endforeach
      </div>
      @endif

      <form method="POST" action="{{ route('password.update-code') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group fade-in-up delay-2">
          <label class="form-label" for="password">Nouveau mot de passe</label>
          <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required minlength="8">
          <small style="font-size:11px; color:var(--color-text-muted); margin-top:4px; display:block;">Minimum 8 caractères, 1 majuscule, 1 chiffre.</small>
        </div>

        <div class="form-group fade-in-up delay-2" style="margin-top: 12px;">
          <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required minlength="8">
        </div>

        <button type="submit" class="btn-auth fade-in-up delay-3" style="margin-top: 16px;">Mettre à jour le mot de passe</button>
      </form>
    </div>
  </div>
</div>
@endsection
