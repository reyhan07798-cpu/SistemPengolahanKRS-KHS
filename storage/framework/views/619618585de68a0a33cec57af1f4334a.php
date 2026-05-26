<?php $__env->startSection('title', 'Detail KRS'); ?>
<?php $__env->startSection('page_title', 'Detail KRS'); ?>

<?php $__env->startSection('content'); ?>
<div class="nb-page-header">
    <div>
        <a href="<?php echo e(route('dosen.wali.krs-verifikasi')); ?>" class="nb-btn nb-btn-secondary nb-btn-sm mb-2">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span> Kembali
        </a>
        <h1 class="mt-2">Detail KRS Mahasiswa</h1>
        <p><?php echo e($krs->semester); ?> <?php echo e($krs->tahun_ajaran); ?>

           <?php if($isReadOnly): ?> <span class="nb-badge nb-badge-stable ml-2">🔒 Read Only</span> <?php endif; ?>
        </p>
    </div>
</div>

<div class="nb-card mb-6">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div><p class="nb-label">Nama</p><p class="font-bold"><?php echo e($krs->nama); ?></p></div>
        <div><p class="nb-label">NIM</p><p class="font-bold"><?php echo e($krs->nim); ?></p></div>
        <div><p class="nb-label">Kelas</p><p class="font-bold"><?php echo e($krs->kelas ?? '-'); ?></p></div>
        <div><p class="nb-label">Status KRS</p>
            <?php $badge = match(strtolower($krs->status)) {
                'disetujui'=>'nb-badge-success','ditolak'=>'nb-badge-danger',default=>'nb-badge-warning'
            }; ?>
            <span class="nb-badge <?php echo e($badge); ?>"><?php echo e(ucfirst($krs->status)); ?></span>
        </div>
        <div><p class="nb-label">Total SKS</p><p class="font-bold"><?php echo e($krs->total_sks); ?> SKS</p></div>
        <?php if($krs->catatan): ?>
        <div class="col-span-2 md:col-span-3">
            <p class="nb-label">Catatan / Alasan</p>
            <p class="text-sm text-red-600 bg-red-50 rounded p-2"><?php echo e($krs->catatan); ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="nb-card-flat">
    <div class="nb-section-header"><h2>Mata Kuliah yang Diambil</h2>
        <span class="nb-badge nb-badge-primary"><?php echo e(count($detailMK)); ?> MK</span>
    </div>
    <table class="nb-table">
        <thead><tr>
            <th>No</th><th>Kode MK</th><th>Nama Mata Kuliah</th>
            <th class="text-center">SKS</th><th>Dosen Pengampu</th><th>Kelas</th>
        </tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $detailMK; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td class="text-muted font-bold"><?php echo e($i+1); ?></td>
            <td><span class="nb-badge nb-badge-stable"><?php echo e($mk->kode_mk); ?></span></td>
            <td class="font-medium"><?php echo e($mk->nama); ?></td>
            <td class="text-center font-bold"><?php echo e($mk->sks); ?></td>
            <td class="text-sm text-muted"><?php echo e($mk->nama_dosen ?? '-'); ?></td>
            <td><?php echo e($mk->kelas_mk ?? '-'); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="6" class="text-center py-8 text-muted">Tidak ada detail MK</td></tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr style="background:var(--nb-surface-2);">
                <td colspan="3" class="text-right font-bold">Total SKS:</td>
                <td class="text-center font-extrabold text-primary"><?php echo e($detailMK->sum('sks')); ?></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php if(!$isReadOnly && strtolower($krs->status) === 'menunggu'): ?>
<div class="nb-card mt-6 flex gap-4 justify-end">
    <form method="POST" action="<?php echo e(route('dosen.wali.krs.approve', $krs->id)); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
        <button type="submit" class="nb-btn nb-btn-primary"
                onclick="return confirm('Setujui KRS ini?')">
            <span class="material-symbols-outlined" style="font-size:16px;">check_circle</span> Setujui KRS
        </button>
    </form>
    <form method="POST" action="<?php echo e(route('dosen.wali.krs.reject', $krs->id)); ?>">
        <?php echo csrf_field(); ?>
        <div class="flex gap-2 items-center">
            <input type="text" name="catatan" placeholder="Alasan penolakan..."
                   required class="border rounded-lg px-3 py-2 text-sm" style="min-width:250px;">
            <button type="submit" class="nb-btn nb-btn-sm" style="background:#dc2626;color:#fff;">
                <span class="material-symbols-outlined" style="font-size:16px;">cancel</span> Tolak
            </button>
        </div>
    </form>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dosen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/pages/dosen_wali/krs-detail.blade.php ENDPATH**/ ?>