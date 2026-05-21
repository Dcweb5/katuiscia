<!DOCTYPE html>
<html lang="fr" data-theme="katuiscia">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') — KATUISCIA</title>
  <meta name="description" content="@yield('description')">
  <link rel="icon" type="image/png" href="{{ asset('assets/images/K ICONE.png') }}">
  <link rel="stylesheet" href="{{ asset('dist/tailwind.css') }}">
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/global.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
  @yield('head')
</head>
<body class="auth-page">
  @yield('content')
</body>
</html>
