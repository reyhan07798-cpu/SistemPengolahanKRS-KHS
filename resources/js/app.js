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
