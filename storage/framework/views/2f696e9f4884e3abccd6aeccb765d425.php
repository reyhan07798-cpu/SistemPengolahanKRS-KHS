<?php $__env->startSection('title', 'Beranda Dosen Matkul'); ?>
<?php $__env->startSection('page_title', 'Beranda'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal87eb4a3d447b3bc07eceb7287f2fe97e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87eb4a3d447b3bc07eceb7287f2fe97e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.hero-header-dosen','data' => ['eyebrow' => 'Dosen Mata Kuliah','description' => 'Kelola nilai dan pantau mahasiswa pada mata kuliah yang Anda ampu.','buttons' => [
        ['label' => 'Input Nilai', 'route' => route('dosen.mk.input-nilai'), 'icon' => 'edit_note'],
        ['label' => 'Lihat Nilai', 'route' => route('dosen.mk.lihat-nilai'), 'icon' => 'analytics', 'variant' => 'nb-btn-secondary']
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('hero-header-dosen'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Dosen Mata Kuliah','description' => 'Kelola nilai dan pantau mahasiswa pada mata kuliah yang Anda ampu.','buttons' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Input Nilai', 'route' => route('dosen.mk.input-nilai'), 'icon' => 'edit_note'],
        ['label' => 'Lihat Nilai', 'route' => route('dosen.mk.lihat-nilai'), 'icon' => 'analytics', 'variant' => 'nb-btn-secondary']
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
        'mata_kuliah_diampu' => ['color' => 'nb-stat--info',    'icon' => 'menu_book',       'label' => 'Mata Kuliah Diampu'],
        'total_mahasiswa'    => ['color' => 'nb-stat--primary', 'icon' => 'groups',          'label' => 'Total Mahasiswa'],
        'nilai_diinput'      => ['color' => 'nb-stat--accent',  'icon' => 'edit_note',       'label' => 'Nilai Diinput'],
        'belum_dinilai'      => ['color' => 'nb-stat--warning', 'icon' => 'pending_actions', 'label' => 'Belum Dinilai']
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-bento'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['stats' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats),'config' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        'mata_kuliah_diampu' => ['color' => 'nb-stat--info',    'icon' => 'menu_book',       'label' => 'Mata Kuliah Diampu'],
        'total_mahasiswa'    => ['color' => 'nb-stat--primary', 'icon' => 'groups',          'label' => 'Total Mahasiswa'],
        'nilai_diinput'      => ['color' => 'nb-stat--accent',  'icon' => 'edit_note',       'label' => 'Nilai Diinput'],
        'belum_dinilai'      => ['color' => 'nb-stat--warning', 'icon' => 'pending_actions', 'label' => 'Belum Dinilai']
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

    
    <div class="nb-card mb-8">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div>
                <span class="nb-eyebrow">Pengajaran</span>
                <h3 class="nb-h3 mt-1">Mata Kuliah yang Diampu</h3>
            </div>
            <a href="<?php echo e(route('dosen.mk.input-nilai')); ?>" class="nb-btn nb-btn-primary nb-btn-sm">
                <span class="material-symbols-outlined" style="font-size:16px;">edit_note</span> Input Nilai
            </a>
        </div>

        <?php if(count($mataKuliah) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php $__currentLoopData = $mataKuliah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="nb-card flex flex-col gap-3" style="padding: 1.25rem;">
                    <div class="flex justify-between items-start">
                        <span class="nb-badge nb-badge-stable"><?php echo e($mk['kode']); ?></span>
                        <div class="flex gap-2">
                            <span class="nb-badge nb-badge-success"><?php echo e($mk['sks']); ?> SKS</span>
                            <span class="nb-badge nb-badge-primary">Kelas <?php echo e($mk['kelas']); ?></span>
                        </div>
                    </div>
                    <h4 class="font-extrabold text-ink text-lg" style="font-family: var(--font-heading);"><?php echo e($mk['nama']); ?></h4>
                    <p class="text-sm text-muted">Semester <?php echo e($mk['semester'] ?? '-'); ?></p>
                    <div class="pt-3 border-t-2 border-[rgba(31,41,55,0.15)] flex justify-between items-center">
                        <span class="nb-label" style="margin-bottom:0;">Mahasiswa</span>
                        <span class="font-extrabold text-primary" style="font-family: var(--font-heading);">
                            <?php echo e($mk['mahasiswa']); ?>/<?php echo e($mk['kapasitas']); ?>

                        </span>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="text-center py-10 text-muted">
            <span class="material-symbols-outlined" style="font-size:48px;">menu_book</span>
            <p class="mt-2">Belum ada mata kuliah yang diampu. Hubungi admin untuk penugasan.</p>
        </div>
        <?php endif; ?>
    </div>

    
    <?php if(count($mahasiswaTerbaru) > 0): ?>
    <div class="nb-card">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div>
                <span class="nb-eyebrow">Aktivitas</span>
                <h3 class="nb-h3 mt-1">Mahasiswa dengan Nilai Tertinggi</h3>
            </div>
            <a href="<?php echo e(route('dosen.mk.lihat-nilai')); ?>" class="nb-btn nb-btn-secondary nb-btn-sm">Lihat Semua</a>
        </div>

        <div class="space-y-3">
            <?php $__currentLoopData = $mahasiswaTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $initials = collect(explode(' ', $mhs['nama']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                ?>
                <div class="nb-list-row">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="nb-avatar-sm" style="cursor:default;background-color:#DBEAFE;color:#1E40AF;">
                            <span class="font-extrabold text-xs" style="font-family:var(--font-heading);"><?php echo e($initials); ?></span>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-ink text-sm"><?php echo e($mhs['nama']); ?></h4>
                            <p class="text-xs text-muted"><?php echo e($mhs['prodi'] ?? '-'); ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="nb-label" style="margin-bottom:2px;">Rata-rata Mutu</p>
                        <p class="font-extrabold text-primary text-lg" style="font-family:var(--font-heading);"><?php echo e(number_format($mhs['rata_nilai'], 2)); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dosen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/pages/dosen_matkul/beranda.blade.php ENDPATH**/ ?>