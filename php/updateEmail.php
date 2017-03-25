<?php

	# This file simply makes a call to the database and updates the email
	# corresponding to the userName

	require 'databaseQueries.php';

	if(isset($_POST["userName"]) && isset($_POST["email"])) {
		$userName = $_POST["userName"];
		$email = $_POST["email"];
		$result = updateEmail($userName,$email);
		echo $result;
	}
	else {
		# The POST parameter is NOT set!
		# Redirect to Error page
		header("Location: " . ERROR_PAGE_URL);
	}
?>