import './bootstrap';

// Confirm Dialog — replace window.confirm() bawaan browser
// Pakai dengan data-attributes di tombol/form/link:
//   data-nb-confirm="true"
//   data-nb-confirm-title="Hapus Data Mahasiswa?"
//   data-nb-confirm-desc="Tindakan ini tidak dapat dibatalkan."
//   data-nb-confirm-button="Ya, Hapus"
//   data-nb-confirm-variant="danger" (danger | primary | warning)
//   data-nb-confirm-icon="delete"
(function () {
    const overlay = document.getElementById('nbConfirmOverlay');
    if (!overlay) return;

    const card = document.getElementById('nbConfirmCard');
    const titleEl = document.getElementById('nbConfirmTitle');
    const descEl = document.getElementById('nbConfirmDesc');
    const iconEl = document.getElementById('nbConfirmIcon');
    const confirmBtn = document.getElementById('nbConfirmConfirm');
    const cancelBtn = document.getElementById('nbConfirmCancel');
    const closeBtn = document.getElementById('nbConfirmClose');

    let pendingAction = null; // function untuk eksekusi saat user klik "Ya"

    function openDialog(opts) {
        titleEl.textContent = opts.title || 'Konfirmasi';
        descEl.textContent = opts.desc || 'Apakah Anda yakin ingin melanjutkan?';
        iconEl.textContent = opts.icon || 'notifications_active';
        confirmBtn.textContent = opts.button || 'Ya, Lanjutkan';
        card.setAttribute('data-variant', opts.variant || 'danger');

        pendingAction = opts.onConfirm || null;

        overlay.classList.add('open');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeDialog() {
        overlay.classList.remove('open');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        pendingAction = null;
    }

    confirmBtn?.addEventListener('click', () => {
        const action = pendingAction;
        closeDialog();
        if (typeof action === 'function') action();
    });

    cancelBtn?.addEventListener('click', closeDialog);
    closeBtn?.addEventListener('click', closeDialog);
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeDialog();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && overlay.classList.contains('open')) closeDialog();
    });

    // Expose globally
    /** @type {any} */ (window).nbConfirm = openDialog;

    // Auto-attach to elements with data-nb-confirm
    function readOpts(el) {
        return {
            title: el.dataset.nbConfirmTitle,
            desc: el.dataset.nbConfirmDesc,
            button: el.dataset.nbConfirmButton,
            variant: el.dataset.nbConfirmVariant || 'danger',
            icon: el.dataset.nbConfirmIcon,
        };
    }

    // Form submit interceptor
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (form.dataset.nbConfirm !== 'true') return;
        if (form.dataset.nbConfirmConfirmed === '1') {
            // sudah confirmed, lepas
            form.dataset.nbConfirmConfirmed = '';
            return;
        }
        e.preventDefault();
        const opts = readOpts(form);
        opts.onConfirm = () => {
            form.dataset.nbConfirmConfirmed = '1';
            form.submit();
        };
        openDialog(opts);
    }, true);

    // Link click interceptor (anchor with data-nb-confirm)
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-nb-confirm="true"]');
        if (!trigger) return;
        if (trigger.tagName === 'FORM') return; // dihandle submit listener
        // Skip jika tombol di dalam form (form sudah handle)
        if (trigger.tagName === 'BUTTON' && trigger.closest('form[data-nb-confirm="true"]')) return;

        e.preventDefault();
        const opts = readOpts(trigger);
        opts.onConfirm = () => {
            if (trigger.tagName === 'A' && trigger.href) {
                window.location.href = trigger.href;
            } else if (trigger.tagName === 'BUTTON' && trigger.form) {
                trigger.form.dataset.nbConfirmConfirmed = '1';
                trigger.form.submit();
            }
        };
        openDialog(opts);
    });
})();

// Toast Notification System
(function () {
    const container = document.getElementById('nbToastContainer');
    if (!container) return;

    /**
     * Tampilkan toast notification
     * @param {string} message - Pesan notifikasi
     * @param {string} type - 'success' | 'error' | 'warning' | 'info'
     * @param {number} duration - Durasi tampil (ms), default 4000
     */
    window.nbToast = function (message, type = 'info', duration = 4000) {
        const icons = {
            success: 'check_circle',
            error: 'error',
            warning: 'warning',
            info: 'info',
        };
        const colors = {
            success: 'text-green-600',
            error: 'text-red-600',
            warning: 'text-yellow-600',
            info: 'text-blue-600',
        };
        const bgColors = {
            success: 'bg-green-50',
            error: 'bg-red-50',
            warning: 'bg-yellow-50',
            info: 'bg-blue-50',
        };

        const toast = document.createElement('div');
        toast.className = `flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg pointer-events-auto ${bgColors[type] || 'bg-blue-50'} border border-gray-200`;
        toast.style.animation = 'slideIn 0.3s ease-out';
        toast.innerHTML = `
            <span class="material-symbols-outlined ${colors[type] || 'text-blue-600'}">
                ${icons[type] || 'info'}
            </span>
            <span class="text-sm font-medium text-gray-900">${message}</span>
            <button type="button" class="ml-auto text-gray-400 hover:text-gray-600" onclick="this.parentElement.remove()">
                <span class="material-symbols-outlined" style="font-size:18px;">close</span>
            </button>
        `;

        container.appendChild(toast);

        if (duration > 0) {
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-out forwards';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    };

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%) translateY(0);
            }
            to {
                opacity: 1;
                transform: translateX(0) translateY(0);
            }
        }
        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0) translateY(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%) translateY(0);
            }
        }
    `;
    document.head.appendChild(style);

    // Auto-display session messages as toast
    window.nbShowSessionMessages = function () {
        const successEl = document.querySelector('[data-session-success]');
        const errorEl = document.querySelector('[data-session-error]');

        if (successEl) {
            const message = successEl.dataset.sessionSuccess;
            if (message) {
                setTimeout(() => nbToast(message, 'success'), 100);
            }
        }

        if (errorEl) {
            const message = errorEl.dataset.sessionError;
            if (message) {
                setTimeout(() => nbToast(message, 'error'), 100);
            }
        }
    };

    // Run on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', nbShowSessionMessages);
})();

/**
 * Global delete function untuk semua halaman admin
 * @param {string} url - URL endpoint untuk delete
 * @param {string} title - Judul konfirmasi (e.g., "Hapus Mata Kuliah?")
 * @param {string} description - Deskripsi konfirmasi
 * @param {string} itemName - Nama item yang akan dihapus (untuk ditampilkan di konfirmasi)
 */
window.deleteData = function (url, title, description, itemName) {
    nbConfirm({
        title: title,
        desc: description.replace('{itemName}', itemName || 'item ini'),
        button: 'Ya, Hapus',
        variant: 'danger',
        icon: 'delete_forever',
        onConfirm: () => {
            // Create and submit form
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
};



