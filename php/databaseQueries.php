<?php
	
	# This file contains all the php code to interact with the mySQL database
	# It is included in several other php files as a dependency

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

	$ERROR_VALUE_DESC = ""; # This variable contains the description of the error message when ERROR_VALUE is returned

	# A function to test the input '$data' for SQL injection attacks
	function test_input($data) {
	  	$data = trim($data);
	  	$data = stripslashes($data);
	  	$data = htmlspecialchars($data);
	  	return $data;
	}

	# A function which logs messages to the browser console
	function logMessage($msg) {
		echo "<script>console.log('" . $msg . "')</script>";
	}

	# This function is used to check whether the user with the credentials [userName,password] is present in the database
	# It returns three statuses depending on the following scenarios:
	# (i) If the password is wrong (i.e. the userName is present in the database) then it returns 'INCORRECT_PASSWORD'
	# (ii) If the userName is not present in the database then it returns 'UNREGISTERED_USER'
	# (iii) If there is any other error such as connecting to the database, etc then it returns 'ERROR_VALUE'
	# (iv) If successfull, then it returns 'SUCCESSFUL_OPR'
	function present($userName,$password) {

		# Open the connection to database
		$mysqli = mysqli_connect($GLOBALS['dbServerName'],$GLOBALS['dbUserName'],$GLOBALS['dbPassword'],$GLOBALS['dbName']);

		# If there is an error report it.
		if (mysqli_connect_error()) {
			$ERROR_VALUE_DESC = 'Error while connecting to database in present() function ' . mysqli_connect_errno() . ' ' . mysqli_connect_error();
			logMessage($ERROR_VALUE_DESC);
			return ERROR_VALUE;
		}

		# No error. Create a prepared statement
		
		$query = "SELECT password FROM users WHERE user_name = ?";
		$retrievedPassword = ""; # The password which will be retrieved from the database
		if($stmt = mysqli_prepare($mysqli,$query)) {

			# bind the parameter to the wildcard entry in the query
		    mysqli_stmt_bind_param($stmt, "s", $userName);
		    # execute the query
		    mysqli_stmt_execute($stmt);
		    # Get the number of rows retrieved (note the difference)
		    mysqli_stmt_store_result($stmt);
		    $rows = mysqli_stmt_num_rows($stmt);
		    # bind the result
		    mysqli_stmt_bind_result($stmt, $retrievedPassword);
		    # fetch the result
		    mysqli_stmt_fetch($stmt);
		    
		    # If the number of rows retrieved are zero then it means there is no user with such user_name in the DB
		    if($rows === 0)
		    	return UNREGISTERED_USER;
		    else if($rows === 1) {
		    	# user_name exists, now check for password
		    	if($password === $retrievedPassword)
		    		return SUCCESSFUL_OPR;
		    	else
		    		return INCORRECT_PASSWORD;
		    }
		    else
		    	return ERROR_VALUE; # Oops Error!

		}
		else {
			# Error in creating the prepared statement. Report it to user
			$ERROR_VALUE_DESC = "Error while creating the prepared statement in present()";
			logMessage($ERROR_VALUE_DESC);
			return ERROR_VALUE;
		}
	}

	# This function checks whether there exists an email with value $emailAddress in the database
	# It returns various status as return values:
	# (i) ERROR_VALUE: If there is any other error such as connecting to the database, etc.
	# (ii) NEW_EMAIL: If the emailAddress is not present in the database
	# (iii) EMAIL_ALREADY_REGISTERED: If the email is already registered in the database
	function checkEmailAddress($emailAddress) {
		# Open the connection to database
		$mysqli = mysqli_connect($GLOBALS['dbServerName'],$GLOBALS['dbUserName'],$GLOBALS['dbPassword'],$GLOBALS['dbName']);

		# If there is an error report it.
		if (mysqli_connect_error()) {
			$ERROR_VALUE_DESC = 'Error while connecting to database in checkEmailAddress() function ' . mysqli_connect_errno() . ' ' . mysqli_connect_error();
			logMessage($ERROR_VALUE_DESC);
			return ERROR_VALUE;
		}

		# No error. Create a prepared statement

		$query = "SELECT email_id FROM email_addresses WHERE email_id = ?";
		$retrievedEmail = ""; # This is of no use
		if($stmt = mysqli_prepare($mysqli,$query)) {

			# bind the parameter to the wildcard entry in the query
		    mysqli_stmt_bind_param($stmt, "s", $emailAddress);
		    # execute the query
		    mysqli_stmt_execute($stmt);
		    # Get the number of rows retrieved (note the difference)
		    mysqli_stmt_store_result($stmt);
		    $rows = mysqli_stmt_num_rows($stmt);
		    # bind the result
		    mysqli_stmt_bind_result($stmt, $retrievedEmail);
		    # fetch the result
		    mysqli_stmt_fetch($stmt);
		    
		    if($rows === 0)
		    	return NEW_EMAIL; # If no rows are retrieved then the email address is new
		    else if($rows > 0)
		    	return EMAIL_ALREADY_REGISTERED; # This email is already registered
		    else
		    	return ERROR_VALUE; # Oops! Error!
		}
		else {
			# Error in creating the prepared statement. Report it to user
			$ERROR_VALUE_DESC = "Error while creating the prepared statement in checkEmailAddress()";
			logMessage($ERROR_VALUE_DESC);
			return ERROR_VALUE;
		}
	}

	# Add the email address specified by $emailAddress to the email_addresses table
	# It returns SUCCESSFUL_OPR if the emailAddress has been added to the database, or else ERROR_VALUE
	function addEmailToDatabase($userName,$emailAddress) {
		# Open the connection to database
		$mysqli = mysqli_connect($GLOBALS['dbServerName'],$GLOBALS['dbUserName'],$GLOBALS['dbPassword'],$GLOBALS['dbName']);

		# If there is an error report it.
		if (mysqli_connect_error()) {
			$ERROR_VALUE_DESC = 'Error while connecting to database in addEmailToDatabase() function ' . mysqli_connect_errno() . ' ' . mysqli_connect_error();
			logMessage($ERROR_VALUE_DESC);
			return ERROR_VALUE;
		}

		# No error. Create a prepared statement

		$query = "INSERT INTO email_addresses(user_name,email_id) VALUES(?,?)";
		if($stmt = mysqli_prepare($mysqli,$query)) {

			# bind the parameters to the wildcard entries in the query
		    mysqli_stmt_bind_param($stmt, "ss", $userName, $emailAddress);
		    # execute the query
		    mysqli_stmt_execute($stmt);
		    # get the number of rows affected due to insert/delete/update
		    $rows = mysqli_stmt_affected_rows($stmt);

		    if($rows > 0) # We are able to insert the user, hence signal success
		    	return SUCCESSFUL_OPR;
		    else # Some Error, despite several checks, Oooops!
		    	return ERROR_VALUE;
		    
		}
		else {
			# Error in creating the prepared statement. Report it to user
			$ERROR_VALUE_DESC = "Error while creating the prepared statement in addEmailToDatabase()";
			logMessage($ERROR_VALUE_DESC);
			return ERROR_VALUE;
		}
	}

	# Add the user details to the database (It is assumed that all the parameters specified are VALID)
	# It returns SUCCESSFUL_OPR if the user has been added to the database, or else ERROR_VALUE
	function addUserToDatabase($firstName,$lastName,$gender,$emailAddress,$userName,$password) {

		# Open the connection to database
		$mysqli = mysqli_connect($GLOBALS['dbServerName'],$GLOBALS['dbUserName'],$GLOBALS['dbPassword'],$GLOBALS['dbName']);

		# If there is an error report it.
		if (mysqli_connect_error()) {
			$ERROR_VALUE_DESC = 'Error while connecting to database in addUserToDatabase() function ' . mysqli_connect_errno() . ' ' . mysqli_connect_error();
			logMessage($ERROR_VALUE_DESC);
			return ERROR_VALUE;
		}

		# No error. Create a prepared statement

		$query = "INSERT INTO users(user_name,password,first_name,last_name,gender) VALUES(?,?,?,?,?)";
		if($stmt = mysqli_prepare($mysqli,$query)) {

			# bind the parameters to the wildcard entries in the query
		    mysqli_stmt_bind_param($stmt, "sssss", $userName, $password, $firstName, $lastName, $gender);
		    # execute the query
		    mysqli_stmt_execute($stmt);
		    # get the number of rows affected due to insert/delete/update
		    $rows = mysqli_stmt_affected_rows($stmt);
		    
		    /*
				From the docs (the description of the return values by mysqli_stmt_affected_rows() function):
				An integer greater than zero indicates the number of rows
				affected or retrieved. Zero indicates that no records where
				updated for an UPDATE/DELETE statement, no rows matched the
				WHERE clause in the query or that no query has yet been executed.
				-1 indicates that the query has returned an error. NULL
				indicates an invalid argument was supplied to the function.
		    */

		    if($rows > 0) # We are able to insert the user, hence signal success
		    	return SUCCESSFUL_OPR;
		    else # Some Error, despite several checks, Oooops! 
		    	return ERROR_VALUE;
		    
		}
		else {
			# Error in creating the prepared statement. Report it to user
			$ERROR_VALUE_DESC = "Error while creating the prepared statement in addUserToDatabase()";
			logMessage($ERROR_VALUE_DESC);
			return ERROR_VALUE;
		}
	}

	# This function checks whether there exists a user called $userName in the database
	# It returns various status as return values:
	# (i) ERROR_VALUE: If there is any other error such as connecting to the database, etc.
	# (ii) NEW_USER: If the emailAddress is not present in the database
	# (iii) USER_ALREADY_REGISTERED: If the email is already registered in the database
	function checkUserName($userName) {
		# Open the connection to database
		$mysqli = mysqli_connect($GLOBALS['dbServerName'],$GLOBALS['dbUserName'],$GLOBALS['dbPassword'],$GLOBALS['dbName']);

		# If there is an error report it.
		if (mysqli_connect_error()) {
			$ERROR_VALUE_DESC = 'Error while connecting to database in checkUserName() function ' . mysqli_connect_errno() . ' ' . mysqli_connect_error();
			logMessage($ERROR_VALUE_DESC);
			return ERROR_VALUE;
		}

		# No error. Create a prepared statement

		$query = "SELECT user_name FROM users WHERE user_name = ?";
		$retrievedUserName = ""; # This is of no use
		if($stmt = mysqli_prepare($mysqli,$query)) {

			# bind the parameter to the wildcard entry in the query
		    mysqli_stmt_bind_param($stmt, "s", $userName);
		    # execute the query
		    mysqli_stmt_execute($stmt);
		    # Get the number of rows retrieved (note the difference)
		    mysqli_stmt_store_result($stmt);
		    $rows = mysqli_stmt_num_rows($stmt);
		    # bind the result
		    mysqli_stmt_bind_result($stmt, $retrievedUserName);
		    # fetch the result
		    mysqli_stmt_fetch($stmt);
		    
		    if($rows === 0)
		    	return NEW_USER; # If no rows are retrieved then the user_name is new
		    else if($rows > 0)
		    	return USER_ALREADY_REGISTERED; # This user_name is already registered
		    else
		    	return ERROR_VALUE; # Oops! Error!
		}
		else {
			# Error in creating the prepared statement. Report it to user
			$ERROR_VALUE_DESC = "Error while creating the prepared statement in checkUserName()";
			logMessage($ERROR_VALUE_DESC);
			return ERROR_VALUE;
		}
	}


	# Functions related to server side validation of user input
	/*
		The below function returns an appropriate error message for the input password.
		If everything went right, then the literal 'true' is returned
	*/
	function checkPassword($passwd) {
	    
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