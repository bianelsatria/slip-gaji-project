{{--
    karyawan/create.blade.php
    Form untuk menambah 1 data karyawan baru (Nama, NIK, Jabatan, Email, No Telepon).
    Submit ke KaryawanController@store. Error validasi ditampilkan lewat @error,
    sumbernya dari App\Http\Requests\KaryawanRequest.
--}}
@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="app-shell app-shell--narrow">

    <header class="app-header">
        <div class="app-header__brand">
            <span class="app-header__logo">&#9993;</span>
            <span>Slip Gaji Karyawan</span>
        </div>
        <div class="app-header__user">
            <a href="{{ route('karyawan.index') }}" class="app-header__nav-link">&larr; Daftar Karyawan</a>
        </div>
    </header>

    <div class="app-body">
        <h1 class="page-toolbar__title" style="margin-bottom: 22px;">Tambah Karyawan</h1>

        <form method="POST" action="{{ route('karyawan.store') }}" novalidate>
            @csrf

            <div class="form-group">
                <label for="nama" class="form-label">Nama</label>
                <input
                    type="text" id="nama" name="nama"
                    value="{{ old('nama') }}"
                    class="form-control @error('nama') is-invalid @enderror"
                    placeholder="Nama lengkap karyawan"
                >
                @error('nama')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nik" class="form-label">NIK</label>
                <input
                    type="text" id="nik" name="nik"
                    value="{{ old('nik') }}"
                    class="form-control @error('nik') is-invalid @enderror"
                    placeholder="Nomor Induk Karyawan"
                >
                @error('nik')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="jabatan" class="form-label">Jabatan</label>
                <input
                    type="text" id="jabatan" name="jabatan"
                    value="{{ old('jabatan') }}"
                    class="form-control @error('jabatan') is-invalid @enderror"
                    placeholder="Contoh: Staff Keuangan"
                >
                @error('jabatan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email" id="email" name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="nama@perusahaan.com"
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_telepon" class="form-label">Nomor Telepon</label>
                <input
                    type="text" id="no_telepon" name="no_telepon"
                    value="{{ old('no_telepon') }}"
                    class="form-control @error('no_telepon') is-invalid @enderror"
                    placeholder="08xxxxxxxxxx"
                >
                @error('no_telepon')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('karyawan.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Karyawan</button>
            </div>
        </form>
    </div>
</div>
@endsection
