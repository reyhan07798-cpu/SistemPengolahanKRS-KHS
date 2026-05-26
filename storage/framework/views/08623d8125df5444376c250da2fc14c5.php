<?php $__env->startSection('page_title', 'Profil'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="nb-card mb-8">
        <div class="flex items-center gap-6 flex-wrap">
            <div class="nb-avatar" style="width: 6rem; height: 6rem; font-size: 2.5rem;">
                <?php echo e(strtoupper(substr($data['nama'] ?? 'M', 0, 1))); ?>

            </div>

            <div class="min-w-0 flex-1">
                <span class="nb-eyebrow">Mahasiswa</span>

                <h1 class="nb-h1 mt-1" style="font-size: 2rem;">
                    <?php echo e($data['nama'] ?? '-'); ?>

                </h1>

                <div class="flex items-center gap-2 mt-3 flex-wrap">
                    <span class="nb-badge nb-badge-primary">
                        <span class="material-symbols-outlined" style="font-size:14px; margin-right:4px;">school</span>
                        <?php echo e($data['program_studi'] ?? 'Teknik Informatika'); ?>

                    </span>

                    <span class="nb-badge nb-badge-stable">
                        NIM: <?php echo e($data['nim'] ?? '-'); ?>

                    </span>

                    <?php if(!empty($data['kelas']) && $data['kelas'] !== '-'): ?>
                        <span class="nb-badge nb-badge-stable">
                            Kelas: <?php echo e($data['kelas']); ?>

                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="nb-alert nb-alert-success mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="nb-alert nb-alert-danger mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('warning')): ?>
        <div class="nb-alert nb-alert-warning mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">warning</span>
            <?php echo e(session('warning')); ?>

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

        <form action="<?php echo e(route('pages.mahasiswa.profil.update')); ?>" method="POST" id="formProfil">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="nb-label">Nama Lengkap</label>
                    <input
                        type="text"
                        name="nama"
                        value="<?php echo e(old('nama', $data['nama'] ?? '')); ?>"
                        disabled
                        id="inputNama"
                    >
                    <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="nb-form-error"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="nb-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="<?php echo e(old('email', $data['email'] ?? '')); ?>"
                        disabled
                        id="inputEmail"
                    >
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="nb-form-error"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="nb-label">No. HP</label>
                    <input
                        type="text"
                        name="no_hp"
                        value="<?php echo e(old('no_hp', $data['no_hp'] ?? '')); ?>"
                        disabled
                        id="inputHp"
                    >
                    <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="nb-form-error"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="nb-label">Alamat</label>
                    <input
                        type="text"
                        name="alamat"
                        value="<?php echo e(old('alamat', $data['alamat'] ?? '')); ?>"
                        disabled
                        id="inputAlamat"
                    >
                    <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="nb-form-error"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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

        <form action="<?php echo e(route('pages.mahasiswa.profil.password')); ?>" method="POST" id="formPassword" class="hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="nb-label">Kata Sandi Lama</label>
                    <input type="password" name="password_lama" placeholder="Masukkan kata sandi lama">
                    <?php $__errorArgs = ['password_lama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="nb-form-error"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="nb-label">Kata Sandi Baru</label>
                    <input type="password" name="password_baru" placeholder="Masukkan kata sandi baru">
                    <?php $__errorArgs = ['password_baru'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="nb-form-error"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="nb-label">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_baru_confirmation" placeholder="Ulangi kata sandi baru">
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function toggleEdit() {
        const inputs = ['inputNama', 'inputEmail', 'inputHp', 'inputAlamat'];
        const buttonSimpan = document.getElementById('buttonSimpan');

        inputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) input.disabled = !input.disabled;
        });

        if (buttonSimpan) {
            buttonSimpan.classList.toggle('hidden');
        }
    }

    function togglePassword() {
        const form = document.getElementById('formPassword');

        if (form) {
            form.classList.toggle('hidden');
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/pages/mahasiswa/profil.blade.php ENDPATH**/ ?>