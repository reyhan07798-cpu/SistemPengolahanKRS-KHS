<?php $__env->startSection('title', 'Verifikasi KRS'); ?>
<?php $__env->startSection('page_title', 'Verifikasi KRS'); ?>

<?php $__env->startSection('content'); ?>
<div class="nb-page-header">
    <div>
        <span class="nb-eyebrow">Persetujuan</span>
        <h1 class="mt-2">Verifikasi KRS</h1>
        <p>Setujui atau tolak pengajuan KRS dari mahasiswa bimbingan Anda.</p>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="nb-alert nb-alert-success mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span> <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="nb-alert nb-alert-danger mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined">error</span> <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>


<div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="nb-stat nb-stat--warning nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">schedule</span></div><p class="nb-stat-label">Menunggu</p></div>
        <div class="nb-stat-value"><?php echo e($stats['menunggu']); ?></div>
    </div>
    <div class="nb-stat nb-stat--accent nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">check_circle</span></div><p class="nb-stat-label">Disetujui</p></div>
        <div class="nb-stat-value"><?php echo e($stats['disetujui']); ?></div>
    </div>
    <div class="nb-stat nb-stat--danger nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">cancel</span></div><p class="nb-stat-label">Ditolak</p></div>
        <div class="nb-stat-value"><?php echo e($stats['ditolak']); ?></div>
    </div>
</div>


<div class="nb-card mb-6">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-primary">filter_list</span>
        <h3 class="nb-h3">Filter</h3>
    </div>
    <form method="GET" action="<?php echo e(route('dosen.wali.krs-verifikasi')); ?>" id="filterForm">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="nb-label">Semester</label>
                <select name="semester_id" onchange="this.form.submit()">
                    <option value="">Semua Semester</option>
                    <?php $__currentLoopData = $allSem; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sem->id); ?>" <?php echo e($filterSemesterId == $sem->id ? 'selected' : ''); ?>>
                            <?php echo e($sem->semester); ?> <?php echo e($sem->tahun_ajaran); ?>

                            <?php if($sem->is_active): ?> ★ <?php endif; ?>
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="nb-label">Status</label>
                <select name="status" onchange="this.form.submit()">
                    <option value="semua" <?php echo e($filterStatus=='semua' ? 'selected' : ''); ?>>Semua Status</option>
                    <option value="menunggu"  <?php echo e($filterStatus=='menunggu'  ? 'selected' : ''); ?>>Menunggu</option>
                    <option value="disetujui" <?php echo e($filterStatus=='disetujui' ? 'selected' : ''); ?>>Disetujui</option>
                    <option value="ditolak"   <?php echo e($filterStatus=='ditolak'   ? 'selected' : ''); ?>>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="nb-label">Kelas</label>
                <select name="kelas" onchange="this.form.submit()">
                    <option value="semua">Semua Kelas</option>
                    <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kls); ?>" <?php echo e($filterKelas==$kls ? 'selected' : ''); ?>><?php echo e($kls); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex items-end">
                <?php if($isReadOnly): ?>
                    <span class="nb-badge nb-badge-stable w-full text-center py-2" style="font-size:13px;">
                        <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">lock</span>
                        Semester Tidak Aktif (Read Only)
                    </span>
                <?php else: ?>
                    <span class="nb-badge nb-badge-success w-full text-center py-2" style="font-size:13px;">
                        <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">check_circle</span>
                        Semester Aktif
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>


