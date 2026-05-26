<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Sistem Pengelolaan KRS-KHS</title>
  <link rel="icon" type="image/png" sizes="16x16 32x32" href="<?php echo e(asset('images/logo.png')); ?>">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/login.js']); ?>
  <style>
    .loading .bt { display: none; }
    .loading .bs { display: flex !important; }
    
    @keyframes tIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
    .toast-anim { animation: tIn 0.3s ease both; }
    
    /* Mobile tweaks */
    @media (max-width: 1023px) {
      main {
        background-image: linear-gradient(rgba(6,11,22,0.9), rgba(6,11,22,0.7)), url('<?php echo e(asset('images/default-campus.jpg')); ?>');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }
      .main-container {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,0.3);
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3);
      }
      .nb-input::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        background-size: contain;
        background-repeat: no-repeat;
        z-index: 2;
        opacity: 0.5;
      }
      .nb-input.nim-input::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231f2937'%3E%3Cpath d='M20 6h-2.18c.11-.31.18-.65.18-1a2.996 2.996 0 0 0-5.5-1.65l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 5.94 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM4 8h16v10H4V8zm10 9c0 .55-.45 1-1 1s-1-.45-1-1 .45-1 1-1 1 .45 1 1zm-4 0c0 .55-.45 1-1 1s-1-.45-1-1 .45-1 1-1 1 .45 1 1z"/%3E%3C/svg%3E");
      }
      .nb-input.password-input::before {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231f2937'%3E%3Cpath d='M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM12 17c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm1-8H11V6c0-1.1.89-2 2-2 .91 0 1.65.74 1.65 1.65V9z'/></svg>%3E");
      }
      .nb-input::placeholder { color: rgba(0,0,0,0.4); }
      .nb-label { color: #1f2937; }
      /* Arrow style baru - no circle, ujung kiri */
      .arrow-back-link a {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        color: rgba(255,255,255,0.9);
        font-size: 1.1rem;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
        position: absolute;
        left: -3rem;
        top: 1rem;
        lg:hidden;
      }
      .arrow-back-link a:hover {
        background: rgba(255,255,255,0.15);
        transform: translateX(-0.25rem);
        color: white;
      }
    }
    .arrow-close.lg-hidden { display: none; }
    @media (min-width: 1024px) { .arrow-close { display: none; } }
  </style>
