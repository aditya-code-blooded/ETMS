<?php

	# This file creates the Expense entry in the database and returns appropriate return status
	# Note: It doesn't check whether the input is valid
	# The input must be validated at client side itself

	# Start the session
	session_start();
	# Include the file for gaining access to several functions
	require 'databaseQueries.php';
	/*
		Check whether the user is logged in. This can be done by
		checking whether the session variables: userName and password
		are set.
	*/ 
	if(isset($_SESSION['userName']) && isset($_SESSION['password'])) {

		# If they are set we need to make sure they are valid
		# We do this by making a database call

		$result = present($_SESSION['userName'],$_SESSION['password']);
		if($result === SUCCESSFUL_OPR) {
			# The user is logged in, then no problem
		}
		else {
			# The sessions variables are set but are incorrect!
			# Security threat - Redirect to Error page
			header("Location: " . ERROR_PAGE_URL);
		}
	}
	else {
		# The sessions variables are NOT set!
		# Security threat - Redirect to Login page
		header("Location: " . LOGIN_PAGE_URL);
	}

	if(isset($_POST["amount"]) && isset($_POST["description"])) {
		# This block of code gets executed when AJAX request is sent

	  	# Add the ToDo entry to database
	  	$userName = $_SESSION["userName"];
	  	$amount = test_input($_POST["amount"]);
	  	$desc = test_input($_POST["description"]);
		$result = addExpenseEntry($userName,$amount,$desc);

		echo $result; # Send the return status to the AJAX callback function
	}
	else {
		# If the page is reached by some other means then throw an error
		echo "Error!";
	}

?>