@extends('layouts.auth')

@section('title', 'Vérification de l\'adresse e-mail')

@section('content')
<div class="auth-split">
  <div class="auth-image">
    <div style="position:absolute;inset:0;background:linear-gradient(135deg, var(--color-warm), var(--color-dark));z-index:0;"></div>
    <div class="auth-slideshow">
      <div class="auth-slide active">
        <div class="auth-slide__icon">✉️</div>
        <h2 class="auth-slide__title">Validez votre adresse e-mail</h2>
        <p class="auth-slide__text">Un lien de confirmation vous a été envoyé. Merci de cliquer dessus pour activer votre compte.</p>
      </div>
    </div>
  </div>

  <div class="auth-form-container">
    <div class="auth-form fade-in-up delay-1">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/images/K LOGO.png') }}" alt="KATUISCIA" class="auth-logo">
      </a>
      <div class="auth-header">
        <h1 class="auth-title">Vérifiez votre e-mail</h1>
        <p class="auth-subtitle">Un e-mail de validation contenant un lien de confirmation et un code à 6 chiffres vous a été envoyé.</p>
      </div>

      @if (session('success'))
      <div style="padding:16px;background:rgba(90,143,110,0.08);border:1px solid var(--color-success);border-radius:8px;color:var(--color-success);margin-bottom:16px;font-size:14px;line-height:1.6;">
        {{ session('success') }}
      </div>
      @endif

      @if (session('email_error'))
      <div style="padding:16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:16px;font-size:14px;line-height:1.6;">
        ⚠️ {{ session('email_error') }}
      </div>
      @endif

      @if (app()->environment('local') || session('email_error'))
      <div style="padding:16px;background:rgba(196,150,122,0.1);border:1px dashed var(--color-warm);border-radius:8px;color:var(--color-dark);margin-bottom:16px;font-size:14px;line-height:1.6;">
        <strong>🔧 Mode de secours (Serveur mail hors-ligne) :</strong><br>
        Saisissez le code de validation suivant pour confirmer votre compte : <strong style="font-size: 16px; color: var(--color-warm);">{{ cache()->get('email_verify_code_' . auth()->id()) }}</strong>
      </div>
      @endif

      @if($errors->any())
      <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:8px;color:var(--color-error);margin-bottom:16px;font-size:13px;">
        @foreach($errors->all() as $error) <p style="margin:0;">{{ $error }}</p> @endforeach
      </div>
      @endif

      <p style="font-size:14px;color:var(--color-text-light);line-height:1.6;margin-bottom:24px;">
        Veuillez confirmer votre adresse e-mail en cliquant sur le lien présent dans le message.
        Si vous préférez, vous pouvez également saisir le code de vérification à 6 chiffres reçu dans le champ ci-dessous.
      </p>

      <form method="POST" action="{{ route('verification.verify-code') }}" style="margin-bottom: 24px;">
        @csrf
        <div class="form-group" style="margin-bottom: 16px;">
          <label class="form-label" for="code">Code de vérification (6 chiffres)</label>
          <input type="text" id="code" name="code" class="form-input" placeholder="123456" required pattern="[0-9]{6}" maxlength="6" style="text-align:center;font-size:20px;letter-spacing:8px;font-weight:bold;">
        </div>
        <button type="submit" class="btn-auth">Confirmer le code</button>
      </form>

      <p style="font-size:13px;color:var(--color-text-light);line-height:1.6;margin-bottom:16px;text-align:center;">
        Vous n'avez pas reçu l'e-mail ?
      </p>
      <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom: 16px;">
        @csrf
        <button type="submit" class="btn-auth" style="background:transparent;border:1px solid var(--color-warm);color:var(--color-warm);">Renvoyer l'e-mail de confirmation</button>
      </form>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-auth" style="background:transparent;border:1px solid var(--color-border);color:var(--color-dark);margin-top:8px;">
          Se déconnecter
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
