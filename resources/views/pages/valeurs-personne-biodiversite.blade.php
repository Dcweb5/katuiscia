@extends('layouts.public')

@section('title', 'Personne & Biodiversité — KATUISCIA')

@section('head')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endsection

@section('content')
<main id="main-content">
    
    <!-- Hero Section -->
    <section class="relative h-[70vh] flex items-center justify-center overflow-hidden bg-dark text-white text-center">
      <div class="absolute inset-0 w-full h-full bg-[url('assets/images/biodiv-hero.webp')] bg-cover bg-center bg-fixed opacity-60 z-0"></div>
      <div class="relative z-10 max-w-4xl px-8 reveal-k-k">
        <h1 class="font-heading text-5xl md:text-6xl lg:text-7xl mb-6 font-light tracking-tight">Personne & Biodiversité</h1>
        <p class="text-lg md:text-xl font-light opacity-90 leading-relaxed max-w-2xl mx-auto">KATUISCIA met chaque jour en pratique la conviction que la beauté doit être porteuse de sens, en finançant et conduisant des actions pour celles et ceux qui en ont besoin.</p>
      </div>
    </section>

    <!-- Commitments Section -->
    <section class="py-24 bg-white">
      <div class="max-w-5xl mx-auto px-4 lg:px-8">
        <div class="text-center mb-20 reveal-k-k">
          <h2 class="font-heading text-3xl md:text-4xl text-warm mb-4">Nos actions solidaires & durables</h2>
          <p class="text-lg text-text-light">Un monde plus juste pour tous.</p>
        </div>

        <!-- Card 1 -->
        <div class="flex flex-col md:flex-row bg-cream rounded-2xl overflow-hidden shadow-lg mb-16 reveal-k-k delay-1">
          <div class="flex-1 min-h-[300px] bg-[url('assets/images/beaute-resp-hero.webp')] bg-cover bg-center"></div>
          <div class="flex-1 p-10 md:p-16 flex flex-col justify-center">
            <span class="self-start bg-warm text-white px-3 py-1 rounded-full text-xs uppercase tracking-widest mb-6">Sourcing Responsable</span>
            <h3 class="font-heading text-2xl md:text-3xl text-dark mb-4">Nos communautés locales</h3>
            <p class="text-text-light leading-relaxed">Nos formules innovantes sont élaborées à partir des plantes les plus brutes et les plus précieuses de la nature, grâce aux personnes qui les préservent. Nous soutenons des chaînes d'approvisionnement équitables et des programmes de soutien pour les communautés locales qui cultivent nos ingrédients.</p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="flex flex-col md:flex-row-reverse bg-cream rounded-2xl overflow-hidden shadow-lg mb-16 reveal-k-k delay-2">
          <div class="flex-1 min-h-[300px] bg-[url('assets/images/emballage-hero.webp')] bg-cover bg-center"></div>
          <div class="flex-1 p-10 md:p-16 flex flex-col justify-center">
            <span class="self-start bg-warm text-white px-3 py-1 rounded-full text-xs uppercase tracking-widest mb-6">Biodiversité</span>
            <h3 class="font-heading text-2xl md:text-3xl text-dark mb-4">Graines de Beauté</h3>
            <p class="text-text-light leading-relaxed">En partenariat avec des associations environnementales, KATUISCIA soutient des programmes de plantations pour protéger les écosystèmes et soutenir le reboisement. Chaque produit acheté contribue à préserver l'avenir d'espèces végétales essentielles.</p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="flex flex-col md:flex-row bg-cream rounded-2xl overflow-hidden shadow-lg reveal-k-k delay-3">
          <div class="flex-1 min-h-[300px] bg-[url('assets/images/blog-hero.webp')] bg-cover bg-center"></div>
          <div class="flex-1 p-10 md:p-16 flex flex-col justify-center">
            <span class="self-start bg-warm text-white px-3 py-1 rounded-full text-xs uppercase tracking-widest mb-6">Engagement Social</span>
            <h3 class="font-heading text-2xl md:text-3xl text-dark mb-4">Engagés pour l'éducation</h3>
            <p class="text-text-light leading-relaxed">Dans le cadre de notre engagement pour la protection des générations futures, nous nous associons à des ONG pour offrir des repas scolaires et un accès à l'éducation aux enfants de communautés défavorisées, afin qu'ils puissent construire un avenir meilleur.</p>
          </div>
        </div>

      </div>
    </section>

    <!-- Vision Section -->
    <section class="py-32 bg-dark flex flex-col items-center justify-center text-center px-4">
      <div class="max-w-4xl mx-auto reveal-k-k">
        <blockquote class="font-heading text-3xl md:text-4xl italic text-cream leading-relaxed">"La beauté de demain sera solidaire, ou ne sera pas."</blockquote>
      </div>
    </section>

  </main>
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
@endsection
