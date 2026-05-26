<?php $__env->startSection('title', 'Beranda Dosen Wali'); ?>
<?php $__env->startSection('page_title', 'Beranda'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal87eb4a3d447b3bc07eceb7287f2fe97e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87eb4a3d447b3bc07eceb7287f2fe97e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.hero-header-dosen','data' => ['eyebrow' => 'Dosen Wali','description' => 'Pantau dan kelola KRS serta hasil studi mahasiswa bimbingan Anda.','buttons' => [
        ['label' => 'Verifikasi KRS', 'route' => route('dosen.wali.krs-verifikasi'), 'icon' => 'fact_check'],
        ['label' => 'Lihat KHS',      'route' => route('dosen.wali.khs'),             'icon' => 'analytics', 'variant' => 'nb-btn-secondary']
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('hero-header-dosen'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Dosen Wali','description' => 'Pantau dan kelola KRS serta hasil studi mahasiswa bimbingan Anda.','buttons' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Verifikasi KRS', 'route' => route('dosen.wali.krs-verifikasi'), 'icon' => 'fact_check'],
        ['label' => 'Lihat KHS',      'route' => route('dosen.wali.khs'),             'icon' => 'analytics', 'variant' => 'nb-btn-secondary']
    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87eb4a3d447b3bc07eceb7287f2fe97e)): ?>
<?php $attributes = $__attributesOriginal87eb4a3d447b3bc07eceb7287f2fe97e; ?>
<?php unset($__attributesOriginal87eb4a3d447b3bc07eceb7287f2fe97e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87eb4a3d447b3bc07eceb7287f2fe97e)): ?>
<?php $component = $__componentOriginal87eb4a3d447b3bc07eceb7287f2fe97e; ?>
<?php unset($__componentOriginal87eb4a3d447b3bc07eceb7287f2fe97e); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginal34cf4563db740e503dc5bc01df1692b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal34cf4563db740e503dc5bc01df1692b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-bento','data' => ['stats' => $stats,'config' => [
        'mahasiswa_bimbingan' => ['color' => 'nb-stat--info',    'icon' => 'groups',          'label' => 'Mahasiswa Bimbingan'],
        'krs_menunggu'        => ['color' => 'nb-stat--warning', 'icon' => 'pending_actions',  'label' => 'KRS Menunggu'],
        'krs_disetujui'       => ['color' => 'nb-stat--accent',  'icon' => 'check_circle',     'label' => 'KRS Disetujui'],
        'krs_ditolak'         => ['color' => 'nb-stat--danger',  'icon' => 'cancel',           'label' => 'KRS Ditolak'],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-bento'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['stats' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats),'config' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        'mahasiswa_bimbingan' => ['color' => 'nb-stat--info',    'icon' => 'groups',          'label' => 'Mahasiswa Bimbingan'],
        'krs_menunggu'        => ['color' => 'nb-stat--warning', 'icon' => 'pending_actions',  'label' => 'KRS Menunggu'],
        'krs_disetujui'       => ['color' => 'nb-stat--accent',  'icon' => 'check_circle',     'label' => 'KRS Disetujui'],
        'krs_ditolak'         => ['color' => 'nb-stat--danger',  'icon' => 'cancel',           'label' => 'KRS Ditolak'],
    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal34cf4563db740e503dc5bc01df1692b2)): ?>
<?php $attributes = $__attributesOriginal34cf4563db740e503dc5bc01df1692b2; ?>
<?php unset($__attributesOriginal34cf4563db740e503dc5bc01df1692b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal34cf4563db740e503dc5bc01df1692b2)): ?>
<?php $component = $__componentOriginal34cf4563db740e503dc5bc01df1692b2; ?>
<?php unset($__componentOriginal34cf4563db740e503dc5bc01df1692b2); ?>
<?php endif; ?>

<div class="nb-card">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div>
            <span class="nb-eyebrow">Bimbingan</span>
            <h3 class="nb-h3 mt-1">Mahasiswa Bimbingan</h3>
        </div>
        <a href="<?php echo e(route('dosen.wali.krs-verifikasi')); ?>" class="nb-btn nb-btn-primary nb-btn-sm">
            <span class="material-symbols-outlined" style="font-size:16px;">fact_check</span> Verifikasi KRS
        </a>
    </div>

    <?php if(count($mahasiswaList) > 0): ?>
    <div class="overflow-x-auto">
        <table class="nb-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM / Nama</th>
                    <th>Kelas</th>
                    <th class="text-center">IPK</th>
                    <th class="text-center">Status KRS</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $mahasiswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $krsBadge = match(strtolower($m['status_krs'])) {
                    'disetujui'         => 'nb-badge-success',
                    'ditolak'           => 'nb-badge-danger',
                    'menunggu'          => 'nb-badge-warning',
                    'belum mengajukan'  => 'nb-badge-stable',
                    default             => 'nb-badge-stable',
                };
                $ipkClass = $m['ipk'] >= 3.5 ? 'text-accent' : ($m['ipk'] >= 2.5 ? 'text-primary' : 'text-muted');
            ?>
            <tr>
                <td class="font-bold text-muted"><?php echo e($i+1); ?></td>
                <td>
                    <div class="font-bold text-sm text-ink"><?php echo e($m['nama']); ?></div>
                    <div class="text-xs text-muted"><?php echo e($m['nim']); ?></div>
                </td>
                <td><?php echo e($m['kelas']); ?></td>
                <td class="text-center">
                    <span class="font-extrabold text-lg <?php echo e($ipkClass); ?>" style="font-family:var(--font-heading);">
                        <?php echo e(number_format($m['ipk'],2)); ?>

                    </span>
                </td>
                <td class="text-center">
                    <span class="nb-badge <?php echo e($krsBadge); ?>"><?php echo e($m['status_krs']); ?></span>
                </td>
                <td class="text-center">
                    <a href="<?php echo e(route('dosen.wali.khs')); ?>?kelas=<?php echo e($m['kelas']); ?>"
                       class="nb-btn nb-btn-secondary nb-btn-sm" title="Lihat KHS">
                        <span class="material-symbols-outlined" style="font-size:14px;">analytics</span>
                    </a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-10 text-muted">
        <span class="material-symbols-outlined" style="font-size:56px;">groups</span>
        <p class="mt-3 font-medium">Belum ada mahasiswa bimbingan.</p>
        <p class="text-sm mt-1">Hubungi admin untuk menetapkan mahasiswa bimbingan.</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dosen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS\resources\views/pages/dosen_wali/beranda.blade.php ENDPATH**/ ?>