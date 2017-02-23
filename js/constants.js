// This file groups together all the constants which will be used by the javascript files
// Note: Do not change anything since this file depends on several other files including /php/.. files

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

// The following variables are constants present in the databaseQueries.php file
// We use them while making AJAX requests
// WARNING: Do not change them
var SUCCESSFUL_OPR = "Successful operation";
var INCORRECT_PASSWORD = "Password is incorrect for the given username.";
var UNREGISTERED_USER = "The user is not registered. Try signing up and creating a new account.";
var NEW_EMAIL = "The email address is new. Not yet inserted into database";
var EMAIL_ALREADY_REGISTERED = "The email address is already present in the database";
var ERROR_VALUE = "Error while accessing/retrieving data from database.";
var INVALID_USER_NAME = "Invalid username";
var INVALID_PASSWORD = "Invalid password";
var USER_ALREADY_REGISTERED = "This username is already present in the database";

// Error message-strings which will be displayed on the UI whenever something goes wrong
var USER_NAME_ERROR = " * Username must contain atleast 7 and maximum 20 characters without spaces and must start with letters and contain only alphanumeric characters";
var PASSWORD_ERROR = " * Password length must be between 7 and 20. Should contain atleast one letter, one number and a special character";
var FIRST_NAME_ERROR = " * Firstname length must be between 4 and 20. Should contain only letters without spaces";
var LAST_NAME_ERROR = " * Lastname length must be between 1 and 20. Should contain only letters without spaces";
var EMAIL_ERROR = " * Enter a valid email";
var PASSWORD_MATCH_ERROR = " * Passwords do not match";
var INCORRECT_PASSWORD_ERROR = " * Incorrect password";
var UNREGISTERED_USER_ERROR = " * You haven't registered. Try Signing up";
var EMAIL_ALREADY_REGISTERED_ERROR = " * This email is already registered, choose another.";
var FILL_THE_ENTIRE_FORM_ERROR = "Please fill the entire form";
var USER_ALREADY_REGISTERED_ERROR = "This username has already been registered, choose another";


// URLS
var HOME_PAGE_URL = "http://localhost/ETMS/home.html";
var SIGN_UP_URL = "http://localhost/ETMS/signup.html";
var LOGIN_URL = "http://localhost/ETMS/index.html";