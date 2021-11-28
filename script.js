//sign up validation
const form1 = document.getElementById('signUp');
const name = document.getElementById('name');
const email = document.getElementById('email');
const username = document.getElementById('uid1');
const password = document.getElementById('pwd1');
const passwordRep = document.getElementById('pwdRep');

if (form1){
	form1.addEventListener('submit', e => {
		e.preventDefault();
		
		checkInputs();
	});
}




function checkInputs() {
	const nameValue = name.value.trim();
	const usernameValue = username.value.trim();
	const emailValue = email.value.trim();
	const passwordValue = password.value.trim();
	const passwordRepValue = passwordRep.value.trim();
	
	if(usernameValue === '') {
		setErrorFor(username, 'Username cannot be blank');
	} else if ((usernameValue.length < 2) || (usernameValue.length > 40)) {
		setErrorFor(username, 'User name should be at least 2 characters but no more than 40');

	} else {
		setSuccessFor(username);
	}

	if(nameValue === '') {
		setErrorFor(name, 'Name cannot be blank');
	} else if (nameValue.length > 150) {
		setErrorFor(name, 'Name should be  no more than 150');

	} else {
		setSuccessFor(name);
	}
	
	if(emailValue === '') {
		setErrorFor(email, 'Email cannot be blank');
	} else if (!isEmail(emailValue)) {
		setErrorFor(email, 'Not a valid email');
	} else {
		setSuccessFor(email);
	}
	
	if(passwordValue === '') {
		setErrorFor(password, 'Password cannot be blank');
	} else if(!strongPwd(passwordValue)){
		setErrorFor(password, 'Password must contain at least eight characters, at least one number and both lower and uppercase letters')
	} else{
		setSuccessFor(password);
	}
	
	if(passwordRep === '') {
		setErrorFor(passwordRep, 'Password cannot be blank');
	} else if(passwordValue !== passwordRepValue) {
		setErrorFor(passwordRep, 'Passwords do not match');
	} else{
		setSuccessFor(passwordRep);
	}
}

function strongPwd(password) {
	if (password.match(/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})/)){
		return true;
	}
	else {
		return false;
	}
}


function setErrorFor(input, message) {
	const formControl = input.parentElement;
	const small = formControl.querySelector('small');
	formControl.className = 'form-control error';
	small.innerText = message;
}

function setSuccessFor(input) {
	const formControl = input.parentElement;
	formControl.className = 'form-control success';
}
	
function isEmail(email) {
	return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email);
}


//log in validation
const form2 = document.getElementById('logIn');
const username2 = document.getElementById('uid2');
const password2 = document.getElementById('pwd2');

if (form2) {
	form2.addEventListener('submit', e => {
		e.preventDefault();
		
		checkInputs1();
	});
}


function checkInputs1() {
	const usernameValue = username2.value.trim();
	const passwordValue = password2.value.trim();;
	
	if(usernameValue === '') {
		setErrorFor(username2, 'Username cannot be blank');
	} else {
		setSuccessFor(username2);
	}
	
	
	if(passwordValue === '') {
		setErrorFor(password2, 'Password cannot be blank');
	} else {
		setSuccessFor(password2);
	}
	
}


