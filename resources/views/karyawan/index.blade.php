{{--
    karyawan/index.blade.php
    Halaman utama aplikasi (tampil pertama kali setelah login).
    Menampilkan semua data karyawan dalam bentuk tabel
    (Nama, NIK, Jabatan, No. Telepon, Email, Gaji Pokok, Aksi), dipaginasi 10 baris per halaman.
    - Tombol "Tambah Karyawan" -> ke halaman karyawan.create
    - Ikon Edit di tiap baris   -> ke halaman Slip Gaji milik karyawan itu
    - Ikon Hapus di tiap baris  -> buka modal konfirmasi, lalu submit form DELETE
--}}
@extends('layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="app-shell">

    <header class="app-header">
        <div class="app-header__brand">
            <span>Slip Gaji Karyawan</span>
        </div>
        <div class="app-header__user">
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="app-header__logout" style="background:none;border:none;cursor:pointer;font-family:inherit;">Keluar</button>
            </form>
        </div>
    </header>

    <div class="app-body">

        {{-- Notifikasi setelah tambah/hapus karyawan (dikirim via session('status')) --}}
        @if (session('status'))
            <div class="alert-status alert-status--wide">{{ session('status') }}</div>
        @endif

        <div class="page-toolbar">
            <div>
                <h1 class="page-toolbar__title">Daftar Karyawan</h1>
                <p class="page-toolbar__subtitle">Kelola data karyawan dan buat slip gaji dari sini.</p>
            </div>
            <a href="{{ route('karyawan.create') }}" class="btn btn-primary">+ Tambah Karyawan</a>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Jabatan</th>
                        <th>No. Telepon</th>
                        <th>Email</th>
                        <th>Gaji Pokok</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($karyawans as $karyawan)
                        <tr>
                            <td>
                                <div class="employee-cell">
                                    <span class="table-avatar">{{ $karyawan->inisial() }}</span>
                                    <span>{{ $karyawan->nama }}</span>
                                </div>
                            </td>
                            <td>{{ $karyawan->nik }}</td>
                            <td>{{ $karyawan->jabatan }}</td>
                            <td>{{ $karyawan->no_telepon ?? '-' }}</td>
                            <td>{{ $karyawan->email ?? '-' }}</td>
                            <td>{{ $karyawan->formatGajiPokok() }}</td>
                            <td class="text-right">
                                <div class="action-icons">
                                    {{-- Klik Edit -> langsung ke halaman Slip Gaji karyawan ini --}}
                                    <a
                                        href="{{ route('slip-gaji.create-for', $karyawan) }}"
                                        class="icon-btn"
                                        title="Buat / Edit Slip Gaji"
                                    >&#9998;</a>

                                    {{-- Klik Hapus -> buka modal konfirmasi lewat JS (lihat karyawan.js) --}}
                                    <button
                                        type="button"
                                        class="icon-btn icon-btn--danger"
                                        title="Hapus Karyawan"
                                        data-delete-trigger
                                        data-delete-url="{{ route('karyawan.destroy', $karyawan) }}"
                                        data-delete-nama="{{ $karyawan->nama }}"
                                    >&#128465;</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center empty-state">
                                Belum ada data karyawan. Klik "Tambah Karyawan" untuk mulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Info jumlah data & halaman, mengikuti hasil pagination dari controller --}}
            <div class="table-footer">
                <span>Menampilkan {{ $karyawans->count() }} dari {{ $karyawans->total() }} data karyawan</span>
                <div class="table-footer__pages">
                    <span>Halaman {{ $karyawans->currentPage() }} dari {{ max($karyawans->lastPage(), 1) }}</span>
                    @if ($karyawans->hasPages())
                        <a
                            href="{{ $karyawans->previousPageUrl() }}"
                            class="table-footer__nav {{ $karyawans->onFirstPage() ? 'is-disabled' : '' }}"
                        >&larr;</a>
                        <a
                            href="{{ $karyawans->nextPageUrl() }}"
                            class="table-footer__nav {{ ! $karyawans->hasMorePages() ? 'is-disabled' : '' }}"
                        >&rarr;</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal konfirmasi hapus: 1 modal dipakai bersama untuk semua baris tabel.
     Isi (nama karyawan & action form) diisi ulang oleh JS saat ikon Hapus diklik. --}}
<div class="modal-overlay" id="modal-hapus" hidden>
    <div class="modal-box">
        <h2 class="modal-box__title">Hapus Karyawan</h2>
        <p class="modal-box__text">
            Yakin ingin menghapus data <strong id="modal-hapus-nama">ini</strong>?
            Tindakan ini tidak bisa dibatalkan.
        </p>
        <form method="POST" id="form-hapus-karyawan">
            @csrf
            @method('DELETE')
            <div class="modal-box__actions">
                <button type="button" class="btn btn-outline" data-delete-cancel>Batal</button>
                <button type="submit" class="btn btn-primary">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/karyawan.js') }}"></script>
@endpush
