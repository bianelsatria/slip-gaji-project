{{--
    slip-gaji/success.blade.php
    Halaman 3: Konfirmasi Sukses.
    Ditampilkan setelah SlipGajiController@store berhasil menyimpan slip gaji.
    Berisi ringkasan + 3 tombol aksi: Unduh PDF, Kirim ke Email, Kirim ke WhatsApp.
--}}
@extends('layouts.app')

@section('title', 'Slip Gaji Berhasil Dikirim')

@section('content')
<div class="app-shell">

    <header class="app-header">
        <div class="app-header__brand">
            <span>Slip Gaji Karyawan</span>
        </div>
        <div class="app-header__user">
            <a href="{{ route('karyawan.index') }}" class="app-header__nav-link">&larr; Daftar Karyawan</a>
            <span class="app-header__avatar">{{ $slipGaji->karyawan->inisial() }}</span>
            <span>{{ $slipGaji->karyawan->nama }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="app-header__logout" style="background:none;border:none;cursor:pointer;font-family:inherit;">Keluar</button>
            </form>
        </div>
    </header>

    <div class="app-body">

        @if (session('status'))
            <div class="alert-status">{{ session('status') }}</div>
        @endif

        <div class="success-box">
            <div class="success-icon">&#10003;</div>
            <h1 class="success-box__title">Slip Gaji Berhasil Dikirim!</h1>
            <p class="success-box__subtitle">
                Slip gaji untuk periode {{ $slipGaji->periode }} telah berhasil diproses dan disimpan.
            </p>

            <div class="summary-card">
                <h2 class="summary-card__title">Ringkasan Slip Gaji</h2>

                <div class="summary-card__row">
                    <span class="label">Nama Karyawan</span>
                    <span class="value">{{ $slipGaji->karyawan->nama }}</span>
                </div>
                <div class="summary-card__row">
                    <span class="label">NIK</span>
                    <span class="value">{{ $slipGaji->karyawan->nik }}</span>
                </div>
                <div class="summary-card__row">
                    <span class="label">Periode</span>
                    <span class="value">{{ $slipGaji->periode }}</span>
                </div>

                <div class="summary-card__divider"></div>

                <div class="summary-card__row summary-card__row--total">
                    <span class="label">Gaji Bersih</span>
                    <span class="value">{{ $slipGaji->formatRupiah('gaji_bersih') }}</span>
                </div>
            </div>

            <div class="success-actions">
                <a href="{{ route('slip-gaji.pdf', $slipGaji) }}" class="btn btn-outline">Unduh PDF</a>

                <form method="POST" action="{{ route('slip-gaji.email', $slipGaji) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline">Kirim ke Email</button>
                </form>

                <form method="POST" action="{{ route('slip-gaji.whatsapp', $slipGaji) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-success">Kirim ke WhatsApp</button>
                </form>
            </div>

            {{-- UPDATE: link ini sekarang menuju List Karyawan, karena itu sudah jadi halaman utama --}}
            <a href="{{ route('karyawan.index') }}" class="back-link">&larr; Kembali ke Halaman Utama</a>
        </div>
    </div>
</div>
@endsection