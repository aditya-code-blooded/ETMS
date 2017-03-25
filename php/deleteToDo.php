<?php

	# This file simply makes a call to retrieve the ToDo list of the specified
	# user from the database. Before making the call, it checks whether the
	# userName is properly set

	require 'databaseQueries.php';

	if(isset($_POST["userName"]) && isset($_POST["title"]) && isset($_POST["desc"])) {
		$userName = $_POST["userName"];
		$title = $_POST["title"];
		$desc = $_POST["desc"];
		echo deleteTodo($userName,$title,$desc);
	}
	else {
		# The GET parameter is NOT set!
		# Redirect to Error page
		header("Location: " . ERROR_PAGE_URL);
	}
?>