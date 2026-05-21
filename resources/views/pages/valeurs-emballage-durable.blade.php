@extends('layouts.public')

@section('title', 'Emballage Durable — KATUISCIA')

@section('head')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endsection

@section('content')
<main id="main-content">
    
    <!-- Hero Section -->
    <section class="relative h-[70vh] flex items-center justify-center overflow-hidden bg-dark text-white text-center">
      <div class="absolute inset-0 w-full h-full bg-[url('assets/images/emballage-hero.png')] bg-cover bg-center bg-fixed opacity-60 z-0"></div>
      <div class="relative z-10 max-w-4xl px-8 reveal-k-k">
        <h1 class="font-heading text-5xl md:text-6xl lg:text-7xl mb-6 font-light tracking-tight">Emballage Durable</h1>
        <p class="text-lg md:text-xl font-light opacity-90 leading-relaxed max-w-2xl mx-auto">Plongez dans l'univers de l'éco-conception chez KATUISCIA, où l'innovation rencontre la durabilité pour transmettre un monde plus beau.</p>
      </div>
    </section>

    <!-- 3R Section -->
    <section class="py-24 bg-white">
      <div class="max-w-site mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16 reveal-k-k">
          <h2 class="font-heading text-3xl md:text-4xl text-dark mb-6">Réduire, Réutiliser, Recycler</h2>
          <p class="text-lg text-text-light leading-relaxed">Notre engagement à réduire notre impact environnemental. Dès le début, KATUISCIA a pour objectif d'œuvrer dans le respect de la nature. Grâce à des emballages réduits, des solutions rechargeables et des matériaux plus durables, nous progressons résolument dans cette voie.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-cream p-10 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-2 text-center reveal-k-k delay-1">
            <div class="text-5xl text-warm mb-6">♳</div>
            <h3 class="font-heading text-2xl text-dark mb-4">Réduire</h3>
            <p class="text-text-light leading-relaxed">Nous recherchons constamment des solutions pour réduire le poids et le volume de nos emballages afin de préserver les ressources précieuses de la nature. 100% du papier utilisé dans nos boîtes est issu de forêts gérées durablement.</p>
          </div>
          <div class="bg-cream p-10 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-2 text-center reveal-k-k delay-2">
            <div class="text-5xl text-warm mb-6">♺</div>
            <h3 class="font-heading text-2xl text-dark mb-4">Réutiliser</h3>
            <p class="text-text-light leading-relaxed">Nous développons des solutions rechargeables pour plusieurs de nos lignes de soins. Ces produits étant rechargeables, vous conservez le pot vide et ne rachetez que la recharge, réduisant considérablement votre empreinte.</p>
          </div>
          <div class="bg-cream p-10 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-2 text-center reveal-k-k delay-3">
            <div class="text-5xl text-warm mb-6">♻</div>
            <h3 class="font-heading text-2xl text-dark mb-4">Recycler</h3>
            <p class="text-text-light leading-relaxed">Dès le départ, nous concevons nos emballages dans une optique de durabilité, en privilégiant l'utilisation de verre recyclable et de plastiques recyclés. Notre objectif : 100% de nos emballages recyclables d'ici 2030.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Éco-conception Section -->
    <section class="py-24 bg-cream">
      <div class="max-w-site mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
          <div class="reveal-k-k">
            <img src="{{ asset('assets/images/biodiv-hero.png') }}" alt="Produit KATUISCIA dans un pot en verre" class="w-full h-[400px] md:h-[500px] object-cover rounded-2xl shadow-xl">
          </div>
          <div class="reveal-k-k delay-1 space-y-6">
            <h2 class="font-heading text-3xl md:text-4xl text-warm">Éco-conception sans compromis</h2>
            <p class="text-lg text-text-light leading-relaxed">Fermez, chargez et lissez avec nos nouveaux emballages rechargeables brevetés. Grâce à leur conception innovante, vous savez exactement quelle est la quantité de crème restante et quand la recharger.</p>
            <p class="text-lg text-text-light leading-relaxed"><strong class="text-dark font-semibold">Le résultat ?</strong> Jusqu'à 50% de plastique et de carton en moins par rapport à l'achat d'un nouveau flacon complet, sans aucun compromis sur l'expérience sensorielle luxueuse propre à KATUISCIA.</p>
            <div class="pt-4">
              <a href="{{ url('boutique') }}" class="btn-katuiscia-filled inline-block py-4 px-10 text-sm tracking-widest uppercase font-semibold">Découvrir nos soins</a>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
@endsection
