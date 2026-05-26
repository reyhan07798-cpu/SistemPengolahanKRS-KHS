<?php $__env->startSection('title', 'Lihat Nilai'); ?>
<?php $__env->startSection('page_title', 'Lihat Nilai'); ?>

<?php $__env->startSection('content'); ?>
<div class="nb-page-header">
    <div>
        <span class="nb-eyebrow">Nilai</span>
        <h1 class="mt-2">Nilai Mahasiswa</h1>
        <p>Rekap nilai rinci mahasiswa per mata kuliah yang Anda ampu.</p>
    </div>
</div>


<div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
    <div class="nb-stat nb-stat--info nb-stat--ribbon">
        <div class="flex items-center gap-4">
            <div class="nb-stat-icon"><span class="material-symbols-outlined filled">groups</span></div>
            <div><p class="nb-stat-label">Total Mahasiswa</p><p class="nb-stat-value mt-1"><?php echo e($stats['total_mahasiswa']); ?></p></div>
        </div>
    </div>
    <div class="nb-stat nb-stat--accent nb-stat--ribbon">
        <div class="flex items-center gap-4">
            <div class="nb-stat-icon"><span class="material-symbols-outlined filled">edit_note</span></div>
            <div><p class="nb-stat-label">Nilai Terinput</p><p class="nb-stat-value mt-1"><?php echo e($stats['nilai_terinput']); ?></p></div>
        </div>
    </div>
    <div class="nb-stat nb-stat--warning nb-stat--ribbon">
        <div class="flex items-center gap-4">
            <div class="nb-stat-icon"><span class="material-symbols-outlined filled">analytics</span></div>
            <div><p class="nb-stat-label">Rata-rata Nilai</p><p class="nb-stat-value mt-1"><?php echo e($stats['rata_nilai']); ?></p></div>
        </div>
    </div>
</div>


<div class="nb-card mb-6">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-primary">filter_list</span>
        <h3 class="nb-h3">Filter</h3>
    </div>
    <form method="GET" action="<?php echo e(route('dosen.mk.lihat-nilai')); ?>" id="filterLihatForm">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            <div>
                <label class="nb-label">Tahun Ajaran</label>
                <select name="tahun_ajaran" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    <?php $__currentLoopData = $tahunAjaranList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ta); ?>" <?php echo e($filterTahunAjaran == $ta ? 'selected' : ''); ?>><?php echo e($ta); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
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
                <label class="nb-label">Mata Kuliah</label>
                <select name="mata_kuliah_id" onchange="onMKChangeLihat(this)">
                    <option value="">Semua Mata Kuliah</option>
                    <?php $__currentLoopData = $daftarMK; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($mk['id']); ?>" <?php echo e($filterMK == $mk['id'] ? 'selected' : ''); ?>>
                            <?php echo e($mk['kode_mk']); ?> – <?php echo e($mk['nama']); ?> (<?php echo e($mk['kelas']); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div>
                <label class="nb-label">Kelas</label>
                <select name="kelas" id="selectKelasLihat" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    <?php $__currentLoopData = $kelasDariMK; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kls); ?>" <?php echo e($filterKelas == $kls ? 'selected' : ''); ?>><?php echo e($kls); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </form>
</div>


