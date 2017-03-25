<?php

	# This file simply makes a call to the database and updates the contact
	# corresponding to the userName

	require 'databaseQueries.php';

	if(isset($_POST["userName"]) && isset($_POST["contact"])) {
		$userName = $_POST["userName"];
		$contact = $_POST["contact"];
		$result = updateContact($userName,$contact);
		echo $result;
	}
	else {
		# The POST parameter is NOT set!
		# Redirect to Error page
		header("Location: " . ERROR_PAGE_URL);
	}
?>