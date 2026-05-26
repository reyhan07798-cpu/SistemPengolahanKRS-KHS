<?php $__env->startSection('title', 'Kelola Paket Matakuliah'); ?>
<?php $__env->startSection('page_title', 'Paket Mata Kuliah'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Paket Mata Kuliah</h1>
            <p>Kelola paket mata kuliah yang dibundle per semester & program studi.</p>
        </div>
        <button type="button" onclick="openModal()" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">add</span>
            Tambah Paket
        </button>
    </div>

    <?php if(session('success')): ?>
        <div class="nb-alert nb-alert-success mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="nb-alert nb-alert-danger mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Bundle Kurikulum</span>
                <h2 class="mt-1">Daftar Paket</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th class="text-center">Semester</th>
                        <th class="hidden md:table-cell">Prodi</th>
                        <th class="text-center">Total SKS</th>
                        <th class="text-center">Jumlah MK</th>
                        <th class="hidden lg:table-cell">Deskripsi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <span class="material-symbols-outlined text-muted" style="font-size:48px;">inventory_2</span>
            <p class="mt-2 text-muted font-medium">Tidak ada data paket</p>
        </div>
    </div>

    
    <div id="modalOverlay" class="nb-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="nb-modal" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <h3 id="modal-title">Tambah Paket Baru</h3>
                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <form action="<?php echo e(route('pages.admin.paketmk.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="nb-modal-body">
                    <div class="space-y-5">
                        <div>
                            <label class="nb-label">Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" name="nama_paket" value="<?php echo e(old('nama_paket')); ?>" placeholder="Paket Normal Semester 3" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="nb-label">Semester <span class="text-danger">*</span></label>
                                <select name="semester" required>
                                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s); ?>" <?php echo e(old('semester') == $s ? 'selected' : ''); ?>>Semester <?php echo e($s); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div>
                                <label class="nb-label">Prodi</label>
                                <select name="prodi">
                                    <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p); ?>" <?php echo e(old('prodi') == $p ? 'selected' : ''); ?>><?php echo e($p); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="nb-label">Mata Kuliah dalam Paket <span class="text-danger">*</span></label>
                            <div class="nb-bordered-list max-h-60 overflow-y-auto space-y-1">
                                <?php $__currentLoopData = $allMataKuliah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-3 cursor-pointer hover:bg-accent-soft p-2 rounded-md transition-colors">
                                        <input type="checkbox" name="mata_kuliah[]" value="<?php echo e($mk->id); ?>" data-sks="<?php echo e($mk->sks); ?>" class="nb-no-style w-5 h-5 cursor-pointer mk-checkbox" style="accent-color: var(--color-accent);">
                                        <div class="flex-1 min-w-0">
                                            <span class="text-sm font-bold text-ink"><?php echo e($mk->nama); ?></span>
                                            <span class="text-xs text-muted ml-2">(<?php echo e($mk->sks); ?> SKS)</span>
                                        </div>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="flex justify-between items-center mt-3 px-1 flex-wrap gap-2">
                                <span class="nb-badge nb-badge-stable">
                                    Terpilih: <span id="selectedCount" class="ml-1">0</span> MK
                                </span>
                                <span class="nb-badge nb-badge-primary">
                                    Total: <span id="totalSks" class="ml-1">0</span> SKS
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="nb-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat paket..."><?php echo e(old('deskripsi')); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="nb-modal-footer">
                    <button type="button" onclick="closeModal()" class="nb-btn nb-btn-secondary nb-btn-sm">Batal</button>
                    <button type="submit" class="nb-btn nb-btn-primary nb-btn-sm">
                        <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                        Tambah Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const rawData = <?php echo json_encode($paketMK, 15, 512) ?>;
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');

    function openModal() {
        document.getElementById('modalOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        updateCounters();
    }
    function closeModal() {
        document.getElementById('modalOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    document.getElementById('modalOverlay')?.addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });

    <?php if(old('_token') || $errors->any()): ?>
        document.addEventListener('DOMContentLoaded', () => { openModal(); });
    <?php endif; ?>

    const checkboxes = document.querySelectorAll('.mk-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const totalSksSpan = document.getElementById('totalSks');

    function updateCounters() {
        let count = 0;
        let sks = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                count++;
                sks += parseInt(cb.dataset.sks);
            }
        });
        selectedCountSpan.textContent = count;
        totalSksSpan.textContent = sks;
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCounters);
    });

    function renderTable(data) {
        tableBody.innerHTML = '';
        if (data.length === 0) { emptyState.classList.remove('hidden'); return; }
        emptyState.classList.add('hidden');

        data.forEach(pk => {
            const row = document.createElement('tr');
            const editUrl = `/admin/paket-mk/${pk.id}/edit`;
            const deleteUrl = `/admin/paket-mk/${pk.id}`;

            row.innerHTML = `
                <td class="font-bold text-primary" style="font-family: var(--font-heading);">${pk.nama_paket}</td>
                <td class="text-center"><span class="nb-badge nb-badge-warning">Sem ${pk.semester}</span></td>
                <td class="hidden md:table-cell text-muted">${pk.prodi}</td>
                <td class="text-center">
                    <span class="font-extrabold text-ink" style="font-family: var(--font-heading);">${pk.total_sks}</span>
                    <span class="text-xs text-muted ml-1">SKS</span>
                </td>
                <td class="text-center">
                    <span class="font-extrabold text-ink" style="font-family: var(--font-heading);">${pk.jumlah_mk}</span>
                    <span class="text-xs text-muted ml-1">MK</span>
                </td>
                <td class="hidden lg:table-cell text-sm text-muted max-w-xs truncate">${pk.deskripsi || '-'}</td>
                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="${editUrl}" class="nb-row-action edit" title="Edit">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </a>
                        <form action="${deleteUrl}" method="POST" data-nb-confirm="true" data-nb-confirm-title="Hapus Paket Mata Kuliah?" data-nb-confirm-desc="Tindakan ini tidak dapat dibatalkan. Mahasiswa yang menggunakan paket ini perlu memilih paket lain." data-nb-confirm-button="Ya, Hapus" data-nb-confirm-icon="delete_forever" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="nb-row-action danger" title="Hapus">
                                <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/pages/admin/data_paketmk.blade.php ENDPATH**/ ?>