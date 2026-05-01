{{-- Singleton confirm dialog. Dipasang sekali di layout, dipanggil
     via [data-nb-confirm] attribute di tombol/form. --}}
<div class="nb-confirm-overlay" id="nbConfirmOverlay" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="nb-confirm-card" id="nbConfirmCard" data-variant="danger" onclick="event.stopPropagation()">
        <button type="button" class="nb-confirm-close" id="nbConfirmClose" aria-label="Tutup">
            <span class="material-symbols-outlined" style="font-size:18px;">close</span>
        </button>
        <div class="nb-confirm-icon-wrap">
            <span class="material-symbols-outlined nb-confirm-icon" id="nbConfirmIcon">notifications_active</span>
        </div>
        <h3 class="nb-confirm-title" id="nbConfirmTitle">Konfirmasi</h3>
        <p class="nb-confirm-desc" id="nbConfirmDesc">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
        <div class="nb-confirm-actions">
            <button type="button" class="nb-confirm-btn-confirm" id="nbConfirmConfirm">Ya, Lanjutkan</button>
            <button type="button" class="nb-confirm-btn-cancel" id="nbConfirmCancel">Batal</button>
        </div>
    </div>
</div>
