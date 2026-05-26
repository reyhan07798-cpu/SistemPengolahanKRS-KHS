<?php $__env->startSection('title', 'Input Nilai'); ?>
<?php $__env->startSection('page_title', 'Input Nilai'); ?>

<?php $__env->startSection('content'); ?>
<div class="nb-page-header">
    <div>
        <span class="nb-eyebrow">Penilaian</span>
        <h1 class="mt-2">Input Nilai</h1>
        <p>Pilih mata kuliah — kelas otomatis terfilter. Isi nilai langsung di tabel.</p>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="nb-alert nb-alert-success mb-6"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if($isReadOnly && $mkAktif): ?>
    <div class="nb-alert nb-alert-warning mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined">lock</span>
        <strong>Semester tidak aktif.</strong> Nilai hanya bisa dilihat (read only).
    </div>
<?php endif; ?>


<div class="nb-card mb-6">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-primary">filter_list</span>
        <h3 class="nb-h3">Pilih Mata Kuliah</h3>
    </div>
    <form method="GET" action="<?php echo e(route('dosen.mk.input-nilai')); ?>" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="nb-label">Mata Kuliah <span class="text-red-500">*</span></label>
                <select name="mata_kuliah_id" id="selectMK" onchange="onMKChange(this)">
                    <option value="">-- Pilih Mata Kuliah --</option>
                    <?php $__currentLoopData = $mataKuliahList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($mk['id']); ?>" <?php echo e($filterMK == $mk['id'] ? 'selected' : ''); ?>

                                data-kelas="<?php echo e($mk['kelas']); ?>" data-active="<?php echo e($mk['is_active'] ? '1' : '0'); ?>">
                            <?php echo e($mk['kode']); ?> – <?php echo e($mk['nama']); ?> (<?php echo e($mk['kelas']); ?>)
                            <?php if(!$mk['is_active']): ?> 🔒 <?php endif; ?>
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="nb-label">Kelas</label>
                <select name="kelas" id="selectKelas" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    <?php $__currentLoopData = $kelasDariMK; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kls); ?>" <?php echo e($filterKelas == $kls ? 'selected' : ''); ?>><?php echo e($kls); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <?php if($mkAktif && !$isReadOnly): ?>
                    <span class="nb-badge nb-badge-success py-2 px-4">✓ Semester Aktif — dapat input</span>
                <?php elseif($mkAktif && $isReadOnly): ?>
                    <span class="nb-badge nb-badge-stable py-2 px-4">🔒 Read Only</span>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<?php if($mkAktif && count($mahasiswaList) > 0): ?>


