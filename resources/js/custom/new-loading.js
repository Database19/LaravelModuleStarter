function showLoading(type = 'default') {
    const overlays = {
        'default': 'loading-overlay',
        'glass': 'loading-overlay-glass',
        'minimal': 'loading-overlay-minimal'
    };

    const overlay = document.getElementById(overlays[type] || overlays.default);
    if (overlay) {
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
}

function hideLoading() {
    const overlays = ['loading-overlay', 'loading-overlay-glass', 'loading-overlay-minimal'];
    overlays.forEach(id => {
        const overlay = document.getElementById(id);
        if (overlay) {
            overlay.classList.add('hidden');
        }
    });
    document.body.style.overflow = 'auto'; // Restore scrolling
}

// Auto-hide loading after specified time (optional)
function showLoadingWithTimeout(duration = 3000, type = 'default') {
    showLoading(type);
    setTimeout(() => {
        hideLoading();
    }, duration);
}
