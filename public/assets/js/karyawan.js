/**
 * karyawan.js
 * Menangani modal konfirmasi hapus di halaman Daftar Karyawan (karyawan/index.blade.php).
 * Modal-nya dibuat dengan HTML + CSS biasa (bukan library Bootstrap JS), disembunyikan
 * lewat atribut `hidden` dan dimunculkan/ditutup dengan menambah/menghapus atribut itu.
 */
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('modal-hapus');
    var modalNamaEl = document.getElementById('modal-hapus-nama');
    var formHapus = document.getElementById('form-hapus-karyawan');

    if (!modal || !formHapus) {
        return;
    }

    /**
     * Tampilkan modal konfirmasi hapus.
     * Mengisi nama karyawan yang mau dihapus dan mengatur "action" form
     * sesuai tombol Hapus mana yang diklik (setiap baris tabel punya URL beda).
     *
     * @param {string} url   alamat route DELETE untuk karyawan yang dipilih
     * @param {string} nama  nama karyawan, ditampilkan di teks konfirmasi
     * @returns {void}
     */
    function bukaModal(url, nama) {
        formHapus.setAttribute('action', url);
        if (modalNamaEl) {
            modalNamaEl.textContent = nama || 'ini';
        }
        modal.removeAttribute('hidden');
    }

    /**
     * Sembunyikan/tutup modal konfirmasi hapus tanpa menghapus apa pun.
     *
     * @returns {void}
     */
    function tutupModal() {
        modal.setAttribute('hidden', 'hidden');
    }

    // Pasang event klik ke semua tombol ikon Hapus yang ada di tabel.
    document.querySelectorAll('[data-delete-trigger]').forEach(function (tombol) {
        tombol.addEventListener('click', function () {
            var url = tombol.getAttribute('data-delete-url');
            var nama = tombol.getAttribute('data-delete-nama');
            bukaModal(url, nama);
        });
    });

    // Tombol "Batal" di dalam modal.
    var tombolBatal = modal.querySelector('[data-delete-cancel]');
    if (tombolBatal) {
        tombolBatal.addEventListener('click', tutupModal);
    }

    // Klik di area gelap luar kotak modal juga menutup modal (perilaku umum modal).
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            tutupModal();
        }
    });

    // Tekan tombol Escape untuk menutup modal.
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hasAttribute('hidden')) {
            tutupModal();
        }
    });
});
