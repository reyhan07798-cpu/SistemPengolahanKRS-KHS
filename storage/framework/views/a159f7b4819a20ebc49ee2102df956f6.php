<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['dosen', 'routeUpdate', 'routePassword']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['dosen', 'routeUpdate', 'routePassword']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!-- Header Profile Card -->
<div class="nb-card mb-8">
    <div class="flex items-center gap-6 flex-wrap">
        <div class="nb-avatar" style="width: 6rem; height: 6rem; font-size: 2.5rem;">
            <?php echo e(strtoupper(substr(explode(' ', $dosen['nama'])[0], 0, 1))); ?>

        </div>
        <div class="min-w-0 flex-1">
            <span class="nb-eyebrow"><?php echo e($dosen['role'] ?? 'Dosen'); ?></span>
            <h1 class="nb-h1 mt-1" style="font-size: 2rem;"><?php echo e($dosen['nama']); ?></h1>
            <div class="flex items-center gap-2 mt-3 flex-wrap">
                <span class="nb-badge nb-badge-primary">
                    <span class="material-symbols-outlined" style="font-size:14px; margin-right:4px;"><?php echo e($dosen['icon'] ?? 'badge'); ?></span>
                    <?php echo e($dosen['id_field'] ?? $dosen['nidn'] ?? $dosen['nip']); ?>

                </span>
                <span class="nb-badge nb-badge-stable">
                    <span class="material-symbols-outlined" style="font-size:14px; margin-right:4px;">school</span>
                    <?php echo e($dosen['program_studi']); ?>

                </span>
            </div>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if(session('success')): ?>
    <div class="nb-alert nb-alert-success mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span>
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<?php if($errors->any()): ?>
    <div class="nb-alert nb-alert-danger mb-6">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined">error</span>
            <strong>Terdapat kesalahan input</strong>
        </div>
        <ul class="list-disc list-inside text-sm">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Informasi Pribadi -->
<div class="nb-card mb-8">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
        <div>
            <span class="nb-eyebrow">Data Diri</span>
            <h2 class="nb-h3 mt-1">Informasi Pribadi</h2>
        </div>
        <button type="button" onclick="toggleEdit()" class="nb-btn nb-btn-primary nb-btn-sm">
            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
            Edit Profil
        </button>
    </div>

    <form action="<?php echo e($routeUpdate); ?>" method="POST" id="formProfil">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="nb-label">Nama Lengkap</label>
                <input type="text" name="nama" value="<?php echo e($dosen['nama']); ?>" disabled id="inputNama">
            </div>
            <div>
                <label class="nb-label">Email</label>
                <input type="email" name="email" value="<?php echo e($dosen['email']); ?>" disabled id="inputEmail">
            </div>
            <div>
                <label class="nb-label">No. HP</label>
                <input type="text" name="no_hp" value="<?php echo e($dosen['no_hp']); ?>" disabled id="inputHp">
            </div>
            <div>
                <label class="nb-label">Alamat</label>
                <input type="text" name="alamat" value="<?php echo e($dosen['alamat']); ?>" disabled id="inputAlamat">
            </div>
        </div>

        <div class="mt-6 hidden" id="buttonSimpan">
            <div class="flex gap-3 flex-wrap">
                <button type="submit" class="nb-btn nb-btn-primary">
                    <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                    Simpan Perubahan
                </button>
                <button type="button" onclick="toggleEdit()" class="nb-btn nb-btn-secondary">
                    Batal
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Ubah Kata Sandi -->
<div class="nb-card">
    <div class="flex items-start gap-4 mb-6 flex-wrap">
        <div class="nb-stat-icon" style="background-color: var(--color-warning-soft); color: var(--color-warning);">
            <span class="material-symbols-outlined">lock</span>
        </div>
        <div class="flex-1 min-w-0">
            <span class="nb-eyebrow">Keamanan</span>
            <h2 class="nb-h3 mt-1">Ubah Kata Sandi</h2>
            <p class="text-sm text-muted mt-1">Perbarui kata sandi akun Anda secara berkala.</p>
        </div>
        <button type="button" onclick="togglePassword()" class="nb-btn nb-btn-warning nb-btn-sm">
            <span class="material-symbols-outlined" style="font-size:16px;">key</span>
            Ubah Kata Sandi
        </button>
    </div>

    <form action="<?php echo e($routePassword); ?>" method="POST" id="formPassword" class="hidden">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="nb-label">Password Lama</label>
                <input type="password" name="password_lama" placeholder="Masukkan password lama">
            </div>
            <div>
                <label class="nb-label">Password Baru</label>
                <input type="password" name="password_baru" placeholder="Masukkan password baru">
            </div>
            <div>
                <label class="nb-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_baru_confirmation" placeholder="Ulangi password baru">
            </div>

            <div class="flex gap-3 pt-2 flex-wrap">
                <button type="submit" class="nb-btn nb-btn-primary">
                    <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                    Simpan Password
                </button>
                <button type="button" onclick="togglePassword()" class="nb-btn nb-btn-secondary">
                    Batal
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function toggleEdit() {
    const inputs = ['inputNama', 'inputEmail', 'inputHp', 'inputAlamat'];
    const buttonSimpan = document.getElementById('buttonSimpan');
    inputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) input.disabled = !input.disabled;
    });
    buttonSimpan.classList.toggle('hidden');
}
function togglePassword() {
    const formPassword = document.getElementById('formPassword');
    if (formPassword) formPassword.classList.toggle('hidden');
}
</script>

<?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/components/profile-dosen.blade.php ENDPATH**/ ?>