//sign up validation
const form1 = document.getElementById('signUp');
const name = document.getElementById('name');
const email = document.getElementById('email');
const password = document.getElementById('password1');
const passwordRepeat = document.getElementById('passwordRepeat');

if (form1){
	form1.addEventListener('submit', function(e) {
		e.preventDefault();
		if (checkInputs().length == 0) {
			this.submit();
		}
	}, false);
}


function checkInputs() {
	const nameValue = name.value.trim();
	const emailValue = email.value.trim();
	const passwordValue = password.value.trim();
	const passwordRepeatValue = passwordRepeat.value.trim();
	const error = [];

	if(nameValue === '') {
		setErrorFor(name, 'Name cannot be blank');
		error.push(1);
	} else if (nameValue.length > 150) {
		setErrorFor(name, 'Name should be no more than 150 symbols');
		error.push(1);

	} else {
		setSuccessFor(name);
	}
	
	if(emailValue === '') {
		setErrorFor(email, 'Email cannot be blank');
		error.push(1);
	} else if (!isEmail(emailValue)) {
		setErrorFor(email, 'Invalid email');
		error.push(1);
	} else {
		setSuccessFor(email);
	}
	
	if(passwordValue === '') {
		setErrorFor(password, 'Password cannot be blank');
		error.push(1);
	} else if(!strongPwd(passwordValue)){
		setErrorFor(password, 'Password must contain at least eight characters at least one number and both lower and uppercase letters')
		error.push(1);
	} else{
		setSuccessFor(password);
	}
	
	if(passwordRepeatValue === '') {
		setErrorFor(passwordRepeat, 'Password cannot be blank');
		error.push(1);
		return error
	} else if(passwordValue !== passwordRepeatValue) {
		setErrorFor(passwordRepeat, 'Passwords do not match');
		error.push(1);
		return error
	} else{
		setSuccessFor(passwordRepeat);
		return error
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
const username2 = document.getElementById('email2');
const password2 = document.getElementById('password2');

if (form2) {
	form2.addEventListener('submit', function(e) {
		e.preventDefault();
		if (checkInputs1().length == 0) {
			this.submit();
		}
	}, false);
}


function checkInputs1() {
	const error = [];
	const usernameValue = username2.value.trim();
	const passwordValue = password2.value.trim();
	if(usernameValue === '') {
		setErrorFor(username2, 'Email cannot be blank');
		error.push(1);
	} 
	else if(!isEmail(usernameValue)) {
		setErrorFor(username2, 'Email is not valid');
		error.push(1);
	}
	else {
		setSuccessFor(username2);
	}
	
	
	if(passwordValue === '') {
		setErrorFor(password2, 'Password cannot be blank');
		error.push(1);
		return error
	} 
	else if(!strongPwd(passwordValue)) {
		setErrorFor(password2, 'Password must contain at least eight characters at least one number and both lower and uppercase letters');
		error.push(1);
		return error
	}
	else {
		setSuccessFor(password2);
		return error
	}
	
}

// comment validation

const commentForm = document.getElementById('add-review');
const text = document.getElementById('comment');

if (commentForm){
	commentForm.addEventListener('submit', function(e) {
		e.preventDefault();
		if (checkComment().length == 0) {
			this.submit();
		}
	}, false);
}

function checkComment(){
	const error = [];
	const textValue = text.value.trim();
	const rating = document.querySelector('input[name="rating"]:checked').value;
	if (textValue.length < 2 || textValue.length > 500){
		error.push(1);
		const small = commentForm.querySelector('small');
		small.classList.add('notHiddenError')
		small.innerText = 'Comment must be at leat 2 symbols but no more than 500!';

	}

	if (![0,1,2,3,4,5].includes(rating)){
		error.push(1);
		const small = commentForm.querySelector('small');
		small.classList.add('notHiddenError')
		small.innerText = 'Rating is not valid';
	}

	return error
}


// sort validation

const sortForm = document.getElementById('sort');

if (sortForm){
	sortForm.addEventListener('submit', function(e) {
		e.preventDefault();
		if (checkSorting().length == 0) {
			this.submit();
		}
		checkSorting();
	}, false);
}

function checkSorting(){
	const error = [];
	var select = document.getElementById('sorting');
	const checkValue = select.options[select.selectedIndex].value;
	if (checkValue !== 'nothing' && checkValue !== 'rating low to high' && checkValue !== 'rating high to low' ) {
		error.push(1);
		const small = sortForm.querySelector('small');
		small.classList.add('notHiddenError')
		small.innerText = 'Sorting is not valid';
	}
	return error
}


// Ajax add or delete favorite recipe

function addToFavs(userid, recipeid) {
	const buttons = document.getElementById('favoriteButton');

	$.ajax({
		url:"/addToFavs.php",
		type:"POST",
		data: "userid=" + userid + "&recipeid=" + recipeid,
        success: function() {
            buttons.classList.add('is-favorite');
		}
})};

function deleteFromFavs(userid, recipeid) {
	const buttons = document.getElementById('favoriteButton');

	$.ajax({
		url:"/deleteFromFavs.php",
		type:"POST",
		data: "userid=" + userid + "&recipeid=" + recipeid,
        success: function() {
            buttons.classList.remove('is-favorite');
		}
	
})};

// switch color theme 
function darkMode(url){
	$.ajax({
		url:"/darkMode.php",
		type:"GET",
		success: function(){
			window.location.replace(url);
		}
	}
	)
};

