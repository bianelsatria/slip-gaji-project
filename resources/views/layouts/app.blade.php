{{--
    layouts/app.blade.php
    Layout dasar (kerangka) yang dipakai SEMUA halaman di aplikasi ini.
    Isinya: <head> dengan font Inter & file CSS utama, lalu @yield('content')
    yang akan diisi oleh masing-masing halaman lewat @extends('layouts.app').
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Slip Gaji')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @yield('content')

    @stack('scripts')
</body>
</html>
