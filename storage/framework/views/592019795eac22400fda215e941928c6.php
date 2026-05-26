<?php $__env->startSection('page_title', 'Kartu Hasil Studi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Akademik</span>
            <h1 class="mt-2">Kartu Hasil Studi</h1>
            <p>Rekap nilai dan capaian akademik Anda dari semua semester.</p>
        </div>
    </div>

    <div class="flex justify-end mb-4">
        <a href="<?php echo e(route('pages.mahasiswa.khs.pdf')); ?>" target="_blank" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">picture_as_pdf</span>
            Cetak KHS
        </a>
    </div>

    
    <div class="nb-bento mb-6" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">trending_up</span>
                </div>
                <p class="nb-stat-label">IPK</p>
            </div>
            <div class="nb-stat-value"><?php echo e(number_format($ipk, 2)); ?></div>
        </div>

        <div class="nb-stat nb-stat--primary nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">menu_book</span>
                </div>
                <p class="nb-stat-label">Total SKS</p>
            </div>
            <div class="nb-stat-value"><?php echo e($totalSks); ?></div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">grade</span>
                </div>
                <p class="nb-stat-label">Mata Kuliah</p>
            </div>
            <div class="nb-stat-value"><?php echo e($mataKuliahCount); ?></div>
        </div>
    </div>

    
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">bar_chart</span>
            <h3 class="nb-h3">Indeks Prestasi Per Semester</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th class="text-center">Semester</th>
                        <th class="text-center">Tahun Ajaran</th>
                        <th class="text-center">MK Diambil</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">IPS</th>
                        <th class="text-center">IPK Kumulatif</th>
                        <th class="text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $ipSemester; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $ipsClass = $ip->ips >= 3.5 ? 'text-accent' : ($ip->ips >= 3.0 ? 'text-primary' : 'text-muted');
                        ?>

                        <tr>
                            <td class="text-center">
                                <span class="nb-badge <?php echo e($ip->semester === 'Ganjil' ? 'nb-badge-info' : 'nb-badge-primary'); ?>">
                                    <?php echo e($ip->semester); ?>

                                </span>
                            </td>
                            <td class="text-center text-muted"><?php echo e($ip->tahun_ajaran); ?></td>
                            <td class="text-center font-bold text-primary"><?php echo e($ip->mk); ?> MK</td>
                            <td class="text-center font-bold text-primary"><?php echo e($ip->sks); ?> SKS</td>
                            <td class="text-center">
                                <span class="font-extrabold text-xl <?php echo e($ipsClass); ?>" style="font-family:var(--font-heading);">
                                    <?php echo e(number_format($ip->ips, 2)); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <span class="font-bold text-ink" style="font-family:var(--font-heading);">
                                    <?php echo e(number_format($ip->ipk, 2)); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <span class="nb-badge <?php echo e($ip->predikat['badge']); ?>">
                                    <?php echo e($ip->predikat['label']); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-muted">
                                Belum ada data IP semester.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter Nilai</h3>
        </div>

        <form method="GET" action="<?php echo e(route('pages.mahasiswa.lihat-khs')); ?>">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="tahun_ajaran" class="nb-label">Tahun Ajaran</label>
                    <select id="tahun_ajaran" name="tahun_ajaran">
                        <option value="">-- Semua Tahun Ajaran --</option>
                        <?php $__currentLoopData = $listTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tahun); ?>" <?php if(($tahunFilter ?? '') === $tahun): echo 'selected'; endif; ?>>
                                <?php echo e($tahun); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label for="semester" class="nb-label">Semester</label>
                    <select id="semester" name="semester">
                        <option value="">-- Semua Semester --</option>
                        <option value="Ganjil" <?php if(($semesterFilter ?? '') === 'Ganjil'): echo 'selected'; endif; ?>>Ganjil</option>
                        <option value="Genap" <?php if(($semesterFilter ?? '') === 'Genap'): echo 'selected'; endif; ?>>Genap</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="nb-btn nb-btn-primary w-full">
                        <span class="material-symbols-outlined" style="font-size:18px;">search</span>
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color:var(--color-accent-soft);">Transkrip</span>
                <h2 class="mt-1">Daftar Nilai</h2>
            </div>
            <span class="nb-badge nb-badge-primary"><?php echo e($mataKuliahCount); ?> Mata Kuliah</span>
        </div>

        <div class="overflow-x-auto">
            <table class="nb-table" id="tabelNilai">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Semester</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Angka (0–4)</th>
                        <th class="text-center">Tahun Ajaran</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $nilai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $mutu = (float) $n->bobot;
                            $mutuClass = $mutu >= 3.5 ? 'text-accent' : ($mutu >= 2.5 ? 'text-primary' : 'text-muted');

                            $nilaiBadge = match($n->nilai) {
                                'A','A-' => 'nb-badge-success',
                                'B+','B' => 'nb-badge-primary',
                                'B-','C+','C' => 'nb-badge-warning',
                                default => 'nb-badge-danger',
                            };
                        ?>

                        <tr>
                            <td class="font-bold text-primary" style="font-family:var(--font-heading);">
                                <?php echo e($n->kode_mk); ?>

                            </td>

                            <td class="font-medium text-ink">
                                <?php echo e($n->nama_mk); ?>

                            </td>

                            <td class="text-center">
                                <?php echo e($n->sks); ?>

                            </td>

                            <td class="text-center">
                                <span class="nb-badge <?php echo e($n->semester === 'Ganjil' ? 'nb-badge-info' : 'nb-badge-primary'); ?>">
                                    <?php echo e($n->semester); ?>

                                </span>
                            </td>

                            <td class="text-center">
                                <span class="nb-badge <?php echo e($nilaiBadge); ?>">
                                    <?php echo e($n->nilai); ?>

                                </span>
                            </td>

                            <td class="text-center">
                                <span class="font-extrabold <?php echo e($mutuClass); ?>" style="font-family:var(--font-heading);">
                                    <?php echo e(number_format($mutu, 2)); ?>

                                </span>
                            </td>

                            <td class="text-center text-muted">
                                <?php echo e($n->tahun_ajaran); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <span class="material-symbols-outlined text-muted" style="font-size:48px;">grade</span>
                                <p class="mt-2 text-muted font-medium">Belum ada data nilai.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/pages/mahasiswa/lihat-khs.blade.php ENDPATH**/ ?>