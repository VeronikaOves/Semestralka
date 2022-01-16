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

	name.addEventListener('input', function(e) {
		var nameValue = name.value.trim();

		if(nameValue === '') {
			setErrorFor(name, 'Name cannot be blank');
		} else if (nameValue.length > 150) {
			setErrorFor(name, 'Name should be no more than 150 symbols');
	
		} else {
			setSuccessFor(name);
		}
	}, false);

	email.addEventListener('input', function(e) {
		var emailValue = email.value.trim();
		if(emailValue === '') {
			setErrorFor(email, 'Email cannot be blank');
		} else if (!isEmail(emailValue)) {
			setErrorFor(email, 'Invalid email');
		} else {
			setSuccessFor(email);
		}
		
	}, false);

	password.addEventListener('input', function(e) {
		var passwordValue = password.value.trim();
		if(passwordValue === '') {
			setErrorFor(password, 'Password cannot be blank');
		} else if(!strongPwd(passwordValue)){
			setErrorFor(password, 'Password must contain at least eight characters at least one number and both lower and uppercase letters')
		} else{
			setSuccessFor(password);
		}
	}, false);

	passwordRepeat.addEventListener('input', function(e) {
		var passwordValue = password.value.trim();
		var passwordRepeatValue = passwordRepeat.value.trim();
		if(passwordRepeatValue === '') {
			setErrorFor(passwordRepeat, 'Password cannot be blank');
		} else if(passwordValue !== passwordRepeatValue) {
			setErrorFor(passwordRepeat, 'Passwords do not match');
		} else{
			setSuccessFor(passwordRepeat);
		 }
	}, false);

}



function checkInputs() {
	var nameValue = name.value.trim();
	var emailValue = email.value.trim();
	var passwordValue = password.value.trim();
	var passwordRepeatValue = passwordRepeat.value.trim();
	var error = [];

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

	username2.addEventListener('input', function(e) {
		var emailValue = username2.value.trim();
		if(emailValue === '') {
			setErrorFor(username2, 'Email cannot be blank');
		} else if (!isEmail(emailValue)) {
			setErrorFor(username2, 'Invalid email');
		} else {
			setSuccessFor(username2);
		}

	}, false);

	password2.addEventListener('input', function(e) {
		var passwordValue = password2.value.trim();
		if(passwordValue === '') {
			setErrorFor(password2, 'Password cannot be blank');
		} else if(!strongPwd(passwordValue)){
			setErrorFor(password2, 'Password must contain at least eight characters at least one number and both lower and uppercase letters')
		} else{
			setSuccessFor(password2);
		}
	}, false);
	
}


function checkInputs1() {
	const error = [];
	var usernameValue = username2.value.trim();
	var passwordValue = password2.value.trim();
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

	text.addEventListener('input', function(e) {
		var textValue = text.value.trim();
		if (textValue.length < 2 || textValue.length > 500){ 
			var small = commentForm.querySelector('small');
			small.classList.add('notHiddenError')
			small.innerText = 'Comment must be at leat 2 symbols but no more than 500!';
		}
		else {
			var small = commentForm.querySelector('small');
			small.classList.toggle('notHiddenError')
			small.innerText = '';
		}
	}, false);
}

function checkComment(){
	const error = [];
	var textValue = text.value.trim();
	var rating = document.querySelector('input[name="rating"]').value;
	if (textValue.length < 2 || textValue.length > 500){
		error.push(1);
		var small = commentForm.querySelector('small');
		small.classList.add('notHiddenError')
		small.innerText = 'Comment must be at leat 2 symbols but no more than 500!';

	}

	if (rating !=1 && rating !=2 && rating !=3 && rating !=4 && rating !=5){
		error.push(1);
		var small = commentForm.querySelector('small');
		small.classList.add('notHiddenError')
		small.innerText = 'Rating is not valid';
	}

	if (rating == ''){
		error.push(1);
		var small = commentForm.querySelector('small');
		small.classList.add('notHiddenError')
		small.innerText = 'You need to choose rating!';
	}

	return error
}



// comment sort validation

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


// addrecipe validation
const addRecipeForm = document.getElementById('addRecipe');
const recipeName = document.getElementById('recipeName');
const recipeDesc = document.getElementById('recipeDescriptionNewRecipe');
const img = document.getElementById('img');

if (addRecipeForm){
	addRecipeForm.addEventListener('submit', function(e) {
		e.preventDefault();
		if (checkNewRecipe().length == 0) {
			this.submit();
		}
	}, false);


	recipeName.addEventListener('input', function(e) {
		var textValue = recipeName.value.trim();
		if (textValue.length < 2 || textValue.length > 250){ 
			setErrorFor(recipeName, 'Recipe name must be at leat 2 symbols but no more then 250!');
		}
		else {
			setSuccessFor(recipeName);
		}
	}, false);

	recipeDesc.addEventListener('input', function(e) {
		var descValue = recipeDesc.value.trim();
		if (descValue.length < 50 || descValue.length > 2000){ 
			setErrorFor(recipeDesc,'Description should be at least 50 symbols but no more then 2000!');
		}
		else {
			setSuccessFor(recipeDesc);
		}
	}, false);


}

function checkNewRecipe(){
	const error = [];
	var textValue = recipeName.value.trim();
	var descValue = recipeDesc.value.trim();

	if (textValue.length < 2 || textValue.length > 250){
		error.push(1);
		setErrorFor(recipeName, 'Recipe name must be at leat 2 symbols but no more then 250!');
	}
	else {
		setSuccessFor(recipeName);
	}

	if (descValue.length < 50 || descValue.length > 2000){
		error.push(1);
		setErrorFor(recipeDesc, 'Recipe name must be at leat 2 symbols but no more then 250!');
	}
	else {
		setSuccessFor(recipeDesc);
	}


	return error
}