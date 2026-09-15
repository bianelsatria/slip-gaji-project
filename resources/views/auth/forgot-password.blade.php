{{--
    auth/forgot-password.blade.php
    Langkah 1 dari fitur Lupa Kata Sandi: form input email.
    Submit ke PasswordResetController@store, yang akan mengirim kode 6 digit
    ke email ini kalau emailnya terdaftar.
--}}
@extends('layouts.app')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="login-page">
    <div class="login-card">
        <p class="login-card__eyebrow">Sistem Slip Gaji</p>
        <h1 class="login-card__title">Lupa Kata Sandi</h1>
        <p style="text-align:center; color:#6B7280; font-size:13px; margin:-18px 0 22px;">
            Masukkan email akun Anda, kami akan mengirimkan kode verifikasi ke email tersebut.
        </p>

        @if (session('status'))
            <div class="alert-status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="emailkamu@gmail.com"
                    autofocus
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block">Kirim Kode</button>

            <a href="{{ route('login') }}" class="login-card__forgot">&larr; Kembali ke Login</a>
        </form>
    </div>
</div>
@endsection
