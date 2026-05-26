<?php $__env->startSection('title', 'Data Mata Kuliah'); ?>
<?php $__env->startSection('page_title', 'Data Mata Kuliah'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Data Mata Kuliah</h1>
            <p>Daftar mata kuliah yang tersedia di sistem.</p>
        </div>
        <button type="button" onclick="openModal()" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">add</span>
            Tambah Mata Kuliah
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

    
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">search</span>
            <h3 class="nb-h3">Filter & Pencarian</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="nb-label">Cari MK</label>
                <input type="text" id="searchInput" placeholder="Cari kode atau nama mata kuliah...">
            </div>
        </div>
    </div>

    

    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Kurikulum</span>
                <h2 class="mt-1">Daftar Mata Kuliah</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="hidden sm:table-cell">Semester</th>
                        <th class="hidden md:table-cell">Dosen Pengampu</th>
                        <th class="hidden lg:table-cell">Jadwal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <span class="material-symbols-outlined text-muted" style="font-size:48px;">menu_book</span>
            <p class="mt-2 text-muted font-medium">Tidak ada data mata kuliah</p>
        </div>
    </div>

    
    <div id="modalOverlay" class="nb-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="nb-modal" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <h3 id="modal-title">Tambah Mata Kuliah Baru</h3>
                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <form action="<?php echo e(route('pages.admin.matakuliah.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="nb-modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="md:col-span-2">
                            <label class="nb-label">Kode MK <span class="text-danger">*</span></label>
                            <input type="text" name="kode" value="<?php echo e(old('kode')); ?>" placeholder="IF101" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                            <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" placeholder="Pemrograman Web" required>
                        </div>

                        <div>
                            <label class="nb-label">SKS <span class="text-danger">*</span></label>
                            <select name="sks" required>
                                <option value="">Pilih SKS</option>
                                <option value="1" <?php echo e(old('sks') == '1' ? 'selected' : ''); ?>>1 SKS</option>
                                <option value="2" <?php echo e(old('sks') == '2' ? 'selected' : ''); ?>>2 SKS</option>
                                <option value="3" <?php echo e(old('sks') == '3' ? 'selected' : ''); ?>>3 SKS</option>
                                <option value="4" <?php echo e(old('sks') == '4' ? 'selected' : ''); ?>>4 SKS</option>
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" required>
                                <option value="">Pilih Semester</option>
                                <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s); ?>" <?php echo e(old('semester') == $s ? 'selected' : ''); ?>>Semester <?php echo e($s); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>



                        <div class="md:col-span-2">
                            <label class="nb-label">Dosen Pengampu <span class="text-danger">*</span></label>
                            <select name="dosen_pengampu" required>
                                <option value="">Pilih dosen pengampu</option>
                                <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dosen->nama); ?>" <?php echo e(old('dosen_pengampu') == $dosen->nama ? 'selected' : ''); ?>><?php echo e($dosen->nama); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Hari <span class="text-danger">*</span></label>
                            <select name="hari" required>
                                <option value="">Pilih Hari</option>
                                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($day); ?>" <?php echo e(old('hari') == $day ? 'selected' : ''); ?>><?php echo e($day); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Jam <span class="text-danger">*</span></label>
                            <input type="text" name="jam" value="<?php echo e(old('jam', '07:00 - 08:40')); ?>" placeholder="07:00 - 08:40" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Ruang <span class="text-danger">*</span></label>
                            <input type="text" name="ruang" value="<?php echo e(old('ruang')); ?>" placeholder="Lab Komputer 1" required>
                        </div>
                    </div>
                </div>

                <div class="nb-modal-footer">
                    <button type="button" onclick="closeModal()" class="nb-btn nb-btn-secondary nb-btn-sm">Batal</button>
                    <button type="submit" class="nb-btn nb-btn-primary nb-btn-sm">
                        <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const rawData = <?php echo json_encode($matakuliah, 15, 512) ?>;
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');

    function openModal() {
        document.getElementById('modalOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
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

    function renderTable(data) {
        tableBody.innerHTML = '';
        if (data.length === 0) { emptyState.classList.remove('hidden'); return; }
        emptyState.classList.add('hidden');

        data.forEach(mk => {
            const row = document.createElement('tr');
            const editUrl = `/admin/matakuliah/${mk.id}/edit`;
            const deleteUrl = `/admin/matakuliah/${mk.id}`;

            row.innerHTML = `
                <td class="font-bold text-primary" style="font-family: var(--font-heading);">${mk.kode}</td>
                <td class="font-medium text-ink">${mk.nama}</td>
                <td class="text-center"><span class="nb-badge nb-badge-primary">${mk.sks} SKS</span></td>
                <td class="hidden sm:table-cell text-muted">Semester ${mk.semester}</td>
                <td class="hidden md:table-cell text-muted">${mk.dosen_pengampu}</td>
                <td class="hidden lg:table-cell text-sm text-muted">${mk.jadwal}</td>
                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="${editUrl}" class="nb-row-action edit" title="Edit">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </a>
                        <form action="${deleteUrl}" method="POST" data-nb-confirm="true" data-nb-confirm-title="Hapus Mata Kuliah?" data-nb-confirm-desc="Tindakan ini tidak dapat dibatalkan. Mata kuliah ini akan hilang dari paket KRS." data-nb-confirm-button="Ya, Hapus" data-nb-confirm-icon="delete_forever" class="inline">
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

    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const filtered = rawData.filter(mk => 
            mk.kode.toLowerCase().includes(searchTerm) || 
            mk.nama.toLowerCase().includes(searchTerm)
        );
        renderTable(filtered);
    }

    document.getElementById('searchInput').addEventListener('keyup', applyFilters);

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO T14\Documents\SistemPengolahanKRS-KHS\resources\views/pages/admin/data_matakuliah.blade.php ENDPATH**/ ?>