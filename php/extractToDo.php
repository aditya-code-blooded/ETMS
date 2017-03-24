<?php

	# This file simply makes a call to retrieve the ToDo list of the specified
	# user from the database. Before making the call, it checks whether the
	# userName is properly set

	require 'databaseQueries.php';

	if(isset($_GET["userName"])) {
		$userName = $_GET["userName"];
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