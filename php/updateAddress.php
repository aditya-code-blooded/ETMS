<?php

	# This file simply makes a call to the database and updates the address
	# corresponding to the userName

	require 'databaseQueries.php';

	if(isset($_POST["userName"]) && isset($_POST["address"])) {
		$userName = $_POST["userName"];
		$address = $_POST["address"];
		$result = updateAddress($userName,$address);
		echo $result;
	}
	else {
		# The POST parameter is NOT set!
		# Redirect to Error page
		header("Location: " . ERROR_PAGE_URL);
	}
?>