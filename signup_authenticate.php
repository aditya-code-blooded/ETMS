<?php
	# Perform server side validation before adding it to database
	if (isset($_POST['login_button'])) {
	    echo "login button clicked";
	} else if (isset($_POST['signup_button'])) {
	    echo "signup button clicked";
	} else {
	    echo "Error";
	}
?>