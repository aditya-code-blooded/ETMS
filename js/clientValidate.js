/*
	Javascript file to validate user's form data before submitting it to server
*/

/*
	The below function returns an appropriate error message for the input password.
	If everything went right, then the literal 'true' is returned
*/
function checkPassword(passwd) {
    
	/*
		This function enforces the following constraints on the password:
		(i) Password length must lie between [7,20]
		(ii) Must contain atleast one number
		(iii) Must contain atleast one letter from the english alphabet (irrespective of case)
		(iv) Must contain atleast one special character (!,(,),@,#,$,%,^,&,*,/,\,[,],{,},:,;,',`,?,+,-)
		(i.e. almost all special characters present on the keyboard)
	*/
    
    if (passwd.length < 6)
        return "Your password is too short. It must be atleast 7 characters.";
    else if (passwd.length > 20)
        return "Your password is too long. It must be at maximum 20 characters";
    else if (passwd.search(/[0-9]/) === -1)
        return "Your password must contain atleast one number";
    else if (passwd.search(/[a-zA-Z]/) == -1)
        return "Your password must contain atleast one letter from the alphabet";
    else if (passwd.search(/[!@#$%^&*]/) == -1)
        return "Your password must contain atleast one special character";
    return true;

}

// This function validates username (It uses the regex for username as specified by the global variable)
function validateUserName(userName,userNameError) {
	if(!userNamePattern.test(userName.value)) {
		userNameError.innerHTML = USER_NAME_ERROR;
		userName.style.borderColor = "red";
		return false;
	}
	return true;
}

// This function validates password (It uses the utility function checkPassword to retrieve specific error messages)
function validatePassword(password,passwordError) {
	var result = checkPassword(password.value);
	if(result === true)
		return true;
	else {
		passwordError.innerHTML = PASSWORD_ERROR;
		password.style.borderColor = "red";
		return false;
	}
}

// A function to validate firstName (It uses the regex for firstName as specified by the global variable)
function validateFirstName(firstName,firstNameError) {
	if(!firstNamePattern.test(firstName.value)) {
		firstNameError.innerHTML = FIRST_NAME_ERROR;
		firstName.style.borderColor = "red";
		return false;
	}
	return true;
}

// A function to validate lastName (It uses the regex for lastName as specified by the global variable)
function validateLastName(lastName,lastNameError) {
	// If last name is empty then we need not validate (and therefore we return true)
	// Note: LastName is not mandatory in the database too! (see the schema)
	if(lastName !== "" && !lastNamePattern.test(lastName.value)) {
		lastNameError.innerHTML = LAST_NAME_ERROR;
		lastName.style.borderColor = "red";
		return false;
	}
	return true;
}

// A function to validate emailAddress (It uses the regex for emailAddress as specified by the global variable)
function validateEmail(emailAddress,emailError) {
	if(!emailPattern.test(emailAddress.value)) {
		emailError.innerHTML = EMAIL_ERROR;
		emailError.style.borderColor = "red";
		return false;
	}
	return true;
}

// A function to validate a string which represents a college name
function validateCollege(college) {
	if(college.length > 50)
		return COLLEGE_ERROR;
	var spaces = 0;
	for(var i = 0;i < college.length;++i) {
		if(!((college[i] >= 'a' && college[i] <= 'z')
			|| (college[i] >= 'A' && college[i] <= 'Z'))) {
			if(college[i] = ' ')
				spaces++;
			else
				return COLLEGE_ERROR;
		}
	}
	if(spaces === college.length)
		return COLLEGE_ERROR;

	return SUCCESSFUL_OPR;
}

// A function to validate a string which represents an address
function validateAddress(address) {
	if(address.length > 255)
		return ADDRESS_ERROR;
	var spaces = 0;
	for(var i = 0;i < address.length;++i) {
		if(!((address[i] >= 'a' && address[i] <= 'z')
			|| (address[i] >= 'A' && address[i] <= 'Z')
			|| address[i] === ',' || address[i] === ':'
			|| (address[i] >= '0' && address[i] <= '9'))) {
			if(address[i] = ' ')
				spaces++;
			else
				return ADDRESS_ERROR;
		}
	}
	if(spaces === address.length)
		return ADDRESS_ERROR;

	return SUCCESSFUL_OPR;
}

// A function to validate a string which represents a contact number
function validateContact(contact) {
	if(contact.length != 10)
		return CONTACT_ERROR;
	for(var i = 0;i < contact.length;++i)
		if(!(contact[i] >= '0' && contact[i] <= '9'))
			return CONTACT_ERROR;
	return SUCCESSFUL_OPR;
}

// A function to validate a string which represents an email address
// (This is used in profile.html while updating the user)
function validateEmail2(email) {
	if(!emailPattern.test(email))
		return EMAIL_ERROR;
	else
		return SUCCESSFUL_OPR;
}

// A function which tests whether both the passwords match
function passwordsMatch(password,retypePassword) {
	if(password.value !== retypePassword.value) {
		retypePasswordError.innerHTML = PASSWORD_MATCH_ERROR;
		retypePassword.style.borderColor = "red";
		return false;
	}
	return true;
}

// A function used to validate the login page (i.e. index.html)
// It validates only the username and password
function validateLogin() {
	var userName = document.getElementById("userName");
	var password = document.getElementById("password");

	/*
		The following variables are used to populate error messages
		if something goes wrong during the AJAX call
	*/
	var userNameError = document.getElementById("userNameError");
	var passwordError = document.getElementById("passwordError");

	// Validate input
	var result = validateUserName(userName,userNameError) && validatePassword(password,passwordError);

	// If the input is valid, only then perform AJAX call
	if(result) {
		$.post('php/login_authenticate.php', { userName: userName.value, password: password.value}, 
			function(returnStatus){
		    	console.log("AJAX call: " + returnStatus);
		    	if(returnStatus === SUCCESSFUL_OPR) // Login successful
		    		window.location.href = HOME_PAGE_URL; // Redirect to home page
		    	else if(returnStatus === INCORRECT_PASSWORD) {
		    		// Password is incorrect
		    		passwordError.innerHTML = INCORRECT_PASSWORD_ERROR; // Set the text in the span tag
		    		password.style.borderColor = "red";
		    	}
		    	else if(returnStatus === UNREGISTERED_USER) {
		    		// User not signed up
		    		userNameError.innerHTML = UNREGISTERED_USER_ERROR; // Set the text in the span tag
		    		userName.style.borderColor = "red";
		    	}
		    	else if(returnStatus === INVALID_USER_NAME) {
		    		// UserName did not pass the regex test at the server end
		    		userNameError.innerHTML = USER_NAME_ERROR;
		    		userName.style.borderColor = "red";
		    	}
		    	else if(returnStatus === INVALID_PASSWORD) {
		    		// Password did not pass the regex test at the server end
		    		passwordError.innerHTML = PASSWORD_ERROR;
		    		password.style.borderColor = "red";
		    	}
		    	else {
		    		// Internal Database Error (Normally this case shouldn't occur)
		    		userNameError.innerHTML = "FATAL ERROR: " + returnStatus;
		    	}
		});
	}
	/*
		Since we are making an AJAX call, we should not return true. If we return true then the html
		form's action attribute will get executed, thereby calling login_authenticate.php
		However, if JavaScript is disabled, this script won't get executed in the first place and hence
		php will takeover the validation
	*/
	return false;
}

/*
	A function used to validate the signup page (i.e. signup.html)
	It validates the following fields:
	(i) userName
	(ii) password
	(iii) firstName
	(iv) lastName (This parameter is optional, user can leave it blank)
	(v) gender
	(vi) emailAddress
*/
function validateSignUp() {
	var firstName = document.getElementById("firstName");
	var lastName = document.getElementById("lastName");
	
	// Get the selected Gender (A bit complicated)
	var selectedGender = "";
	var maleGender = document.getElementById("maleGender");
	var femaleGender = document.getElementById("femaleGender");
	if(maleGender.checked)
		selectedGender = maleGender.value;
	else if(femaleGender.checked)
		selectedGender = femaleGender.value;


	var emailAddress = document.getElementById("emailAddress");
	var userName = document.getElementById("userName");
	var password = document.getElementById("password");
	var retypePassword = document.getElementById("retypePassword");

	/*
		The following variables are used to populate error messages
		if something goes wrong during the AJAX call
	*/
	var userNameError = document.getElementById("userNameError");
	var passwordError = document.getElementById("passwordError");
	var firstNameError = document.getElementById("firstNameError");
	var lastNameError = document.getElementById("lastNameError");
	var genderError = document.getElementById("genderError");
	var emailError = document.getElementById("emailError");
	var retypePasswordError = document.getElementById("retypePasswordError");

	// If any one of the field (except lastName) is empty, then display error message
	if(firstName.value === "" || selectedGender === "" || emailAddress.value === ""
		|| userName.value === "" || password.value === "" || retypePassword.value === "") {
		// Test message
		document.getElementById("emptyFormError").innerHTML = FILL_THE_ENTIRE_FORM_ERROR;
		return false;
	}

	var result = validateFirstName(firstName,firstNameError) &&
			validateLastName(lastName,lastNameError) &&
			validateEmail(emailAddress,emailError) &&
			validateUserName(userName,userNameError) &&
			validatePassword(password,passwordError) &&
			passwordsMatch(password,retypePassword);

	// If the input is valid perform AJAX call
	if(result) {
		$.post('php/signup_authenticate.php',
			{ 	
				userName: userName.value,
				password: password.value,
				firstName: firstName.value,
				lastName: lastName.value,
				emailAddress: emailAddress.value,
				gender: selectedGender,
				retypePassword: retypePassword.value
			}, 
			function(returnStatus){
		    	console.log("AJAX call callback: " + returnStatus);
		    	if(returnStatus === USER_ALREADY_REGISTERED) {
		    		// The userName is already registered (error)
		    		userNameError.innerHTML = USER_ALREADY_REGISTERED_ERROR;
		    		userName.style.borderColor = "red";
		    	}
		    	else if(returnStatus === EMAIL_ALREADY_REGISTERED) {
		    		// The emailAddress is already registered (error)
		    		emailError.innerHTML = EMAIL_ALREADY_REGISTERED_ERROR;
		    		emailAddress.style.borderColor = "red";
		    	}
		    	else if(returnStatus === SUCCESSFUL_OPR) // Signup successful
		    		window.location.href = HOME_PAGE_URL; // Redirect to home page
		    	else {
		    		// Internal Database Error (Normally this case shouldn't occur)
		    		userNameError.innerHTML = "FATAL ERROR: " + returnStatus;
		    	}
		});
	}

	/*
		Since we are making an AJAX call, we should not return true. If we return true then the html
		form's action attribute will get executed, thereby calling signup_authenticate.php
		However, if JavaScript is disabled, this script won't get executed in the first place and hence
		php will takeover the validation
	*/
	return false;
}

// A function which validates the amount and returns appropriate status (which are self explanatory)
function validateAmount(amount) {
	var length = amount.length;
	if(length > 7)
		return AMOUNT_EXCEEDED;
	
	var zeroEncountered = false;
	for(var i = 0;i < length;++i) {
		if(amount[i] === '0') {
			if(!zeroEncountered)
				return INVALID_AMOUNT;
		}
		else if(!(amount[i] >= '1' && amount[i] <= '9'))
			return INVALID_AMOUNT;
		zeroEncountered = true;
	}
	return SUCCESSFUL_OPR;
}

// A function which validates the description and returns appropriate status (which are self explanatory)
function validateDescription(desc) {
	var length = desc.length;
	if(length > 200)
		return DESC_LENGTH_EXCEEDED;
	for(var i = 0;i < length;++i) {
		switch(desc[i]) {
			case '<':
			case '>':
			case '/':
			case '\\':
			case '!':
			case '@':
			case '#':
			case '$':
			case '%':
			case '^':
			case '{':
			case '}': return INVALID_DESC;
		}
	}
	return SUCCESSFUL_OPR;
}