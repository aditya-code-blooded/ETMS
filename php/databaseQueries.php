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
	# WARNING: Do not change their names or their values
	define("SUCCESSFUL_OPR", "Successful operation");
	define("INCORRECT_PASSWORD", "Password is incorrect for the given username.");
	define("UNREGISTERED_USER", "The user is not registered. Try signing up and creating a new account.");
	define("NEW_EMAIL", "The email address is new. Not yet inserted into database");
	define("EMAIL_ALREADY_REGISTERED", "The email address is already present in the database");
	define("ERROR_VALUE", "Error while accessing/retrieving data from database.");
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

?>