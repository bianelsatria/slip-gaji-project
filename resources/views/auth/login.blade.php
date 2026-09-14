{{--
    auth/login.blade.php
    Halaman 1: Login.
    Menampilkan form Email & Kata Sandi. Error validasi (format email salah,
    email/password tidak cocok) ditampilkan lewat @error, dikirim dari
    AuthenticatedSessionController@store.
--}}
@extends('layouts.app')

@section('title', 'Masuk ke Sistem')

@section('content')
<div class="login-page">
    <div class="login-card">
        <p class="login-card__eyebrow">Sistem Slip Gaji</p>
        <h1 class="login-card__title">Masuk ke Sistem</h1>

        <form method="POST" action="{{ route('login.store') }}" novalidate>
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="nama.anda@perusahaan.com"
                    autofocus
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="••••••••••••"
                >
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block">Masuk ke Sistem</button>

            <a href="#" class="login-card__forgot">Lupa kata sandi?</a>
        </form>
    </div>
</div>
@endsection
