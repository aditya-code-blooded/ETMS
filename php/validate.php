<?php

	# This script file contains functions which validate various inputs
	# Currently the functions which process input from login and signup pages are present

	# A function to test the input '$data' for SQL injection attacks
	function test_input($data) {
	  	$data = trim($data);
	  	$data = stripslashes($data);
	  	$data = htmlspecialchars($data);
	  	return $data;
	}

	# This function validates username (It uses the regex for username as specified by the global variable)
	function validateUserName($userName) {
		return preg_match('/^[A-Za-z][A-Za-z0-9]{6,19}$/',$userName);
	}

	// This function validates password (It uses the utility function checkPassword to retrieve specific error messages)
	function validatePassword($password) {
		return checkPassword($password);
	}

	// A function to validate firstName (It uses the regex for firstName as specified by the global variable)
	function validateFirstName($firstName) {
		return preg_match('/^[a-zA-Z]{3,19}$/',$firstName);
	}

	// A function to validate lastName (It uses the regex for lastName as specified by the global variable)
	function validateLastName($lastName) {
		// If last name is empty then we need not validate (and therefore we return true)
		// Note: LastName is not mandatory in the database too! (see the schema)
		return preg_match('/^[a-zA-Z]{0,19}$/',$lastName);
	}

	// A function to validate emailAddress (It uses the regex for emailAddress as specified by the global variable)
	function validateEmail($emailAddress) {
		return preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',$emailAddress);
	}

	// A function which tests whether both the passwords match
	function passwordsMatch($password,$retypePassword) {
		return (strcmp($password,$retypePassword) === 0);
	}
?>