const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const containerRegister = document.getElementById('containerRegister');

signUpButton.addEventListener('click', () => {
	containerRegister.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	containerRegister.classList.remove("right-panel-active");
});