<div class="nb-card-flat">
    <div class="nb-section-header">
        <div>
            <span class="nb-eyebrow" style="color:var(--color-accent-soft);">Rekap Nilai Rinci</span>
            <h2 class="mt-1">Daftar Nilai Mahasiswa</h2>
        </div>
        <span class="nb-badge nb-badge-primary"><?php echo e(count($mahasiswa)); ?> data</span>
    </div>
    <div class="overflow-x-auto">
        <table class="nb-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM / Nama</th>
                    <th>Mata Kuliah</th>
                    <th class="text-center" title="Nilai Tugas">Tugas</th>
                    <th class="text-center" title="Nilai Praktikum">Prak.</th>
                    <th class="text-center" title="Nilai UTS">UTS</th>
                    <th class="text-center" title="Nilai UAS">UAS</th>
                    <th class="text-center" title="Nilai Kehadiran">Had.</th>
                    <th class="text-center">Nilai Akhir</th>
                    <th class="text-center">Grade</th>
                    <th class="text-center">Mutu</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $mahasiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $nilaiAkhir = (float)($mhs['nilai_akhir'] ?? 0);
                    $grade      = $mhs['grade'] ?? '—';
                    $mutu       = (float)($mhs['mutu'] ?? 0);
                    $gradeBadge = match(true) {
                        in_array($grade,['A','A-'])           => 'nb-badge-success',
                        in_array($grade,['B+','B','B-'])      => 'nb-badge-primary',
                        in_array($grade,['C+','C'])           => 'nb-badge-warning',
                        $grade === 'D'                        => 'nb-badge-warning',
                        default                               => 'nb-badge-danger',
                    };
                ?>
                <tr>
                    <td class="font-bold text-muted"><?php echo e($mhs['no']); ?></td>
                    <td>
                        <div class="font-bold text-ink text-sm"><?php echo e($mhs['nama']); ?></div>
                        <div class="text-xs text-muted"><?php echo e($mhs['nim']); ?></div>
                    </td>
                    <td><span class="nb-badge nb-badge-stable text-xs"><?php echo e($mhs['kode_mk']); ?></span></td>
                    <td class="text-center text-sm"><?php echo e($mhs['nilai_tugas']     !== null ? number_format($mhs['nilai_tugas'],1)     : '—'); ?></td>
                    <td class="text-center text-sm"><?php echo e($mhs['nilai_praktikum'] !== null ? number_format($mhs['nilai_praktikum'],1) : '—'); ?></td>
                    <td class="text-center text-sm"><?php echo e($mhs['nilai_uts']       !== null ? number_format($mhs['nilai_uts'],1)       : '—'); ?></td>
                    <td class="text-center text-sm"><?php echo e($mhs['nilai_uas']       !== null ? number_format($mhs['nilai_uas'],1)       : '—'); ?></td>
                    <td class="text-center text-sm"><?php echo e($mhs['nilai_kehadiran'] !== null ? number_format($mhs['nilai_kehadiran'],1) : '—'); ?></td>
                    <td class="text-center font-extrabold text-primary text-lg" style="font-family:var(--font-heading);">
                        <?php echo e($nilaiAkhir > 0 ? number_format($nilaiAkhir, 1) : '—'); ?>

                    </td>
                    <td class="text-center">
                        <span class="nb-badge <?php echo e($gradeBadge); ?>"><?php echo e($grade); ?></span>
                    </td>
                    <td class="text-center">
                        <span class="font-extrabold text-lg <?php echo e($mutu >= 3.5 ? 'text-accent' : ($mutu >= 2.5 ? 'text-primary' : 'text-muted')); ?>"
                              style="font-family:var(--font-heading);">
                            <?php echo e($mutu > 0 ? number_format($mutu, 2) : '—'); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="11" class="text-center py-12">
                        <span class="material-symbols-outlined text-muted" style="font-size:48px;">search_off</span>
                        <p class="mt-2 text-muted font-medium">Belum ada data nilai. Pilih mata kuliah atau input nilai terlebih dahulu.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const URL_KELAS_LIHAT = '<?php echo e(route("dosen.mk.kelas-by-mk")); ?>';

function onMKChangeLihat(sel) {
    const mkId = sel.value;
    const kelasSelect = document.getElementById('selectKelasLihat');
    if (!mkId) {
        kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
        document.getElementById('filterLihatForm').submit();
        return;
    }
    fetch(URL_KELAS_LIHAT + '?mata_kuliah_id=' + mkId)
        .then(r => r.json())
        .then(data => {
            kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
            (data.kelas || []).forEach(k => {
                const opt = document.createElement('option');
                opt.value = k; opt.textContent = k;
                kelasSelect.appendChild(opt);
            });
            if ((data.kelas || []).length === 1) kelasSelect.value = data.kelas[0];
            document.getElementById('filterLihatForm').submit();
        });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dosen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/pages/dosen_matkul/lihat-nilai.blade.php ENDPATH**/ ?>