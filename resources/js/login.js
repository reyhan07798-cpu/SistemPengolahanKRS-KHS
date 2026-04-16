// Toggle password
document.getElementById('togglePw').addEventListener('click', function () {
  const input = document.getElementById('password');
  const isPw = input.type === 'password';
  input.type = isPw ? 'text' : 'password';
  this.querySelector('i').className = isPw ? 'fas fa-eye-slash' : 'fas fa-eye';
});

document.getElementById('loginForm').addEventListener('submit', function () {
  document.getElementById('btnLogin').classList.add('loading');
});

const autoToast = document.getElementById('toastAuto');
if (autoToast) {
  setTimeout(function () {
    autoToast.style.animation = 'tOut 0.3s ease forwards';
    autoToast.addEventListener('animationend', function () {
      autoToast.remove();
    });
  }, 4000);
}