</head>
<body class="font-sans antialiased" style="font-family: 'Plus Jakarta Sans', sans-serif;">

  
  <div class="fixed top-5 right-5 z-50 flex flex-col gap-2">
    <?php if(session('success')): ?>
      <div class="toast-anim bg-[var(--color-surface)] border-[3px] border-[var(--color-ink)] shadow-[4px_4px_0_0_var(--color-ink)] px-4 py-3 rounded-xl flex items-center gap-3" id="toastAuto">
        <i class="fas fa-check-circle text-[var(--color-success)] text-lg"></i>
        <span class="text-sm font-bold"><?php echo e(session('success')); ?></span>
      </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
      <div class="toast-anim bg-[var(--color-surface)] border-[3px] border-[var(--color-ink)] shadow-[4px_4px_0_0_var(--color-ink)] px-4 py-3 rounded-xl flex items-center gap-3" id="toastAuto">
        <i class="fas fa-times-circle text-red-500 text-lg"></i>
        <span class="text-sm font-bold"><?php echo e(session('error')); ?></span>
      </div>
    <?php endif; ?>
  </div>

  <main class="relative min-h-screen lg:grid lg:grid-cols-2">
    
    <!-- LEFT PANEL desktop - navy image bg -->
    <div class="hidden lg:flex relative h-full flex-col p-10 overflow-hidden border-r-[4px] border-[var(--color-ink)] bg-gradient-to-br from-slate-900 via-navy-900 to-slate-950">
      <div class="absolute inset-0 z-0 opacity-30 bg-cover bg-center" style="background-image: url('<?php echo e(asset('images/default-campus.jpg')); ?>');"></div>
      <div class="z-20 flex items-center gap-3 text-white">
        <div class="w-12 h-12 bg-white rounded-xl border-2 border-[var(--color-ink)] shadow-[4px_4px_0_0_#1F2937] flex items-center justify-center p-1.5 shrink-0">
          <img src="<?php echo e($logoImage ?? asset('images/logo-dashboard.png')); ?>" alt="Logo" class="w-full h-full object-contain">
        </div>
        <p class="text-2xl font-bold font-heading tracking-wide">SIPAKAR</p>
      </div>
      <div class="z-20 mt-auto text-white">
        <blockquote class="space-y-4">
          <p class="text-3xl font-bold leading-tight font-heading">
            &ldquo;Sistem Pengelolaan KRS-KHS mempermudah proses akademik menjadi lebih efisien, terpusat, dan terintegrasi.&rdquo;
          </p>
          <footer class="font-mono text-sm font-bold uppercase tracking-widest text-[var(--color-accent-soft)]">
            ~ Politeknik Negeri Batam
          </footer>
        </blockquote>
      </div>
      <div class="pointer-events-none absolute inset-0 z-10 opacity-30">
        <svg class="h-full w-full text-white" viewBox="0 0 696 316" fill="none">
          <path d="M-20 -100 C 100 100 300 200 600 150" stroke="currentColor" stroke-width="2" stroke-opacity="0.2"/>
          <path d="M-40 -120 C 80 80 280 180 580 130" stroke="currentColor" stroke-width="1.5" stroke-opacity="0.3"/>
          <path d="M-60 -140 C 60 60 260 160 560 110" stroke="currentColor" stroke-width="1" stroke-opacity="0.4"/>
          <circle cx="100" cy="150" r="4" fill="currentColor" opacity="0.5"/>
          <circle cx="300" cy="200" r="6" fill="currentColor" opacity="0.3"/>
          <circle cx="600" cy="150" r="4" fill="currentColor" opacity="0.5"/>
        </svg>
      </div>
    </div>

    <!-- Right: Form -->
    <div class="relative flex min-h-screen flex-col justify-center p-6 lg:p-12 overflow-hidden z-10">
      
      <!-- Radials desktop only -->
      <div aria-hidden="true" class="hidden lg:block absolute inset-0 isolate -z-10 opacity-60">
        <div class="absolute top-0 right-0 h-[600px] w-[600px] -translate-y-1/2 translate-x-1/3 rounded-full bg-[radial-gradient(circle_at_50%_50%,var(--color-primary-soft)_0%,transparent_60%)]"></div>
        <div class="absolute bottom-0 left-0 h-[600px] w-[600px] translate-y-1/3 -translate-x-1/3 rounded-full bg-[radial-gradient(circle_at_50%_50%,var(--color-accent-soft)_0%,transparent_60%)]"></div>
      </div>

      <!-- Form Wrapper - Glass mobile, round arrow left top-left inside -->
      <div class="main-container mx-auto w-full max-w-[420px] space-y-8 rounded-3xl p-8 shadow-2xl lg:shadow-none lg:p-0 lg:bg-transparent z-20 relative">
        
        <!-- Arrow left - hitam -->
        <div class="absolute left-4 top-4 lg:hidden z-30 pt-2">
          <a href="/" class="group inline-flex items-center -ml-2 text-[var(--color-ink)]/90 hover:text-[var(--color-ink)] transition-all hover:-translate-x-1">
            <i class="fas fa-chevron-left text-xl group-hover:-translate-x-1 transition-transform duration-200"></i>
          </a>
        </div>
        
        <!-- Mobile Logo - font hitam -->
        <div class="flex items-center gap-3 mb-2 lg:hidden">
          <div class="w-12 h-12 bg-white rounded-xl border-2 border-[var(--color-ink)] shadow-[4px_4px_0_0_#1F2937] flex items-center justify-center p-1.5 shrink-0">
            <img src="<?php echo e($logoImage ?? asset('images/logo-dashboard.png')); ?>" alt="Logo" class="w-full h-full object-contain">
          </div>
          <p class="text-2xl font-bold font-heading tracking-wide text-[var(--color-ink)]">SIPAKAR</p>
        </div>

        <!-- Heading -->
        <div class="flex flex-col space-y-2">
          <h1 class="font-heading text-3xl font-black tracking-tight text-[var(--color-ink)]">
            Masuk ke Akun
          </h1>
          <p class="text-[var(--color-ink)]/80 text-sm font-medium">
            Masukkan identitas akademik Anda.
          </p>
        </div>

        <form id="loginForm" method="POST" action="/login" novalidate class="space-y-5">
          <?php echo csrf_field(); ?>

          <div class="space-y-1.5">
            <label for="identifier" class="nb-label text-[var(--color-ink)]">NIM / NIK</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <i class="fas fa-id-card text-[var(--color-muted)]/50"></i>
              </div>
              <input
                type="text"
                id="identifier"
                name="identifier"
                value="<?php echo e(old('identifier')); ?>"
                placeholder="Masukan NIM atau NIK"
                autocomplete="off"
                class="nb-input !pl-11 w-full <?php $__errorArgs = ['identifier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> !border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              >
            </div>
            <?php $__errorArgs = ['identifier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <p class="text-xs text-red-400 lg:text-red-600 font-bold flex items-center gap-1 mt-1.5">
                <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

              </p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="space-y-1.5">
            <label for="password" class="nb-label text-[var(--color-ink)]">Kata Sandi</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <i class="fas fa-lock text-[var(--color-muted)]/50"></i>
              </div>
              <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan kata sandi"
                autocomplete="off"
                class="nb-input !pl-11 !pr-11 w-full <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> !border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              >
              <button type="button" id="togglePw" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[var(--color-muted)]/50 hover:text-[var(--color-ink)] transition-colors" aria-label="Tampilkan kata sandi">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <p class="text-xs text-red-400 lg:text-red-600 font-bold flex items-center gap-1 mt-1.5">
                <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

              </p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <button type="submit" id="btnLogin" class="nb-btn nb-btn-primary w-full h-[52px] text-base mt-4 relative text-white shadow-2xl hover:shadow-3xl lg:text-white lg:bg-[var(--color-primary-ink)] lg:shadow-none">
            <span class="bt">Masuk Sistem</span>
            <span class="bs hidden absolute inset-0 items-center justify-center"><i class="fas fa-spinner fa-spin text-xl"></i></span>
          </button>
        </form>
        
        <p class="text-center text-[var(--color-ink)]/80 text-xs font-medium pt-2">
          Gunakan kredensial yang terdaftar di <br/>Politeknik Negeri Batam
        </p>
      </div>

    </div>
  </main>

</body>
</html><?php /**PATH C:\Users\LENOVO T14\Documents\SistemPengolahanKRS-KHS\resources\views/auth/login.blade.php ENDPATH**/ ?>