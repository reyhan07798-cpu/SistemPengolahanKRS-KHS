<?php $__env->startSection('title', 'Data Mahasiswa'); ?>
<?php $__env->startSection('page_title', 'Data Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Data Mahasiswa</h1>
            <p>Kelola data mahasiswa terdaftar di sistem akademik.</p>
        </div>
        <button type="button" onclick="openModal()" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">add</span>
            Tambah Mahasiswa
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
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter & Pencarian</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="nb-label">Cari Mahasiswa</label>
                <input type="text" id="searchInput" placeholder="Cari NIM atau Nama...">
            </div>
            <div>
                <label class="nb-label">Prodi</label>
                <select id="filterProdi">
                    <option value="">Semua Prodi</option>
                    <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($prodi); ?>"><?php echo e($prodi); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="nb-label">Angkatan</label>
                <select id="filterAngkatan">
                    <option value="">Semua Angkatan</option>
                    <?php $__currentLoopData = $angkatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $angkatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($angkatan); ?>"><?php echo e($angkatan); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>

    
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Daftar Aktif</span>
                <h2 class="mt-1">Mahasiswa Terdaftar</h2>
            </div>
            <span class="nb-badge nb-badge-primary">Total: <span id="totalData">0</span></span>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th class="hidden md:table-cell">Prodi</th>
                        <th class="hidden lg:table-cell text-center">Kelas</th>
                        <th class="hidden sm:table-cell text-center">Angkatan</th>
                        <th class="hidden xl:table-cell">Dosen Wali</th>
                        <th class="hidden lg:table-cell">Email</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <span class="material-symbols-outlined text-muted" style="font-size:48px;">inbox</span>
            <p class="mt-2 text-muted font-medium">Tidak ada data mahasiswa</p>
        </div>
    </div>

    
    <div id="modalOverlay" class="nb-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="nb-modal" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <h3 id="modal-title">Tambah Mahasiswa Baru</h3>
                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <form action="<?php echo e(route('pages.admin.mahasiswa.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="nb-modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <label class="nb-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" name="nim" value="<?php echo e(old('nim')); ?>" placeholder="2021001001" required>
                            <?php $__errorArgs = ['nim'];
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
                            <label class="nb-label">Program Studi <span class="text-danger">*</span></label>
                            <select name="prodi" required>
                                <option value="">Pilih prodi</option>
                                <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prodi); ?>" <?php echo e(old('prodi') == $prodi ? 'selected' : ''); ?>><?php echo e($prodi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Angkatan <span class="text-danger">*</span></label>
                            <select name="angkatan" required>
                                <option value="">Pilih Angkatan</option>
                                <?php $__currentLoopData = $angkatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $angkatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($angkatan); ?>" <?php echo e(old('angkatan') == $angkatan ? 'selected' : ''); ?>><?php echo e($angkatan); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas" required>
                                <option value="">Pilih kelas</option>
                                <option value="A" <?php echo e(old('kelas') == 'A' ? 'selected' : ''); ?>>Kelas A</option>
                                <option value="B" <?php echo e(old('kelas') == 'B' ? 'selected' : ''); ?>>Kelas B</option>
                                <option value="C" <?php echo e(old('kelas') == 'C' ? 'selected' : ''); ?>>Kelas C</option>
                                <option value="D" <?php echo e(old('kelas') == 'D' ? 'selected' : ''); ?>>Kelas D</option>
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Dosen Wali <span class="text-danger">*</span></label>
                            <select name="dosen_wali" required>
                                <option value="">Pilih dosen wali</option>
                                <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dosen->nama); ?>" <?php echo e(old('dosen_wali') == $dosen->nama ? 'selected' : ''); ?>><?php echo e($dosen->nama); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">No. HP</label>
                            <input type="text" name="no_hp" value="<?php echo e(old('no_hp')); ?>" placeholder="081234567890">
                        </div>

                        <div>
                            <label class="nb-label">Password Default <span class="text-danger">*</span></label>
                            <input type="text" name="password" value="<?php echo e(old('password', 'mhs123')); ?>" placeholder="mhs123" required>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="nb-form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Alamat</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap"><?php echo e(old('alamat')); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="nb-modal-footer">
                    <button type="button" onclick="closeModal()" class="nb-btn nb-btn-secondary nb-btn-sm">
                        Batal
                    </button>
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
    const rawData = <?php echo json_encode($mahasiswa, 15, 512) ?>;
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const totalDataSpan = document.getElementById('totalData');

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
        if (data.length === 0) {
            emptyState.classList.remove('hidden');
            totalDataSpan.textContent = '0';
            return;
        }
        emptyState.classList.add('hidden');
        totalDataSpan.textContent = data.length;

        data.forEach(mhs => {
            const row = document.createElement('tr');
            const editUrl = `/admin/mahasiswa/${mhs.id}/edit`;
            const deleteUrl = `/admin/mahasiswa/${mhs.id}`;
            const initials = mhs.nama.split(' ').map(n => n[0]).join('').substring(0, 2);

            row.innerHTML = `
                <td class="font-bold text-primary" style="font-family: var(--font-heading);">${mhs.nim}</td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary-soft border-2 border-ink flex items-center justify-center flex-shrink-0">
                            <span class="text-primary font-extrabold text-xs" style="font-family: var(--font-heading);">${initials}</span>
                        </div>
                        <span class="font-medium text-ink">${mhs.nama}</span>
                    </div>
                </td>
                <td class="hidden md:table-cell text-muted">${mhs.prodi}</td>
                <td class="hidden lg:table-cell text-center"><span class="nb-badge nb-badge-stable">${mhs.kelas}</span></td>
                <td class="hidden sm:table-cell text-center text-muted">${mhs.angkatan}</td>
                <td class="hidden xl:table-cell text-muted text-sm">${mhs.dosen_wali}</td>
                <td class="hidden lg:table-cell text-sm text-primary">${mhs.email}</td>
                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="${editUrl}" class="nb-row-action edit" title="Edit">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </a>
                        <form action="${deleteUrl}" method="POST" data-nb-confirm="true" data-nb-confirm-title="Hapus Data Mahasiswa?" data-nb-confirm-desc="Tindakan ini tidak dapat dibatalkan. Data mahasiswa beserta riwayat KRS/KHS-nya akan dihapus permanen." data-nb-confirm-button="Ya, Hapus" data-nb-confirm-icon="delete_forever" class="inline">
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
        const prodiFilter = document.getElementById('filterProdi').value;
        const angkatanFilter = document.getElementById('filterAngkatan').value;
        const filtered = rawData.filter(mhs => {
            const matchSearch = mhs.nim.toLowerCase().includes(searchTerm) || mhs.nama.toLowerCase().includes(searchTerm);
            const matchProdi = !prodiFilter || mhs.prodi === prodiFilter;
            const matchAngkatan = !angkatanFilter || mhs.angkatan === angkatanFilter;
            return matchSearch && matchProdi && matchAngkatan;
        });
        renderTable(filtered);
    }

    document.getElementById('searchInput').addEventListener('keyup', applyFilters);
    document.getElementById('filterProdi').addEventListener('change', applyFilters);
    document.getElementById('filterAngkatan').addEventListener('change', applyFilters);

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO T14\Documents\SistemPengolahanKRS-KHS\resources\views/pages/admin/data_mahasiswa.blade.php ENDPATH**/ ?>