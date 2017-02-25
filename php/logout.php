<?php
	# This script file just takes care of logging off the user
	# It ensures that the session is destroyed
	session_start();
	require 'constants.php';
	require 'utility.php';
  	logMessage("Logout Successfully");
  	session_destroy();
  	header("Location: " . LOGIN_PAGE_URL);
?>