{{--
    karyawan/create.blade.php
    Form untuk menambah 1 data karyawan baru (Nama, NIK, Jabatan, Email,
    No Telepon, Gaji Pokok). Submit ke KaryawanController@store. Error validasi
    ditampilkan lewat @error, sumbernya dari App\Http\Requests\KaryawanRequest.
--}}
@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="app-shell app-shell--narrow">

    <header class="app-header">
        <div class="app-header__brand">
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
                    placeholder="Masukan Nama Lengkap Karyawan..."
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
                    placeholder="Masukan Nomor Induk Karyawan..."
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
                    placeholder="Contoh : Staff Manager"
                >
                @error('jabatan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input
                    type="email" id="email" name="email" required
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="emailkamu@gmail.com"
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
                    placeholder="Masukan Nomor Telepon..."
                >
                @error('no_telepon')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="gaji_pokok_default" class="form-label">Gaji Pokok *</label>
                <input
                    type="number" step="0.01" min="0"
                    id="gaji_pokok_default" name="gaji_pokok_default" required
                    value="{{ old('gaji_pokok_default') }}"
                    class="form-control @error('gaji_pokok_default') is-invalid @enderror"
                    placeholder="Contoh : 3000000"
                >
                @error('gaji_pokok_default')
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
