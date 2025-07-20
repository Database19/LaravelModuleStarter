// File: resources/js/custom/loading-overlay.js

document.addEventListener('DOMContentLoaded', function() {
    const loadingOverlay = document.getElementById('loading-overlay');

    // Fungsi untuk menampilkan loader
    const showLoader = () => {
        if (loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
        }
    };

    // Fungsi untuk menyembunyikan loader
    const hideLoader = () => {
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    };

    // --- PERBAIKAN UTAMA ---
    // 1. Buat fungsi ini bisa diakses secara global dari mana saja
    window.showLoader = showLoader;
    window.hideLoader = hideLoader;

    // 2. Modifikasi event listener untuk form
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            // JANGAN tampilkan loader jika form ini ditandai untuk AJAX
            if (form.hasAttribute('data-ajax-form')) {
                return;
            }
            showLoader();
        });
    });
    // --- AKHIR PERBAIKAN ---

    // Event listener untuk link 'a' bisa dibiarkan seperti semula
    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', function(event) {
            if (link.target === '_blank' || link.getAttribute('href').startsWith('#') || link.getAttribute('href') === 'javascript:void(0);') {
                return;
            }
            showLoader();
        });
    });

    // Sembunyikan loader saat halaman selesai dimuat
    window.addEventListener('pageshow', hideLoader);
});
