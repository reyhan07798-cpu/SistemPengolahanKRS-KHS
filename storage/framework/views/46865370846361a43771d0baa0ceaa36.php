<?php $__env->startSection('title', 'KHS Mahasiswa Bimbingan'); ?>
<?php $__env->startSection('page_title', 'KHS Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="nb-page-header">
    <div>
        <span class="nb-eyebrow">Akademik</span>
        <h1 class="mt-2">KHS Mahasiswa Bimbingan</h1>
        <p>Pantau hasil studi mahasiswa bimbingan Anda.
           <?php if($isReadOnly): ?> <span class="nb-badge nb-badge-stable ml-2">🔒 Read Only</span> <?php endif; ?>
        </p>
    </div>
</div>

<div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="nb-stat nb-stat--info nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">groups</span></div><p class="nb-stat-label">Total Mahasiswa</p></div>
        <div class="nb-stat-value"><?php echo e($totalMahasiswa); ?></div>
    </div>
    <div class="nb-stat nb-stat--accent nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">analytics</span></div><p class="nb-stat-label">Rata-rata IPK</p></div>
        <div class="nb-stat-value"><?php echo e(number_format($rataIpk,2)); ?></div>
    </div>
    <div class="nb-stat nb-stat--success nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">star</span></div><p class="nb-stat-label">IPK ≥ 3.5</p></div>
        <div class="nb-stat-value"><?php echo e($ipkTinggi); ?></div>
    </div>
</div>


<div class="nb-card mb-6">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-primary">filter_list</span>
        <h3 class="nb-h3">Filter</h3>
    </div>
    <form method="GET" action="<?php echo e(route('dosen.wali.khs')); ?>">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <label class="nb-label">Semester</label>
                <select name="semester_id" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = $allSem; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sem->id); ?>" <?php echo e($filterSemesterId == $sem->id ? 'selected' : ''); ?>>
                            <?php echo e($sem->semester); ?> <?php echo e($sem->tahun_ajaran); ?>

                            <?php if($sem->is_active): ?> ★ <?php endif; ?>
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="nb-label">Kelas</label>
                <select name="kelas" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kls); ?>" <?php echo e($filterKelas==$kls ? 'selected' : ''); ?>><?php echo e($kls); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex items-end">
                <?php if($isReadOnly): ?>
                    <span class="nb-badge nb-badge-stable w-full text-center py-2">🔒 Semester Tidak Aktif</span>
                <?php else: ?>
                    <span class="nb-badge nb-badge-success w-full text-center py-2">✓ Semester Aktif</span>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<div class="nb-card-flat">
    <div class="nb-section-header">
        <h2>Rekap Nilai Mahasiswa</h2>
        <span class="nb-badge nb-badge-primary"><?php echo e(count($mahasiswaList)); ?> mahasiswa</span>
    </div>
    <div class="overflow-x-auto">
        <table class="nb-table">
            <thead><tr>
                <th>Rank</th><th>NIM / Nama</th><th>Kelas</th>
                <th class="text-center">MK Lulus</th><th class="text-center">IP Semester</th>
                <th class="text-center">IPK Kumulatif</th>
            </tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $mahasiswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $ipkBadge = $mhs['ipk'] >= 3.5 ? 'nb-badge-success' : ($mhs['ipk'] >= 2.5 ? 'nb-badge-primary' : 'nb-badge-warning');
            ?>
            <tr>
                <td>
                    <?php if($mhs['ranking'] <= 3): ?>
                        <span class="nb-badge nb-badge-primary" style="font-family:var(--font-heading);">#<?php echo e($mhs['ranking']); ?></span>
                    <?php else: ?>
                        <span class="text-muted"><?php echo e($mhs['ranking']); ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="font-bold text-ink text-sm"><?php echo e($mhs['nama']); ?></div>
                    <div class="text-xs text-muted"><?php echo e($mhs['nim']); ?></div>
                </td>
                <td><?php echo e($mhs['kelas']); ?></td>
                <td class="text-center font-bold"><?php echo e($mhs['mk_lulus']); ?></td>
                <td class="text-center font-extrabold text-primary" style="font-family:var(--font-heading);">
                    <?php echo e(number_format($mhs['ip'],2)); ?>

                </td>
                <td class="text-center">
                    <span class="nb-badge <?php echo e($ipkBadge); ?> font-extrabold" style="font-family:var(--font-heading);font-size:14px;">
                        <?php echo e(number_format($mhs['ipk'],2)); ?>

                    </span>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center py-12 text-muted">
                <span class="material-symbols-outlined" style="font-size:48px;">school</span>
                <p class="mt-2">Belum ada data nilai mahasiswa bimbingan.</p>
            </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dosen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS\resources\views/pages/dosen_wali/khs.blade.php ENDPATH**/ ?>