<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Sistem Pengelolaan KRS-KHS</title>
  <link rel="icon" type="image/png" href="{{ asset('images/Logo-Polibatam.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/js/login.js'])
  <style>
    .loading .bt { display: none; }
    .loading .bs { display: flex !important; }
    
    @keyframes tIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes tOut { from { opacity: 1; } to { opacity: 0; transform: translateX(30px); } }
    .toast-anim { animation: tIn 0.3s ease both; }
  </style>
</head>
<body class="font-sans antialiased text-[var(--color-ink)] bg-[var(--color-bg)]" style="font-family: 'Plus Jakarta Sans', sans-serif;">

  {{-- Toast untuk flash message dari backend --}}
  <div class="fixed top-5 right-5 z-50 flex flex-col gap-2">
    @if (session('success'))
      <div class="toast-anim bg-[var(--color-surface)] border-[3px] border-[var(--color-ink)] shadow-[4px_4px_0_0_var(--color-ink)] px-4 py-3 rounded-xl flex items-center gap-3" id="toastAuto">
        <i class="fas fa-check-circle text-[var(--color-success)] text-lg"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
      </div>
    @endif

    @if (session('error'))
      <div class="toast-anim bg-[var(--color-surface)] border-[3px] border-[var(--color-ink)] shadow-[4px_4px_0_0_var(--color-ink)] px-4 py-3 rounded-xl flex items-center gap-3" id="toastAuto">
        <i class="fas fa-times-circle text-red-500 text-lg"></i>
        <span class="text-sm font-bold">{{ session('error') }}</span>
      </div>
    @endif
  </div>

  <main class="relative min-h-screen lg:grid lg:grid-cols-2">
    
    <!-- LEFT COLUMN: Background Campus + Branding -->
    <div class="relative hidden h-full flex-col p-10 lg:flex overflow-hidden border-r-[4px] border-[var(--color-ink)] bg-[var(--color-primary-ink)]">
      
      <!-- Background Image -->
      <div class="absolute inset-0 z-0 opacity-40 mix-blend-overlay bg-cover bg-center" style="background-image: url('{{ asset('images/default-campus.jpg') }}');"></div>
      
      <!-- Gradient Overlay -->
      <div class="absolute inset-0 z-10 bg-gradient-to-t from-[var(--color-ink)] via-[var(--color-ink)]/60 to-transparent"></div>

      <!-- Header / Logo -->
      <div class="z-20 flex items-center gap-3 text-white">
        <div class="w-12 h-12 bg-white rounded-xl border-2 border-[var(--color-ink)] shadow-[4px_4px_0_0_#1F2937] flex items-center justify-center p-1.5 shrink-0">
          <img src="{{ $logoImage ?? asset('images/logo-dashboard.png') }}" alt="Logo" class="w-full h-full object-contain">
        </div>
        <p class="text-2xl font-bold font-heading tracking-wide">SIPAKAR</p>
      </div>

      <!-- Quote -->
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

      <!-- Floating Paths SVG Background -->
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

    <!-- RIGHT COLUMN: Login Form -->
    <div class="relative flex min-h-screen flex-col justify-center p-6 lg:p-12 overflow-hidden z-10">
      
      <!-- Abstract Radial Backgrounds -->
      <div aria-hidden="true" class="absolute inset-0 isolate -z-10 opacity-60">
        <div class="absolute top-0 right-0 h-[600px] w-[600px] -translate-y-1/2 translate-x-1/3 rounded-full bg-[radial-gradient(circle_at_50%_50%,var(--color-primary-soft)_0%,transparent_60%)]"></div>
        <div class="absolute bottom-0 left-0 h-[600px] w-[600px] translate-y-1/3 -translate-x-1/3 rounded-full bg-[radial-gradient(circle_at_50%_50%,var(--color-accent-soft)_0%,transparent_60%)]"></div>
      </div>

      <!-- Back Button -->
      <div class="absolute top-6 left-6 lg:top-8 lg:left-8">
        <a href="/" class="nb-btn nb-btn-secondary gap-2 px-4 py-2 text-sm">
          <i class="fas fa-chevron-left"></i> Kembali
        </a>
      </div>

      <!-- Form Wrapper (Without Card) -->
      <div class="mx-auto w-full max-w-[380px] space-y-8 relative z-20">
        
        <!-- Mobile Logo -->
        <div class="flex items-center gap-3 lg:hidden mb-2">
          <div class="w-12 h-12 bg-white rounded-xl border-2 border-[var(--color-ink)] shadow-[4px_4px_0_0_#1F2937] flex items-center justify-center p-1.5 shrink-0">
            <img src="{{ $logoImage ?? asset('images/logo-dashboard.png') }}" alt="Logo" class="w-full h-full object-contain">
          </div>
          <p class="text-2xl font-bold font-heading tracking-wide">SIPAKAR</p>
        </div>

        <!-- Heading -->
        <div class="flex flex-col space-y-2">
          <h1 class="font-heading text-3xl font-black tracking-tight text-[var(--color-ink)]">
            Masuk ke Akun
          </h1>
          <p class="text-[var(--color-ink)]/70 text-sm font-medium">
            Masukkan identitas akademik Anda.
          </p>
        </div>

        <form id="loginForm" method="POST" action="/login" novalidate class="space-y-5">
          @csrf

          <div class="space-y-1.5">
            <label for="identifier" class="nb-label">NIM / NIK</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <i class="fas fa-id-card text-[var(--color-ink)]/50"></i>
              </div>
              <input
                type="text"
                id="identifier"
                name="identifier"
                value="{{ old('identifier') }}"
                placeholder="Masukan NIM atau NIK"
                autocomplete="off"
                class="nb-input !pl-11 w-full @error('identifier') !border-red-500 @enderror"
              >
            </div>
            @error('identifier')
              <p class="text-xs text-red-600 font-bold flex items-center gap-1 mt-1.5">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
              </p>
            @enderror
          </div>

          <div class="space-y-1.5">
            <label for="password" class="nb-label">Kata Sandi</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <i class="fas fa-lock text-[var(--color-ink)]/50"></i>
              </div>
              <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan kata sandi"
                autocomplete="off"
                class="nb-input !pl-11 !pr-11 w-full @error('password') !border-red-500 @enderror"
              >
              <button type="button" id="togglePw" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[var(--color-ink)]/50 hover:text-[var(--color-ink)] transition-colors" aria-label="Tampilkan kata sandi">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            @error('password')
              <p class="text-xs text-red-600 font-bold flex items-center gap-1 mt-1.5">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
              </p>
            @enderror
          </div>

          <button type="submit" id="btnLogin" class="nb-btn nb-btn-primary w-full h-[52px] text-base mt-4 relative">
            <span class="bt">Masuk Sistem</span>
            <span class="bs hidden absolute inset-0 items-center justify-center"><i class="fas fa-spinner fa-spin text-xl"></i></span>
          </button>
        </form>
        
        <p class="text-center text-[var(--color-ink)]/60 text-xs font-medium pt-2">
          Gunakan kredensial yang terdaftar di <br/>Politeknik Negeri Batam
        </p>
      </div>

    </div>
  </main>

</body>
</html>