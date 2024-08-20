// SignUp Login Form Eye Toggle
const btnEyeToggle = document.getElementById('btnEyeToggle');
const eyeIcon = document.getElementById('eyeIcon')

btnEyeToggle.addEventListener('click', () => {
	const txtpassword = document.getElementById('password');

	if (txtpassword.getAttribute('type') === 'password') {
		txtpassword.setAttribute('type', 'text');
		eyeIcon.classList.remove("fa-eye-slash");
		eyeIcon.classList.add("fa-eye");
	} else {
		txtpassword.setAttribute('type', 'password');
		eyeIcon.classList.remove("fa-eye");
		eyeIcon.classList.add("fa-eye-slash");
	}
}); 
