{{--
    slip-gaji/index.blade.php
    Halaman 2: Form Slip Gaji.
    Bisa dibuka dengan 2 cara (lihat routes/web.php & SlipGajiController):
    1. route('slip-gaji.create')          -> pakai karyawan milik user login
    2. route('slip-gaji.create-for', $id) -> pakai karyawan yang dipilih dari
       tabel List Karyawan (klik ikon Edit)
    Variabel $karyawan, $periode, $captchaA, $captchaB dikirim dari controller.
--}}
@extends('layouts.app')

@section('title', 'Slip Gaji Karyawan')

@section('content')
<div class="app-shell">

    <header class="app-header">
        <div class="app-header__brand">
            <span class="app-header__logo">&#9993;</span>
            <span>Slip Gaji Karyawan</span>
        </div>
        <div class="app-header__user">
            <a href="{{ route('karyawan.index') }}" class="app-header__nav-link">&larr; Daftar Karyawan</a>
            <span class="app-header__avatar">{{ $karyawan->inisial() }}</span>
            <span>{{ $karyawan->nama }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="app-header__logout" style="background:none;border:none;cursor:pointer;font-family:inherit;">Keluar</button>
            </form>
        </div>
    </header>

    <div class="app-body">

        {{-- Info karyawan: diambil dari model Karyawan (relasi ke user login), read-only --}}
        <div class="info-box">
            <div class="info-box__item">
                <span class="label">Nama Karyawan</span>
                <span class="value">{{ $karyawan->nama }}</span>
            </div>
            <div class="info-box__item">
                <span class="label">NIK</span>
                <span class="value">{{ $karyawan->nik }}</span>
            </div>
            <div class="info-box__item">
                <span class="label">Jabatan</span>
                <span class="value">{{ $karyawan->jabatan }}</span>
            </div>
            <div class="info-box__item">
                <span class="label">Periode</span>
                <span class="value">{{ $periode }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('slip-gaji.store') }}" novalidate>
            @csrf
            {{-- Kirim ID karyawan secara diam-diam (hidden) supaya controller tahu
                 slip gaji ini mau disimpan untuk karyawan yang mana. --}}
            <input type="hidden" name="karyawan_id" value="{{ $karyawan->id }}">

            <div class="columns-2">
                {{-- Kolom Penghasilan --}}
                <div class="panel">
                    <h2 class="panel__title">Penghasilan (Income)</h2>

                    <div class="field-money">
                        <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                        <input
                            type="number" step="0.01" min="0"
                            id="gaji_pokok" name="gaji_pokok"
                            value="{{ old('gaji_pokok', $karyawan->gaji_pokok_default) }}"
                            class="form-control @error('gaji_pokok') is-invalid @enderror"
                        >
                        <span class="edit-icon">&#9998;</span>
                        @error('gaji_pokok')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field-money">
                        <label for="lembur" class="form-label">Lembur</label>
                        <input
                            type="number" step="0.01" min="0"
                            id="lembur" name="lembur"
                            value="{{ old('lembur', 0) }}"
                            class="form-control @error('lembur') is-invalid @enderror"
                        >
                        <span class="edit-icon">&#9998;</span>
                        @error('lembur')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="panel__total">
                        <span class="label">Total Penghasilan</span>
                        <span class="value" id="total-penghasilan">Rp 0</span>
                    </div>
                </div>

                {{-- Kolom Potongan --}}
                <div class="panel">
                    <h2 class="panel__title">Potongan (Deductions)</h2>

                    <div class="field-money">
                        <label for="pinjaman_karyawan" class="form-label">Pinjaman Karyawan</label>
                        <input
                            type="number" step="0.01" min="0"
                            id="pinjaman_karyawan" name="pinjaman_karyawan"
                            value="{{ old('pinjaman_karyawan', 0) }}"
                            class="form-control @error('pinjaman_karyawan') is-invalid @enderror"
                        >
                        <span class="edit-icon">&#9998;</span>
                        @error('pinjaman_karyawan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="panel__total">
                        <span class="label">Total Potongan</span>
                        <span class="value" id="total-potongan">Rp 0</span>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Gaji Bersih --}}
            <div class="summary-box">
                <div>
                    <span class="label">Gaji Bersih Diterima</span>
                    <span class="amount" id="gaji-bersih">Rp 0</span>
                </div>
                <div class="summary-box__breakdown">
                    <div id="breakdown-penghasilan">Total Penghasilan: Rp 0</div>
                    <div class="minus" id="breakdown-potongan">- Total Potongan: Rp 0</div>
                </div>
            </div>

            {{-- Verifikasi Captcha --}}
            <div class="captcha-section">
                <div>
                    <h3 class="captcha-section__title">Verifikasi Keamanan</h3>
                    <p class="captcha-section__hint">Selesaikan perhitungan matematika sederhana berikut:</p>
                </div>
                <div>
                    <div class="captcha-question">
                        <span class="equation">{{ $captchaA }} + {{ $captchaB }} =</span>
                        <input
                            type="number"
                            id="jawaban_captcha" name="jawaban_captcha"
                            value="{{ old('jawaban_captcha') }}"
                            class="form-control @error('jawaban_captcha') is-invalid @enderror"
                            placeholder="Jawaban Anda"
                        >
                    </div>
                    @error('jawaban_captcha')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <p class="form-note">Pastikan seluruh data penghasilan dan potongan telah sesuai sebelum mengirim.</p>

            <button type="submit" id="btn-submit-slip" class="btn btn-primary btn-block" disabled>
                Kirim Slip Gaji (Selesaikan Captcha)
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/slip-gaji.js') }}"></script>
@endpush
