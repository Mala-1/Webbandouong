const popup = document.getElementById('popupForm');
const closeBtn = document.getElementById('closeForm');
const registerForm = document.getElementById('registerForm');
const loginForm = document.getElementById('loginForm');
const switchToLogin = document.getElementById('switchToLogin');
const switchToRegister = document.getElementById('switchToRegister');

closeBtn.addEventListener('click', () => {
  popup.style.display = 'none';
});

switchToLogin.addEventListener('click', () => {
  registerForm.style.display = 'none';
  loginForm.style.display = 'block';
});

switchToRegister.addEventListener('click', () => {
  loginForm.style.display = 'none';
  registerForm.style.display = 'block';
});