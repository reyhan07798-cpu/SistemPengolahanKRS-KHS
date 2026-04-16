<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Sistem Pengelolaan KRS-KHS</title>
  <link rel="icon" type="image/png" href="{{ asset('images/Logo-Polibatam.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  @vite(['resources/css/login.css', 'resources/js/login.js'])
</head>
<body>

  <div class="bg-campus"
     style="background: url('{{ asset('images/default-campus.jpg') }}') center/cover no-repeat;">
</div>

  {{-- Toast untuk flash message dari backend --}}
  <div class="toast-wrap">
    @if (session('success'))
      <div class="toast success" id="toastAuto">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if (session('error'))
      <div class="toast error" id="toastAuto">
        <i class="fas fa-times-circle"></i>
        <span>{{ session('error') }}</span>
      </div>
    @endif
  </div>

  <div class="login-card" id="loginCard">
    <div class="logo">
      <div class="logo-box">
        <img src="{{ $logoImage ?? asset('images/logo-polibatam.png') }}" alt="Logo Polibatam">
      </div>
      <div class="logo-name">
        <span>Sistem KRS-KHS</span>
        <span>Politeknik Negeri Batam</span>
      </div>
    </div>

    <div class="form-header">
      <h2>Masuk ke Akun</h2>
      <p>Masukkan NIM/NIK</p>
    </div>

    <form id="loginForm" method="POST" action="/login" novalidate>
      @csrf

      <div class="input-group">
        <label for="identifier">NIM / NIK</label>
        <div class="input-wrap">
          <i class="fas fa-id-card fi"></i>
          <input
            type="text"
            id="identifier"
            name="identifier"
            value="{{ old('identifier') }}"
            placeholder="Masukan NIM atau NIK"
            autocomplete="off"
            @error('identifier') class="input-error" @enderror
          >
        </div>
        @error('identifier')
          <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $message }}</span>
          </div>
        @enderror
      </div>

      <div class="input-group">
        <label for="password">Kata Sandi</label>
        <div class="input-wrap">
          <i class="fas fa-lock fi"></i>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Masukkan kata sandi"
            autocomplete="off"
            @error('password') class="input-error" @enderror
          >
          <button type="button" class="toggle-pw" id="togglePw" aria-label="Tampilkan kata sandi">
            <i class="fas fa-eye"></i>
          </button>
        </div>
        @error('password')
          <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $message }}</span>
          </div>
        @enderror
      </div>

      <button type="submit" class="btn-login" id="btnLogin">
        <span class="bt">Masuk</span>
        <span class="bs"><div class="spinner"></div></span>
      </button>
    </form>


</body>
</html>