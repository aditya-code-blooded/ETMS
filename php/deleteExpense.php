<?php

	# This file simply makes a call to retrieve the Expenses list of the specified
	# user from the database. Before making the call, it checks whether the
	# userName is properly set

	require 'databaseQueries.php';

	if(isset($_POST["userName"]) && isset($_POST["amount"]) && isset($_POST["desc"])) {
		$userName = $_POST["userName"];
		$amount = $_POST["amount"];
		$desc = $_POST["desc"];
		echo deleteExpense($userName,$amount,$desc);
	}
	else {
		# The GET parameter is NOT set!
		# Redirect to Error page
		header("Location: " . ERROR_PAGE_URL);
	}
?>