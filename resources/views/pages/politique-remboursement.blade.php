@extends('layouts.public')

@section('title', 'Politique de Remboursement — KATUISCIA')

@section('head')

@endsection

@section('content')
<main id="main-content">
    <div class="pt-[calc(160px+3rem)] pb-12 px-4 text-center reveal-k-k">
      <h1 class="font-heading text-4xl font-normal text-dark mb-4">Politique de Remboursement</h1>
      <p class="text-sm text-text-muted">Dernière mise à jour : 28 février 2026</p>
    </div>

    <section class="max-w-3xl mx-auto px-4 pb-24 reveal-k-k delay-1">
      <div class="space-y-8">
        <div>
          <h2 class="font-heading text-2xl font-medium text-dark mb-4">1. Délai de retour</h2>
          <p class="text-text-light leading-relaxed">Vous disposez de 14 jours après réception pour demander un retour.
          </p>
        </div>

        <div>
          <h2 class="font-heading text-2xl font-medium text-dark mb-4">2. Produits éligibles</h2>
          <p class="text-text-light leading-relaxed mb-4">Nous acceptons uniquement les produits répondant aux critères
            suivants :</p>
          <ul class="list-disc pl-6 space-y-2 text-text-light">
            <li>Produits non ouverts</li>
            <li>Produits non utilisés</li>
            <li>Produits dans leur emballage d’origine</li>
          </ul>
          <p class="text-text-light leading-relaxed mt-4"><strong>Pour des raisons d’hygiène :</strong> Les soins
            (crèmes, huiles, gommages, chantilly, maquillages) ne sont ni repris ni échangés si ouverts.</p>
        </div>

        <div>
          <h2 class="font-heading text-2xl font-medium text-dark mb-4">3. Remboursement</h2>
          <p class="text-text-light leading-relaxed">Une fois le retour accepté et reçu, nous procédons au remboursement
            sur le mode de paiement initial. Les frais de retour restent à votre charge.</p>
        </div>

        <div>
          <h2 class="font-heading text-2xl font-medium text-dark mb-4">4. Dommages & erreurs</h2>
          <p class="text-text-light leading-relaxed">Si vous recevez un article endommagé ou incorrect, veuillez nous
            contacter sous 48h avec photo à l'adresse : <a href="mailto:contact@katuiscia.com"
              class="text-dark underline hover:text-warm transition-colors">contact@katuiscia.com</a>.</p>
        </div>
      </div>
    </section>
  </main>
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
@endsection
