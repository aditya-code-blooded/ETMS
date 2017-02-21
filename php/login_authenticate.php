<?php
	/*
		Go to php.ini file in ./php/7.0/apachee tergv
		find error_reporting and restore changes
		and display_startup_errors also 
		and then restart the server
	*/

	// Comment the below 2 lines
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');

	# This file performs server side validation of the input data from login form.
	# After the data is validated (to prevent SQL injection attacks) query the database for credentials and validate.

	# Include the file for gaining access to several functions
	require 'databaseQueries.php';

	if (isset($_POST['login_button'])) {
		# Test for malicious input (The return value will be free from sql injection attack)
	  	$userName = test_input($_POST["userName"]);
	  	$password = test_input($_POST["password"]);

	  	/*
			Since this block of code is executed when AJAX is not performed then the user
			input is not validated. We need to perform server side validation because
			client side validation is disabled
	  	*/
		$result = validateUserName($userName);
		if(!$result)
			echo USER_NAME_ERROR;
		else {
			$result = validatePassword($password);
			echo $result;
			if(!($result === true))
				echo PASSWORD_ERROR;
			else {
				# After the processing, query the database whether the credentials are right
				$result = present($userName,$password);
				switch ($result) {
				    case SUCCESSFUL_OPR:
				    	header("Location: http://localhost/ETMS/home.html"); # Redirect to Home Page
				        break;
				    case INCORRECT_PASSWORD:
				        echo INCORRECT_PASSWORD_ERROR;
				        break;
				    case UNREGISTERED_USER:
				        echo UNREGISTERED_USER_ERROR;
				        break;
				    default:
				        echo "Error while interacting with database";
				}
			}
		}

	}
	else if(isset($_POST["userName"]) && isset($_POST["password"])) {

		# This block of code gets executed when AJAX request is sent

		# Test for malicious input (The return value will be free from sql injection attack)
	  	$userName = test_input($_POST["userName"]);
	  	$password = test_input($_POST["password"]);

	  	# After the processing, query the database whether the credentials are right
		$result = present($userName,$password);

		echo $result; # Send the return status to the AJAX callback function
	}
	else {
		# If the page is reached by some other means then throw an error
		echo "Error!";
	}

?>