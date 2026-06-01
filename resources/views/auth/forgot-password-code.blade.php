@extends('layouts.auth')

@section('title', 'Entrez le code de validation')

@section('content')
<div class="auth-split">
  <div class="auth-image">
    <div style="position:absolute;inset:0;background:linear-gradient(135deg, var(--color-warm), var(--color-dark));z-index:0;"></div>
    <div class="auth-slideshow">
      <div class="auth-slide active">
        <div class="auth-slide__icon">🔑</div>
        <h2 class="auth-slide__title">Vérifiez vos emails</h2>
        <p class="auth-slide__text">Saisissez le code à 6 chiffres pour continuer la réinitialisation.</p>
      </div>
    </div>
  </div>

  <div class="auth-form-container">
    <a href="{{ url('mot-de-passe-oublie') }}" class="back-home">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m15 18-6-6 6-6"/></svg>
      Retour
    </a>
    <div class="auth-form fade-in-up delay-1">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/images/K LOGO.png') }}" alt="KATUISCIA" class="auth-logo">
      </a>
      <div class="auth-header">
        <h1 class="auth-title">Code de validation</h1>
        <p class="auth-subtitle">Entrez le code à 6 chiffres reçu par e-mail pour l'adresse <strong>{{ $email }}</strong>.</p>
      </div>

      @if($errors->any())
      <div style="padding:12px 16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:var(--radius-md);color:var(--color-error);margin-bottom:16px;font-size:14px;">
        @foreach($errors->all() as $error) <p style="margin:0;">{{ $error }}</p> @endforeach
      </div>
      @endif

      @if(session('email_error'))
      <div style="padding:16px;background:rgba(199,80,80,0.1);border:1px solid var(--color-error);border-radius:var(--radius-md);color:var(--color-error);margin-bottom:16px;font-size:14px;line-height:1.6;">
        ⚠️ {{ session('email_error') }}
      </div>
      @endif

      @if(app()->environment('local') || session('email_error'))
      <div style="padding:16px;background:rgba(196,150,122,0.1);border:1px dashed var(--color-warm);border-radius:var(--radius-md);color:var(--color-dark);margin-bottom:16px;font-size:14px;line-height:1.6;">
        <strong>🔧 Mode de secours (Serveur mail hors-ligne) :</strong><br>
        - Saisissez le code de réinitialisation suivant : <strong style="font-size: 16px; color: var(--color-warm);">{{ cache()->get('pwd_reset_code_' . $email) }}</strong><br>
        @if(cache()->get('pwd_reset_raw_' . $email))
        - Ou utilisez le lien direct de réinitialisation : <a href="{{ url('/reinitialisation/' . cache()->get('pwd_reset_raw_' . $email) . '?email=' . urlencode($email)) }}" style="color:var(--color-warm);text-decoration:underline;">cliquer ici</a>
        @endif
      </div>
      @endif

      @if(session('success'))
      <div style="padding:16px;background:rgba(90,143,110,0.08);border:1px solid var(--color-success);border-radius:var(--radius-md);color:var(--color-success);margin-bottom:16px;font-size:14px;">
        {{ session('success') }}
      </div>
      @endif

      <form method="POST" action="{{ route('password.verify-code') }}" id="code-form">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        
        <div class="form-group fade-in-up delay-2">
          <label class="form-label" for="code">Code de réinitialisation (6 chiffres)</label>
          <input type="text" id="code" name="code" class="form-input" placeholder="123456" required pattern="[0-9]{6}" maxlength="6" autofocus style="text-align:center;font-size:22px;letter-spacing:8px;font-weight:bold;">
        </div>

        <button type="submit" class="btn-auth fade-in-up delay-3">Valider le code</button>
      </form>

      <p class="auth-footer fade-in-up delay-4" style="margin-top:var(--space-lg); text-align:center;">
        Vous n'avez pas reçu le code ?
      </p>

      <form method="POST" action="{{ url('mot-de-passe-oublie') }}" style="margin-top: 8px;">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <button type="submit" class="btn-auth" style="background:transparent;border:1px solid var(--color-warm);color:var(--color-warm);width:100%;">
          Renvoyer l'e-mail
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
