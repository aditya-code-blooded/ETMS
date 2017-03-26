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

	/*
		The below function returns an appropriate error message for the input password.
		If everything went right, then the literal 'true' is returned
	*/
	function validatePassword($password) {
		/*
			This function enforces the following constraints on the password:
			(i) Password length must lie between [7,20]
			(ii) Must contain atleast one number
			(iii) Must contain atleast one letter from the english alphabet (irrespective of case)
			(iv) Must contain atleast one special character (!,(,),@,#,$,%,^,&,*,/,\,[,],{,},:,;,',`,?,+,-)
			(i.e. almost all special characters present on the keyboard)
		*/

	    $length = strlen($passwd);

	    if ($length < 6)
	        return "Your password is too short. It must be atleast 7 characters.";
	    else if ($length > 20)
	        return "Your password is too long. It must be at maximum 20 characters";
	    else if (preg_match('/[0-9]/',$passwd) !== 1)
	        return "Your password must contain atleast one number";
	    else if (preg_match('/[a-zA-Z]/',$passwd) !== 1)
	        return "Your password must contain atleast one letter from the alphabet";
	    else if (preg_match('/[!@%^&#*]/',$passwd) !== 1)
	        return "Your password must contain atleast one special character";
	    return true;
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