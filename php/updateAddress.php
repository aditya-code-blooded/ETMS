<?php

	# This file simply makes a call to the database and updates the address
	# corresponding to the userName

	// Start the session
	session_start();
	# Include the file for gaining access to several functions
	require_once 'databaseQueries.php';
	/*
		Check whether the user is logged in. This can be done by
		checking whether the session variables: userName and password
		are set.
	*/
	if(isset($_SESSION['userName']) && isset($_SESSION['password'])) {

		// If they are set we need to make sure they are valid
		// We do this by making a database call

		$result = present($_SESSION['userName'],$_SESSION['password']);
		if($result === SUCCESSFUL_OPR) {
			// Success: No need to do anything here.
		}
		else {
			# The sessions variables are set but are incorrect!
			# Security threat - Redirect to error page
			header("Location: " . ERROR_PAGE_URL);
		}
	}
	else {
		# Session variables are not set
		# Security threat - Redirect to login page
		header("Location: " . LOGIN_PAGE_URL);
	}

	if(isset($_POST["userName"]) && isset($_POST["address"])) {
		$userName = test_input($_POST["userName"]);
		$address = test_input($_POST["address"]);
		// Validate each of the input fields here before going further
		$result = updateAddress($userName,$address);
		echo $result;
	}
	else {
		# The POST parameter is NOT set!
		# Redirect to Error page
		header("Location: " . ERROR_PAGE_URL);
	}
?>