<?php

	# This file simply makes a call to the database and updates the college
	# corresponding to the userName

	require 'databaseQueries.php';

	if(isset($_POST["userName"]) && isset($_POST["college"])) {
		$userName = $_POST["userName"];
		$college = $_POST["college"];
		$result = updateCollege($userName,$college);
		echo $result;
	}
	else {
		# The POST parameter is NOT set!
		# Redirect to Error page
		header("Location: " . ERROR_PAGE_URL);
	}
?>