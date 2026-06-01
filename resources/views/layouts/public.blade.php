<!DOCTYPE html>
<html lang="fr" data-theme="katuiscia">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'KATUISCIA — Beauté Botanique de Luxe')</title>
  <meta name="description" content="@yield('description', 'Découvrez KATUISCIA, des formulations botaniques soignées pour élever votre rituel quotidien.')">
  <link rel="icon" type="image/png" href="{{ asset('assets/images/K ICONE.png') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('dist/tailwind.css') }}">
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/global.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  @yield('head')
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    
    // Read local consent choice
    var consentChoice = localStorage.getItem('katuiscia_cookies');
    var isGranted = consentChoice === 'accepted';
    
    gtag('consent', 'default', {
      'ad_storage': isGranted ? 'granted' : 'denied',
      'ad_user_data': isGranted ? 'granted' : 'denied',
      'ad_personalization': isGranted ? 'granted' : 'denied',
      'analytics_storage': isGranted ? 'granted' : 'denied'
    });
  </script>
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-1LFTT4JZ9R"></script>
  <script>
    gtag('js', new Date());
    gtag('config', 'G-1LFTT4JZ9R');
  </script>
</head>
<body class="bg-cream text-dark font-body antialiased">

  @include('components.header')

  <main id="main-content">
    @yield('content')
  </main>

  @include('components.footer')

  <script type="module" src="{{ asset('js/main.js') }}"></script>
  <script src="{{ asset('js/cart-ajax.js') }}"></script>
  @yield('scripts')

  @include('components.chatbot')
</body>
</html>
