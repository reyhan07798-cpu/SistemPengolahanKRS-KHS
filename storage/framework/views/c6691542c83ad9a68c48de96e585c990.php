<div id="confirmDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="nb-card p-6 max-w-md w-full mx-4">
    <div class="flex items-center gap-3 mb-6">
      <span class="material-symbols-outlined text-warning text-2xl">warning</span>
      <h3 class="nb-h3">Konfirmasi</h3>
    </div>
    <p class="text-muted mb-6" id="confirmMessage"></p>
    <div class="flex gap-3 justify-end">
      <button id="confirmCancel" class="nb-btn nb-btn-secondary">Batal</button>
      <button id="confirmOk" class="nb-btn nb-btn-danger">Hapus</button>
    </div>
  </div>
</div>

<script>
  function showConfirm(message, callback) {
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmDialog').classList.remove('hidden');
    document.getElementById('confirmOk').onclick = callback;
  }
  
  document.getElementById('confirmCancel').onclick = () => {
    document.getElementById('confirmDialog').classList.add('hidden');
  };
</script>

<?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/components/confirm-dialog.blade.php ENDPATH**/ ?>