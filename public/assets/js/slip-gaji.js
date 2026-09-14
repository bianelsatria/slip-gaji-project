/**
 * slip-gaji.js
 * Dipakai di halaman resources/views/slip-gaji/index.blade.php.
 * Menangani kalkulasi real-time (Total Penghasilan, Total Potongan, Gaji Bersih)
 * dan mengaktifkan tombol submit hanya setelah jawaban captcha diisi.
 * Semua nilai tetap divalidasi ulang di server (SlipGajiController@store)
 * karena JS di browser bisa saja dimatikan/dimanipulasi oleh user.
 */
document.addEventListener('DOMContentLoaded', function () {
    var gajiPokokInput = document.getElementById('gaji_pokok');
    var lemburInput = document.getElementById('lembur');
    var pinjamanInput = document.getElementById('pinjaman_karyawan');

    var totalPenghasilanEl = document.getElementById('total-penghasilan');
    var totalPotonganEl = document.getElementById('total-potongan');
    var gajiBersihEl = document.getElementById('gaji-bersih');
    var breakdownPenghasilanEl = document.getElementById('breakdown-penghasilan');
    var breakdownPotonganEl = document.getElementById('breakdown-potongan');

    var captchaInput = document.getElementById('jawaban_captcha');
    var submitButton = document.getElementById('btn-submit-slip');

    /**
     * Format angka menjadi format rupiah "Rp 1.234.000".
     * @param {number} angka
     * @returns {string}
     */
    function formatRupiah(angka) {
        var bulat = Math.round(angka || 0);
        return 'Rp ' + bulat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    /**
     * Ambil nilai numerik dari sebuah input, default 0 jika kosong/tidak valid.
     * @param {HTMLInputElement} input
     * @returns {number}
     */
    function ambilAngka(input) {
        var nilai = parseFloat(input && input.value ? input.value : 0);
        return isNaN(nilai) ? 0 : nilai;
    }

    /**
     * Hitung ulang Total Penghasilan, Total Potongan, dan Gaji Bersih,
     * lalu perbarui tampilan secara real-time.
     */
    function hitungUlang() {
        var gajiPokok = ambilAngka(gajiPokokInput);
        var lembur = ambilAngka(lemburInput);
        var pinjaman = ambilAngka(pinjamanInput);

        var totalPenghasilan = gajiPokok + lembur;
        var totalPotongan = pinjaman;
        var gajiBersih = totalPenghasilan - totalPotongan;

        if (totalPenghasilanEl) totalPenghasilanEl.textContent = formatRupiah(totalPenghasilan);
        if (totalPotonganEl) totalPotonganEl.textContent = formatRupiah(totalPotongan);
        if (gajiBersihEl) gajiBersihEl.textContent = formatRupiah(gajiBersih);
        if (breakdownPenghasilanEl) breakdownPenghasilanEl.textContent = 'Total Penghasilan: ' + formatRupiah(totalPenghasilan);
        if (breakdownPotonganEl) breakdownPotonganEl.textContent = '- Total Potongan: ' + formatRupiah(totalPotongan);
    }

    /**
     * Aktifkan tombol submit hanya jika kolom jawaban captcha sudah diisi.
     */
    function perbaruiStatusTombol() {
        if (!submitButton || !captchaInput) return;

        var terisi = captchaInput.value.trim().length > 0;
        submitButton.disabled = !terisi;
        submitButton.textContent = terisi ? 'Kirim Slip Gaji' : 'Kirim Slip Gaji (Selesaikan Captcha)';
    }

    [gajiPokokInput, lemburInput, pinjamanInput].forEach(function (input) {
        if (input) {
            input.addEventListener('input', hitungUlang);
        }
    });

    if (captchaInput) {
        captchaInput.addEventListener('input', perbaruiStatusTombol);
    }

    // Hitung & set status awal saat halaman dimuat.
    hitungUlang();
    perbaruiStatusTombol();
});
