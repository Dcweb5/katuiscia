<!DOCTYPE html>
<html lang="fr" data-theme="katuiscia">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') — KATUISCIA Admin</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/K ICONE.png') }}">
  <link rel="stylesheet" href="{{ asset('dist/tailwind.css') }}">
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/global.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  @yield('head')
</head>
<body class="dashboard-layout admin-dashboard">
  @include('components.admin-sidebar')
  @include('components.toast')
  <main class="dashboard-main">
    @yield('content')
  </main>
  @yield('scripts')
</body>
</html>
