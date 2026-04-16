// Toggle password
document.getElementById('togglePw').addEventListener('click', function () {
  const input = document.getElementById('password');
  const isPw = input.type === 'password';
  input.type = isPw ? 'text' : 'password';
  this.querySelector('i').className = isPw ? 'fas fa-eye-slash' : 'fas fa-eye';
});

// Loading state saat submit
document.getElementById('loginForm').addEventListener('submit', function () {
  document.getElementById('btnLogin').classList.add('loading');
});

// Auto-hapus toast dari flash message setelah 4 detik
const autoToast = document.getElementById('toastAuto');
if (autoToast) {
  setTimeout(function () {
    autoToast.style.animation = 'tOut 0.3s ease forwards';
    autoToast.addEventListener('animationend', function () {
      autoToast.remove();
    });
  }, 4000);
}