<?php if(!$isReadOnly): ?>
<div class="nb-card mb-6" id="bobotCard">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-primary">tune</span>
        <h3 class="nb-h3">Bobot Penilaian <span class="nb-badge nb-badge-stable ml-2" style="font-size:11px;">Variabel — total harus 100%</span></h3>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <?php $__currentLoopData = [
            ['key'=>'tugas',     'label'=>'Tugas (%)'],
            ['key'=>'praktikum', 'label'=>'Praktikum (%)'],
            ['key'=>'uts',       'label'=>'UTS (%)'],
            ['key'=>'uas',       'label'=>'UAS (%)'],
            ['key'=>'kehadiran', 'label'=>'Kehadiran (%)'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $val = $mahasiswaList[0]['bobot_'.$b['key']] ?? ['tugas'=>20,'praktikum'=>15,'uts'=>30,'uas'=>30,'kehadiran'=>5][$b['key']]; ?>
        <div>
            <label class="nb-label"><?php echo e($b['label']); ?></label>
            <input type="number" id="bobot_<?php echo e($b['key']); ?>" value="<?php echo e($val); ?>"
                   min="0" max="100" step="1"
                   class="text-center bobot-input w-full"
                   oninput="updateBobotTotal(); recalcAll()">
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-3 flex items-center gap-3">
        <span class="nb-label mb-0">Total:</span>
        <span id="bobotTotal" class="font-extrabold text-lg text-primary">100%</span>
        <span id="bobotWarn" class="text-xs text-red-500 hidden">⚠ Total harus 100%</span>
    </div>
</div>
<?php endif; ?>


<div class="nb-card-flat">
    <div class="nb-section-header">
        <div>
            <span class="nb-eyebrow" style="color:var(--color-accent-soft);">
                <?php echo e($mkAktif->kode_mk); ?> · <?php echo e($mkAktif->kelas ?? '-'); ?>

            </span>
            <h2 class="mt-1"><?php echo e($mkAktif->nama); ?></h2>
        </div>
        <div class="flex items-center gap-3">
            <span class="nb-badge nb-badge-primary"><?php echo e(count($mahasiswaList)); ?> mahasiswa</span>
            <?php if(!$isReadOnly): ?>
            <button onclick="simpanSemua()" id="btnSimpanSemua"
                    class="nb-btn nb-btn-primary nb-btn-sm">
                <span class="material-symbols-outlined" style="font-size:16px;">save</span> Simpan Semua
            </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="nb-table" id="tabelNilai">
            <thead>
                <tr>
                    <th style="width:40px">No</th>
                    <th>NIM / Nama</th>
                    <th class="text-center" style="min-width:80px">Tugas<br><small id="hdr_tugas" class="text-muted font-normal">(<?php echo e($mahasiswaList[0]['bobot_tugas'] ?? 20); ?>%)</small></th>
                    <th class="text-center" style="min-width:80px">Praktikum<br><small id="hdr_praktikum" class="text-muted font-normal">(<?php echo e($mahasiswaList[0]['bobot_praktikum'] ?? 15); ?>%)</small></th>
                    <th class="text-center" style="min-width:80px">UTS<br><small id="hdr_uts" class="text-muted font-normal">(<?php echo e($mahasiswaList[0]['bobot_uts'] ?? 30); ?>%)</small></th>
                    <th class="text-center" style="min-width:80px">UAS<br><small id="hdr_uas" class="text-muted font-normal">(<?php echo e($mahasiswaList[0]['bobot_uas'] ?? 30); ?>%)</small></th>
                    <th class="text-center" style="min-width:80px">Kehadiran<br><small id="hdr_kehadiran" class="text-muted font-normal">(<?php echo e($mahasiswaList[0]['bobot_kehadiran'] ?? 5); ?>%)</small></th>
                    <th class="text-center">Nilai Akhir</th>
                    <th class="text-center">Grade</th>
                    <?php if(!$isReadOnly): ?><th class="text-center">Aksi</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $mahasiswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="row_<?php echo e($mhs['id']); ?>">
                <td class="text-muted font-bold"><?php echo e($idx+1); ?></td>
                <td>
                    <div class="font-bold text-ink text-sm"><?php echo e($mhs['nama']); ?></div>
                    <div class="text-xs text-muted"><?php echo e($mhs['nim']); ?></div>
                </td>
                <?php $__currentLoopData = ['tugas','praktikum','uts','uas','kehadiran']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td class="text-center p-1">
                    <?php if(!$isReadOnly): ?>
                    <input type="number" id="<?php echo e($k); ?>_<?php echo e($mhs['id']); ?>"
                           value="<?php echo e($mhs['nilai_'.$k] ?? ''); ?>"
                           min="0" max="100" step="0.5" placeholder="—"
                           data-mhs="<?php echo e($mhs['id']); ?>"
                           oninput="hitungBaris(<?php echo e($mhs['id']); ?>)"
                           style="width:68px;padding:5px 3px;border:1.5px solid var(--nb-border);border-radius:6px;font-size:12px;text-align:center;background:var(--nb-surface);">
                    <?php else: ?>
                    <span class="text-sm font-medium"><?php echo e($mhs['nilai_'.$k] ?? '—'); ?></span>
                    <?php endif; ?>
                </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td class="text-center">
                    <span id="akhir_<?php echo e($mhs['id']); ?>" class="font-extrabold text-primary" style="font-family:var(--font-heading);">
                        <?php echo e($mhs['nilai_akhir'] !== null ? number_format($mhs['nilai_akhir'],1) : '—'); ?>

                    </span>
                </td>
                <td class="text-center">
                    <span id="grade_<?php echo e($mhs['id']); ?>" class="nb-badge <?php echo e($mhs['grade'] ? 'nb-badge-success' : 'nb-badge-stable'); ?>">
                        <?php echo e($mhs['grade'] ?? '—'); ?>

                    </span>
                </td>
                <?php if(!$isReadOnly): ?>
                <td class="text-center">
                    <button onclick="simpanSatu(<?php echo e($mhs['id']); ?>)" id="btn_<?php echo e($mhs['id']); ?>"
                            class="nb-btn nb-btn-primary nb-btn-sm">
                        <span class="material-symbols-outlined" style="font-size:14px;">save</span>
                    </button>
                    <span id="ok_<?php echo e($mhs['id']); ?>" class="text-xs text-green-600 hidden ml-1">✓</span>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php elseif($mkAktif && count($mahasiswaList) === 0): ?>
<div class="nb-card text-center py-12">
    <span class="material-symbols-outlined text-muted" style="font-size:64px;">group_off</span>
    <h3 class="nb-h3 mt-4">Belum Ada Mahasiswa</h3>
    <p class="text-muted mt-2">Belum ada mahasiswa yang KRS-nya disetujui untuk mata kuliah ini<?php echo e($filterKelas ? ' di kelas '.$filterKelas : ''); ?>.</p>
</div>
<?php else: ?>
<div class="nb-card text-center py-12">
    <span class="material-symbols-outlined text-muted" style="font-size:64px;">edit_note</span>
    <h3 class="nb-h3 mt-4">Pilih Mata Kuliah</h3>
    <p class="text-muted mt-2">Pilih mata kuliah di atas untuk menampilkan daftar mahasiswa.</p>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const MK_ID  = <?php echo e($filterMK ?: 'null'); ?>;
const CSRF   = '<?php echo e(csrf_token()); ?>';
const URL_SIMPAN = '<?php echo e(route("dosen.mk.simpan-nilai")); ?>';
const URL_KELAS  = '<?php echo e(route("dosen.mk.kelas-by-mk")); ?>';

// Saat MK berubah: ambil kelas dari API lalu submit form
function onMKChange(sel) {
    const mkId = sel.value;
    if (!mkId) { document.getElementById('filterForm').submit(); return; }
    fetch(URL_KELAS + '?mata_kuliah_id=' + mkId)
        .then(r => r.json())
        .then(data => {
            const kelasSelect = document.getElementById('selectKelas');
            kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
            (data.kelas || []).forEach(k => {
                const opt = document.createElement('option');
                opt.value = k; opt.textContent = k;
                kelasSelect.appendChild(opt);
            });
            // Auto-pilih jika hanya 1 kelas
            if ((data.kelas || []).length === 1) kelasSelect.value = data.kelas[0];
            document.getElementById('filterForm').submit();
        });
}

function getBobots() {
    return {
        tugas:     parseFloat(document.getElementById('bobot_tugas')?.value     || 20),
        praktikum: parseFloat(document.getElementById('bobot_praktikum')?.value || 15),
        uts:       parseFloat(document.getElementById('bobot_uts')?.value        || 30),
        uas:       parseFloat(document.getElementById('bobot_uas')?.value        || 30),
        kehadiran: parseFloat(document.getElementById('bobot_kehadiran')?.value  || 5),
    };
}

function updateBobotTotal() {
    const b = getBobots();
    const total = b.tugas + b.praktikum + b.uts + b.uas + b.kehadiran;
    const el = document.getElementById('bobotTotal');
    const warn = document.getElementById('bobotWarn');
    if (el) el.textContent = total + '%';
    if (warn) warn.classList.toggle('hidden', Math.abs(total-100) < 0.01);
    ['tugas','praktikum','uts','uas','kehadiran'].forEach(k => {
        const h = document.getElementById('hdr_'+k);
        if (h) h.textContent = '(' + b[k] + '%)';
    });
}

function gradeFromN(n) {
    if (n>=85) return {g:'A',  cls:'nb-badge-success'};
    if (n>=80) return {g:'A-', cls:'nb-badge-success'};
    if (n>=75) return {g:'B+', cls:'nb-badge-primary'};
    if (n>=70) return {g:'B',  cls:'nb-badge-primary'};
    if (n>=65) return {g:'B-', cls:'nb-badge-primary'};
    if (n>=60) return {g:'C+', cls:'nb-badge-warning'};
    if (n>=55) return {g:'C',  cls:'nb-badge-warning'};
    if (n>=40) return {g:'D',  cls:'nb-badge-warning'};
    return {g:'E', cls:'nb-badge-danger'};
}

function hitungBaris(id) {
    const b = getBobots();
    const v = k => parseFloat(document.getElementById(k+'_'+id)?.value) || 0;
    const akhir = (v('tugas')*b.tugas + v('praktikum')*b.praktikum +
                   v('uts')*b.uts + v('uas')*b.uas + v('kehadiran')*b.kehadiran) / 100;
    const el = document.getElementById('akhir_'+id);
    const gr = document.getElementById('grade_'+id);
    if (el) el.textContent = akhir > 0 ? akhir.toFixed(1) : '—';
    if (gr && akhir > 0) { const g = gradeFromN(akhir); gr.textContent = g.g; gr.className = 'nb-badge '+g.cls; }
}

function recalcAll() {
    document.querySelectorAll('[id^="akhir_"]').forEach(el => hitungBaris(el.id.replace('akhir_','')));
    updateBobotTotal();
}

async function simpanSatu(id) {
    const b = getBobots();
    const total = b.tugas+b.praktikum+b.uts+b.uas+b.kehadiran;
    if (Math.abs(total-100) > 0.01) { alert('Total bobot harus 100%. Sekarang: '+total+'%'); return; }

    const btn = document.getElementById('btn_'+id);
    if(btn){ btn.disabled=true; btn.innerHTML='<span class="material-symbols-outlined" style="font-size:14px;">hourglass_empty</span>'; }

    const fd = new FormData();
    fd.append('_token', CSRF);
    fd.append('mata_kuliah_id', MK_ID);
    fd.append('mahasiswa_id', id);
    ['tugas','praktikum','uts','uas','kehadiran'].forEach(k => {
        fd.append('bobot_'+k, b[k]);
        fd.append('nilai_'+k+'['+id+']', document.getElementById(k+'_'+id)?.value || 0);
    });

    try {
        const res = await fetch(URL_SIMPAN, {method:'POST', body:fd});
        const data = await res.json();
        if (data.success) {
            const ok = document.getElementById('ok_'+id);
            if(ok){ ok.classList.remove('hidden'); setTimeout(()=>ok.classList.add('hidden'),2000); }
            const el = document.getElementById('akhir_'+id);
            const gr = document.getElementById('grade_'+id);
            if(el) el.textContent = parseFloat(data.nilai_akhir).toFixed(1);
            if(gr){ const g = gradeFromN(data.nilai_akhir); gr.textContent=data.grade; gr.className='nb-badge '+g.cls; }
        } else { alert(data.message); }
    } catch(e) { alert('Gagal menyimpan. Periksa koneksi.'); }

    if(btn){ btn.disabled=false; btn.innerHTML='<span class="material-symbols-outlined" style="font-size:14px;">save</span>'; }
}

async function simpanSemua() {
    const rows = document.querySelectorAll('[id^="row_"]');
    const btn = document.getElementById('btnSimpanSemua');
    if(btn){ btn.disabled=true; btn.textContent='Menyimpan...'; }
    for (const row of rows) { await simpanSatu(row.id.replace('row_','')); }
    if(btn){ btn.disabled=false; btn.innerHTML='<span class="material-symbols-outlined" style="font-size:16px;">save</span> Simpan Semua'; }
}

// lihat-nilai auto filter
document.getElementById('filterForm') && updateBobotTotal();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dosen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS\resources\views/pages/dosen_matkul/input-nilai.blade.php ENDPATH**/ ?>