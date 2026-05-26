<?php $__env->startSection('title', 'Data Dosen'); ?>
<?php $__env->startSection('page_title', 'Data Dosen'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Data Dosen</h1>
            <p>Kelola data dosen pengajar dan dosen wali.</p>
        </div>
        <button type="button" onclick="openModal()" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">add</span>
            Tambah Dosen
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

    

    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">supervisor_account</span>
                </div>
                <p class="nb-stat-label">Dosen Wali</p>
            </div>
            <div class="nb-stat-value" id="countWali">0</div>
        </div>

        <div class="nb-stat nb-stat--primary nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">co_present</span>
                </div>
                <p class="nb-stat-label">Dosen Mata Kuliah</p>
            </div>
            <div class="nb-stat-value" id="countMK">0</div>
        </div>
    </div>

    
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">search</span>
            <h3 class="nb-h3">Filter Dosen</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <div>
                <label class="nb-label">Program Studi</label>
                <select id="filterProdi">
                    <option value="">Semua Prodi</option>
                    <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($prodi); ?>"><?php echo e($prodi); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>

    

    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Dosen Aktif</span>
                <h2 class="mt-1">Daftar Dosen</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th class="hidden md:table-cell">Email</th>
                        <th class="hidden sm:table-cell text-center">Tipe Dosen</th>
                        <th class="hidden lg:table-cell">Program Studi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <span class="material-symbols-outlined text-muted" style="font-size:48px;">badge</span>
            <p class="mt-2 text-muted font-medium">Tidak ada data dosen</p>
        </div>
    </div>

    
    <div id="modalOverlay" class="nb-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="nb-modal" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <h3 id="modal-title">Tambah Dosen Baru</h3>
                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <form action="<?php echo e(route('pages.admin.dosen.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="nb-modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <label class="nb-label">NIP / NIK <span class="text-danger">*</span></label>
                            <input type="text" name="nik" value="<?php echo e(old('nik')); ?>" placeholder="198501012020011001" required>
                            <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="nb-form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label class="nb-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" placeholder="Nama lengkap" required>
                            <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="nb-form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="email@univ.ac.id" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="nb-form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label class="nb-label">Peran Dosen <span class="text-danger">*</span></label>
                            <select name="tipe_dosen" required>
                                <option value="">Pilih peran dosen</option>
                                <option value="Dosen Wali" <?php echo e(old('tipe_dosen') == 'Dosen Wali' ? 'selected' : ''); ?>>Dosen Wali & Matakuliah</option>
                                <option value="Dosen Mata Kuliah" <?php echo e(old('tipe_dosen') == 'Dosen Mata Kuliah' ? 'selected' : ''); ?>>Dosen Mata Kuliah</option>
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Program Studi    <span class="text-danger">*</span></label>
                            <select name="fakultas" required>
                                <option value="">Pilih Prodi</option>
<?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prodi); ?>" <?php echo e(old('fakultas') == $prodi ? 'selected' : ''); ?>><?php echo e($prodi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Alamat</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap"><?php echo e(old('alamat')); ?></textarea>
                        </div>

                        <div>
                            <label class="nb-label">Password Default <span class="text-danger">*</span></label>
                            <input type="text" name="password" value="<?php echo e(old('password', 'dosen123')); ?>" placeholder="dosen123" required>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="nb-form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
const rawData = <?php echo json_encode($dosen, 15, 512) ?>;
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const countWaliSpan = document.getElementById('countWali');
    const countMKSpan = document.getElementById('countMK');
    const filterProdiSelect = document.getElementById('filterProdi');

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

        let waliCount = 0;
        let mkCount = 0;

        data.forEach(dsn => {
            if (dsn.tipe_dosen === 'Dosen Wali') waliCount++; else mkCount++;

            const row = document.createElement('tr');
            const editUrl = `/admin/dosen/${dsn.id}/edit`;
            const deleteUrl = `/admin/dosen/${dsn.id}`;
            const initials = dsn.nama.split(' ').map(n => n[0]).join('').substring(0, 2);
            const badgeClass = dsn.tipe_dosen === 'Dosen Wali' ? 'nb-badge-success' : 'nb-badge-primary';

            row.innerHTML = `
                <td class="font-bold text-primary" style="font-family: var(--font-heading);">${dsn.nik}</td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-accent-soft border-2 border-ink flex items-center justify-center flex-shrink-0">
                            <span class="text-ink font-extrabold text-xs" style="font-family: var(--font-heading);">${initials}</span>
                        </div>
                        <span class="font-medium text-ink">${dsn.nama}</span>
                    </div>
                </td>
                <td class="hidden md:table-cell text-sm text-primary">${dsn.email}</td>
                <td class="hidden sm:table-cell text-center"><span class="nb-badge ${badgeClass}">${dsn.tipe_dosen}</span></td>
                <td class="hidden lg:table-cell text-muted">${dsn.fakultas}</td>
                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="${editUrl}" class="nb-row-action edit" title="Edit">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </a>
                        <form action="${deleteUrl}" method="POST" data-nb-confirm="true" data-nb-confirm-title="Hapus Data Dosen?" data-nb-confirm-desc="Tindakan ini tidak dapat dibatalkan. Data dosen akan dihapus permanen." data-nb-confirm-button="Ya, Hapus" data-nb-confirm-icon="delete_forever" class="inline">
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

        countWaliSpan.textContent = waliCount;
        countMKSpan.textContent = mkCount;
    }

function filterTable() {
        const prodi = document.getElementById('filterProdi').value;
        const filtered = rawData.filter(dsn => !prodi || dsn.fakultas === prodi || dsn.prodi === prodi);
        renderTable(filtered);
    }

    document.getElementById('filterProdi').addEventListener('change', filterTable);

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/pages/admin/data_dosen.blade.php ENDPATH**/ ?>