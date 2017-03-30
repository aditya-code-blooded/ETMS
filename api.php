<?php

	# This file retrieves the user information from the database
	# along with the list of expenses and ToDo's.
	# It is not used within the application. This file is the endpoint
	# to which the user must make an API call:
	# 		/ETMS/api.php
	# Note: It doesn't check whether the input is valid
	# The input must be validated at client side itself

	# Include the file for gaining access to several functions
	require 'php/databaseQueries.php';
	
	header('Content-Type: application/json');

	$userName = "";
	$password = "";
	$response = "";

	// Extract the userName and password from request headers
	foreach (getallheaders() as $name => $value) {
    	if($name === "userName")
    		$userName = $value;
    	if($name === "password")
    		$password = $value;
	}

	// Check if the userName and password request headers are present
	if($userName === "" || $password === "") {
		$response["status"] = "Failure";
		$response["message"] = "Use proper request headers (userName, password)";
	}
	else {
		$userName = test_input($userName);
		$password = test_input($password);

		// Make a call to the database
		$result = present($userName,$password);

		// Take appropriate action based on the $result
		if($result === SUCCESSFUL_OPR) {

			// If success, then retrieve the user information along with
			// to-do list and expenses list
			$userInfo = getUserInfo($userName);
			$todoList = getToDoList($userName);
			$expenseList = getExpenseList($userName);

			// Prepare the response
			$response["status"] = "Success";
			$response["message"] = "Details of $userName";
			$response["userInfo"] = $userInfo;
			$response["todoList"] = $todoList;
			$response["expenseList"] = $expenseList;

		}
		else if($result === ERROR_VALUE) {
			$response["status"] = "Failure";
			$response["message"] = "Internal server error has occurred";
		}
		else if($result === UNREGISTERED_USER) {
			$response["status"] = "Failure";
			$response["message"] = "This user is not yet registered";
		}
		else if($result === INCORRECT_PASSWORD) {
			$response["status"] = "Failure";
			$response["message"] = "Incorrect password. Please check the password you have typed";
		}
		else {
			$response["status"] = "Failure";
			$response["message"] = "ERROR!";
		}
	}

	$jsonResponse = json_encode($response);
	echo $jsonResponse;

?>