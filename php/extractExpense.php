<?php

	# This file simply makes a call to retrieve the Expense list of the specified
	# user from the database. Before making the call, it checks whether the
	# userName is properly set

	require 'databaseQueries.php';

	if(isset($_GET["userName"])) {
		$userName = $_GET["userName"];
		$expenseList = getExpenseList($userName);
		$jsonTodo = json_encode($expenseList);
		echo $jsonTodo;
	}
	else {
		# The GET parameter is NOT set!
		# Redirect to Error page
		header("Location: " . ERROR_PAGE_URL);
	}
?>