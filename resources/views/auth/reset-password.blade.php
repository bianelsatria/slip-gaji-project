{{--
    auth/reset-password.blade.php
    Langkah 2 dari fitur Lupa Kata Sandi: form input kode verifikasi + kata
    sandi baru. Email sudah otomatis terisi dari query string (dikirim dari
    PasswordResetController@store setelah kode berhasil dikirim).
    Submit ke PasswordResetController@reset.
--}}
@extends('layouts.app')

@section('title', 'Reset Kata Sandi')

@section('content')
<div class="login-page">
    <div class="login-card">
        <p class="login-card__eyebrow">Sistem Slip Gaji</p>
        <h1 class="login-card__title">Reset Kata Sandi</h1>
        <p style="text-align:center; color:#6B7280; font-size:13px; margin:-18px 0 22px;">
            Masukkan kode yang dikirim ke email Anda, lalu buat kata sandi baru.
        </p>

        @if (session('status'))
            <div class="alert-status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" novalidate>
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    class="form-control @error('email') is-invalid @enderror"
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="kode" class="form-label">Kode Verifikasi</label>
                <input
                    type="text" inputmode="numeric" maxlength="6"
                    id="kode"
                    name="kode"
                    value="{{ old('kode') }}"
                    class="form-control @error('kode') is-invalid @enderror"
                    placeholder="6 digit dari email"
                    autofocus
                >
                @error('kode')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi Baru</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Minimal 8 karakter"
                >
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Ulangi kata sandi baru"
                >
            </div>

            <button type="submit" class="btn btn-primary btn-block">Reset Kata Sandi</button>

            <a href="{{ route('password.request') }}" class="login-card__forgot">Belum dapat kode / kode kedaluwarsa? Kirim ulang</a>
        </form>
    </div>
</div>
@endsection
