<!DOCTYPE html>
<html lang="fr" data-theme="katuiscia">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') — KATUISCIA</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/K ICONE.png') }}">
  <link rel="stylesheet" href="{{ asset('dist/tailwind.css') }}">
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/global.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  @yield('head')
</head>
<body class="dashboard-layout">
  @include('components.user-sidebar')
  @include('components.toast')
  <main class="dashboard-main">
    @yield('content')
  </main>
  @yield('scripts')
</body>
</html>