<div class="nb-card-flat">
    <div class="nb-section-header">
        <h2>Daftar Pengajuan KRS</h2>
        <span class="nb-badge nb-badge-primary"><?php echo e(count($daftarKrs)); ?> pengajuan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="nb-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mahasiswa</th>
                    <th>Kelas</th>
                    <th class="text-center">Jml MK</th>
                    <th class="text-center">Total SKS</th>
                    <th>Semester</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $daftarKrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $krs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $badge = match(strtolower($krs['status'])) {
                        'disetujui' => 'nb-badge-success',
                        'ditolak'   => 'nb-badge-danger',
                        default     => 'nb-badge-warning',
                    };
                    $canAct = $krs['is_active'] && !$isReadOnly && strtolower($krs['status']) === 'menunggu';
                ?>
                <tr>
                    <td class="font-bold text-muted"><?php echo e($i+1); ?></td>
                    <td>
                        <div class="font-bold text-sm text-ink"><?php echo e($krs['nama']); ?></div>
                        <div class="text-xs text-muted"><?php echo e($krs['nim']); ?></div>
                    </td>
                    <td><?php echo e($krs['kelas']); ?></td>
                    <td class="text-center"><?php echo e($krs['mk_count']); ?></td>
                    <td class="text-center font-bold"><?php echo e($krs['total_sks']); ?></td>
                    <td class="text-sm"><?php echo e($krs['semester']); ?> <?php echo e($krs['tahun_ajaran']); ?></td>
                    <td class="text-center"><span class="nb-badge <?php echo e($badge); ?>"><?php echo e($krs['status']); ?></span></td>
                    <td class="text-center text-sm text-muted"><?php echo e($krs['tanggal']); ?></td>
                    <td class="text-center">
                        <div class="flex gap-2 justify-center flex-wrap">
                            
                            <a href="<?php echo e(route('dosen.wali.krs.detail', $krs['krs_id'])); ?>"
                               class="nb-btn nb-btn-secondary nb-btn-sm"
                               title="Lihat Detail">
                                <span class="material-symbols-outlined" style="font-size:14px;">visibility</span>
                            </a>
                            <?php if($canAct): ?>
                            
                            <form method="POST" action="<?php echo e(route('dosen.wali.krs.approve', $krs['krs_id'])); ?>" style="display:inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="nb-btn nb-btn-primary nb-btn-sm"
                                        onclick="return confirm('Setujui KRS <?php echo e($krs["nama"]); ?>?')"
                                        title="Setujui">
                                    <span class="material-symbols-outlined" style="font-size:14px;">check</span>
                                </button>
                            </form>
                            
                            <button type="button" class="nb-btn nb-btn-sm"
                                    style="background:#fee2e2;color:#991b1b;"
                                    onclick="showTolakModal(<?php echo e($krs['krs_id']); ?>, '<?php echo e($krs['nama']); ?>')"
                                    title="Tolak">
                                <span class="material-symbols-outlined" style="font-size:14px;">close</span>
                            </button>
                            <?php elseif(!$krs['is_active'] && strtolower($krs['status']) === 'menunggu'): ?>
                            <span class="text-xs text-muted">🔒 Terkunci</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="9" class="text-center py-12 text-muted">
                    <span class="material-symbols-outlined" style="font-size:48px;">inbox</span>
                    <p class="mt-2">Tidak ada data KRS sesuai filter.</p>
                </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div id="modalTolak" class="fixed inset-0 z-50 hidden" style="background:rgba(0,0,0,0.5);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="nb-card w-full max-w-md">
            <h3 class="nb-h3 mb-4">Tolak KRS</h3>
            <p class="text-muted mb-4" id="modalTolakNama">—</p>
            <form id="formTolak" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="nb-label">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="catatan" rows="4" required
                              placeholder="Tuliskan alasan penolakan KRS..."
                              class="w-full border border-gray-300 rounded-lg p-3 text-sm"></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="hideTolakModal()" class="nb-btn nb-btn-secondary">Batal</button>
                    <button type="submit" class="nb-btn nb-btn-primary" style="background:#dc2626;">Tolak KRS</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function showTolakModal(krsId, nama) {
    document.getElementById('modalTolakNama').textContent = 'Tolak KRS: ' + nama;
    document.getElementById('formTolak').action = '/dosen/wali/krs/' + krsId + '/reject';
    document.getElementById('modalTolak').classList.remove('hidden');
}
function hideTolakModal() {
    document.getElementById('modalTolak').classList.add('hidden');
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dosen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS\resources\views/pages/dosen_wali/krs-verifikasi.blade.php ENDPATH**/ ?>