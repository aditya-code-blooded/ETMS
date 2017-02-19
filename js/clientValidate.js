/*
	Javascript file to validate user's form data before submitting it to server
*/

// Note: Comment out useless console messages when testing is done

/*
	A regex for username input. It enforces that:
	(i) Username length must lie between [7,20]
	(ii) Must start with a letter
	(iii) Should not contain white spaces or special characters
*/ 
var userNamePattern = /^[a-z][\w\.]{6,19}$/i;

// A regex for firstName input. Its length must lie in between [4,20] and must only contain letters from the alphabet
var firstNamePattern = /^[a-zA-Z]{3,19}$/;

// A regex for lastName input. Its length must lie in between [1,20] and must only contain letters from the alphabet
var lastNamePattern = /^[a-zA-Z]{0,19}$/;

// A regex for email address
var emailPattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

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
function validateUserName(userName) {
	if(!userNamePattern.test(userName)) {
		// Test message
		console.log("Username did not pass regex test");
		return false;
	}
	return true;
}

// This function validates password (It uses the utility function checkPassword to retrieve specific error messages)
function validatePassword(password) {
	var result = checkPassword(password);
	// Test message
	console.log("Password is validated at client side: " + result);
	if(result === true)
		return true;
}

// A function to validate firstName (It uses the regex for firstName as specified by the global variable)
function validateFirstName(firstName) {
	if(!firstNamePattern.test(firstName)) {
		// Test message
		console.log("FirstName did not pass the regex test");
		return false;
	}
	return true;
}

// A function to validate lastName (It uses the regex for lastName as specified by the global variable)
function validateLastName(lastName) {
	// If last name is empty then we need not validate (and therefore we return true)
	// Note: LastName is not mandatory in the database too! (see the schema)
	if(lastName !== "" && !lastNamePattern.test(lastName)) {
		// Test message
		console.log("LastName did not pass the regex test");
		return false;
	}
	return true;
}

// A function to validate emailAddress (It uses the regex for emailAddress as specified by the global variable)
function validateEmail(emailAddress) {
	if(!emailPattern.test(emailAddress)) {
		// Test message
		console.log("Email address did not pass the regex test");
		return false;
	}
	return true;
}

// A function used to validate the login page (i.e. index.html)
// It validates only the username and password
function validateLogin() {
	var userName = document.getElementById("userName").value;
	var password = document.getElementById("password").value;

	// For testing
	console.log("Username entered: " + userName);
	console.log("Password entered: " + password);

	return validateUserName(userName) && validatePassword(password);
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
	var firstName = document.getElementById("firstName").value;
	var lastName = document.getElementById("lastName").value;
	var gender = document.getElementById("gender").value;
	var emailAddress = document.getElementById("emailAddress").value;
	var userName = document.getElementById("userName").value;
	var password = document.getElementById("password").value;
	var retypePassword = document.getElementById("retypePassword").value;

	// If any one of the field (except lastName) is empty, then display error message
	if(firstName === "" || gender === "" || emailAddress === ""
		|| userName === "" || password === "" || retypePassword === "") {
		// Test message
		console.log("Please fill the entire form");
		return false;
	}

	return validateFirstName(firstName) &&
			validateLastName(lastName) &&
			validateEmail(emailAddress) &&
			validateUserName(userName) &&
			validatePassword(password);

}
function forward_to_signup() {
	window.location.href = "http://localhost/ETMS/signup.html";
}