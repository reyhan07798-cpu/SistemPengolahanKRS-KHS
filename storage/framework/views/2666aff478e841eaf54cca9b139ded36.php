<?php $__env->startSection('page_title', 'Beranda'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Dashboard</span>
            <h1 class="mt-2">Selamat datang, <?php echo e($data['nama']); ?></h1>
            <p>Ringkasan akademik dan progres studi Anda semester ini.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('pages.mahasiswa.ambil-krs')); ?>" class="nb-btn nb-btn-secondary">
                <span class="material-symbols-outlined" style="font-size:20px;">assignment</span>
                Ambil KRS
            </a>
            <a href="<?php echo e(route('pages.mahasiswa.lihat-khs')); ?>" class="nb-btn nb-btn-primary">
                <span class="material-symbols-outlined" style="font-size:20px;">grade</span>
                Lihat KHS
            </a>
        </div>
    </div>

    
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">event</span>
                </div>
                <p class="nb-stat-label">Semester Aktif</p>
            </div>
            <div class="nb-stat-value"><?php echo e($data['semester_aktif']); ?></div>
        </div>

        <div class="nb-stat nb-stat--primary nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">assignment</span>
                </div>
                <p class="nb-stat-label">Total SKS</p>
            </div>
            <div class="nb-stat-value"><?php echo e($data['total_sks']); ?></div>
        </div>

        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">trending_up</span>
                </div>
                <p class="nb-stat-label">IPK</p>
            </div>
            <div class="nb-stat-value"><?php echo e(number_format($data['ipk'], 2)); ?></div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">workspace_premium</span>
                </div>
                <p class="nb-stat-label">MK Lulus</p>
            </div>
            <div class="nb-stat-value"><?php echo e($data['mata_kuliah_lulus']); ?></div>
        </div>
    </div>

    
    <div class="nb-card mb-8">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
            <div>
                <span class="nb-eyebrow">Profil</span>
                <h3 class="nb-h3 mt-1">Informasi Mahasiswa</h3>
            </div>
            <a href="<?php echo e(route('pages.mahasiswa.profil')); ?>" class="nb-btn nb-btn-secondary nb-btn-sm">
                <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                Kelola Profil
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            <div>
                <p class="nb-label">NIM</p>
                <p class="text-base font-bold text-ink mb-4"><?php echo e($data['nim']); ?></p>

                <p class="nb-label">Nama</p>
                <p class="text-base font-bold text-ink mb-4"><?php echo e($data['nama']); ?></p>

                <p class="nb-label">Program Studi</p>
                <p class="text-base font-bold text-ink"><?php echo e($data['prodi']); ?></p>
            </div>

            <div>
                <p class="nb-label">Angkatan</p>
                <p class="text-base font-bold text-ink mb-4"><?php echo e($data['angkatan']); ?></p>

                <p class="nb-label">Email</p>
                <p class="text-base font-bold text-ink mb-4 break-all"><?php echo e($data['email']); ?></p>

                <p class="nb-label">Semester Aktif</p>
                <p class="text-base font-bold text-ink">Semester <?php echo e($data['semester_aktif']); ?></p>
            </div>
        </div>
    </div>

    
    <div class="nb-card-flat mb-8">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Akademik</span>
                <h2 class="mt-1">Nilai Terbaru</h2>
            </div>
            <a href="<?php echo e(route('pages.mahasiswa.lihat-khs')); ?>" class="nb-btn nb-btn-secondary nb-btn-sm">
                Lihat Semua
                <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
            </a>
        </div>

        <?php if(count($data['nilai_terbaru']) > 0): ?>
            <div class="overflow-x-auto">
                <table class="nb-table">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Angka (0–4)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $data['nilai_terbaru']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nilai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginale0962b715e147072582052543364fa8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0962b715e147072582052543364fa8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.grade-row','data' => ['matkul' => $nilai['matkul'],'sks' => $nilai['sks'],'nilai' => $nilai['nilai'],'bobot' => $nilai['bobot']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('grade-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['matkul' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($nilai['matkul']),'sks' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($nilai['sks']),'nilai' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($nilai['nilai']),'bobot' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($nilai['bobot'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0962b715e147072582052543364fa8b)): ?>
<?php $attributes = $__attributesOriginale0962b715e147072582052543364fa8b; ?>
<?php unset($__attributesOriginale0962b715e147072582052543364fa8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0962b715e147072582052543364fa8b)): ?>
<?php $component = $__componentOriginale0962b715e147072582052543364fa8b; ?>
<?php unset($__componentOriginale0962b715e147072582052543364fa8b); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-muted" style="font-size:48px;">grade</span>
                <p class="mt-2 text-muted font-medium">Belum ada data nilai</p>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Semester Berjalan</span>
                <h2 class="mt-1">KRS Aktif</h2>
            </div>
            <a href="<?php echo e(route('pages.mahasiswa.ambil-krs')); ?>" class="nb-btn nb-btn-secondary nb-btn-sm">
                Kelola KRS
                <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
            </a>
        </div>

        <?php if(count($data['krs_aktif']) > 0): ?>
            <div class="overflow-x-auto">
                <table class="nb-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $data['krs_aktif']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $krs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal367205e9d4f0cc1e8c0896d1ddd7cd07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal367205e9d4f0cc1e8c0896d1ddd7cd07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.krs-table-row','data' => ['kode' => $krs['kode'],'matkul' => $krs['matkul'],'sks' => $krs['sks'],'status' => $krs['status']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krs-table-row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['kode' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($krs['kode']),'matkul' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($krs['matkul']),'sks' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($krs['sks']),'status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($krs['status'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal367205e9d4f0cc1e8c0896d1ddd7cd07)): ?>
<?php $attributes = $__attributesOriginal367205e9d4f0cc1e8c0896d1ddd7cd07; ?>
<?php unset($__attributesOriginal367205e9d4f0cc1e8c0896d1ddd7cd07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal367205e9d4f0cc1e8c0896d1ddd7cd07)): ?>
<?php $component = $__componentOriginal367205e9d4f0cc1e8c0896d1ddd7cd07; ?>
<?php unset($__componentOriginal367205e9d4f0cc1e8c0896d1ddd7cd07); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-muted" style="font-size:48px;">assignment</span>
                <p class="mt-2 text-muted font-medium">Belum ada KRS aktif</p>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/pages/mahasiswa/beranda.blade.php ENDPATH**/ ?>