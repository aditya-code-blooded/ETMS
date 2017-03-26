<?php

	# This file simply makes a call to retrieve the ToDo list of the specified
	# user from the database. Before making the call, it checks whether the
	# userName is properly set

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

	if(isset($_GET["userName"])) {
		$userName = test_input($_GET["userName"]);
		$todoList = getToDoList($userName);
		$jsonTodo = json_encode($todoList);
		echo $jsonTodo;
	}
	else {
		# The GET parameter is NOT set!
		# Redirect to Error page
		header("Location: " . ERROR_PAGE_URL);
	}
?>