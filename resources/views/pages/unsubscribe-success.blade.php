@extends('layouts.public')

@section('title', 'Désinscription Newsletter')

@section('content')
<div class="pt-[calc(190px+3rem)] pb-20 max-w-site mx-auto px-4 lg:px-8 text-center" style="font-family: Inter, system-ui, sans-serif;">
  <div class="max-w-md mx-auto bg-white p-12 rounded-[16px] border border-border-k shadow-sm" style="background:#fff; border: 1px solid #ede4db; padding: 3rem; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
    <div style="font-size: 48px; margin-bottom: 24px;">🌿</div>
    <h1 class="font-heading text-3xl font-light text-dark mb-4" style="font-family: var(--font-display), serif; font-size: 1.8rem; font-weight: 300; margin-bottom: 1rem; color: var(--color-dark);">Désinscription confirmée</h1>
    <p class="text-text-light mb-8 leading-relaxed" style="color: var(--color-text-light); line-height: 1.6; margin-bottom: 2rem; font-size: 14px;">
      L'adresse e-mail <strong>{{ $email }}</strong> a bien été retirée de notre liste de diffusion. Vous ne recevrez plus nos offres et actualités.
    </p>
    <a href="{{ url('/') }}" class="btn-katuiscia-filled inline-block" style="text-decoration:none; display:inline-block; padding: 12px 30px; background: var(--color-warm); color:#fff; border-radius: 8px; font-weight: 500; font-size: 14px; transition: background 0.2s;">Retour à l'accueil</a>
  </div>
</div>
@endsection
