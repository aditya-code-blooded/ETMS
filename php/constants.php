<?php

	# This .php file contains list of all constants which will be used by other php files

	# Connection parameters for the mysql database
	# Need to find a better way to secure them (instead of directly embedding in the source code)
	$dbServerName = "localhost";
	$dbUserName = "root";
	$dbPassword = "Root1996!";
	$dbName = "etms_db";

	# The following constants are used for specifying return status for several functions
	# These variables are also used by clientValidate.js file
	# WARNING: Do not change their names or their values
	define("SUCCESSFUL_OPR", "Successful operation");
	define("INCORRECT_PASSWORD", "Password is incorrect for the given username.");
	define("UNREGISTERED_USER", "The user is not registered. Try signing up and creating a new account.");
	define("NEW_EMAIL", "The email address is new. Not yet inserted into database");
	define("EMAIL_ALREADY_REGISTERED", "The email address is already present in the database");
	define("ERROR_VALUE", "Error while accessing/retrieving data from database.");
	define("INVALID_USER_NAME", "Invalid username");
	define("INVALID_PASSWORD", "Invalid password");
	define("INVALID_FIRST_NAME", "Invalid first name");
	define("INVALID_LAST_NAME", "Invalid last name");
	define("INVALID_EMAIL", "Invalid email");
	define("NEW_USER", "New user. Not yet added into database");
	define("USER_ALREADY_REGISTERED","This username is already present in the database");
	define("EMAIL_ADDRESS_NOT_FOUND","The user is registered, but the email address is not present in the database");

	// Error message-strings which will be displayed on the console whenever something goes wrong
	define("USER_NAME_ERROR"," * Username must contain atleast 7 and maximum 20 characters without spaces and must start with letters and contain only alphanumeric characters");
	define("PASSWORD_ERROR"," * Password length must be between 7 and 20. Should contain atleast one letter, one number and a special character");
	define("FIRST_NAME_ERROR"," * Firstname length must be between 4 and 20. Should contain only letters without spaces");
	define("LAST_NAME_ERROR"," * Lastname length must be between 1 and 20. Should contain only letters without spaces");
	define("EMAIL_ERROR"," * Enter a valid email");
	define("PASSWORD_MATCH_ERROR"," * Passwords do not match");
	define("INCORRECT_PASSWORD_ERROR"," * Incorrect password");
	define("UNREGISTERED_USER_ERROR"," * You haven't registered. Try Signing up");
	define("EMAIL_ALREADY_REGISTERED_ERROR"," * This email is already registered, choose another.");
	define("FILL_THE_ENTIRE_FORM_ERROR","Please fill the entire form");

	# Constants for web pages
	define("SIGN_UP_URL","http://localhost/ETMS/signup.html");
	define("HOME_PAGE_URL","http://localhost/ETMS/home.html");
	define("LOGIN_PAGE_URL","http://localhost/ETMS/index.html");
	define("ERROR_PAGE_URL","http://localhost/ETMS/error.html");

	$ERROR_VALUE_DESC = ""; # This variable contains the description of the error message when ERROR_VALUE is returned
?>