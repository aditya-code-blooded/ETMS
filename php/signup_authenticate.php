<?php
	
	# This file performs server side validation of the input data from signup form.
	# After the data is validated (to prevent SQL injection attacks) insert into the database

	# Include the file for gaining access to several functions
	require 'databaseQueries.php';

	if (isset($_POST['signup_button'])) {
	    # Test for malicious input (The return value will be free from sql injection attack)
	    $firstName = test_input($_POST["firstName"]);
	    $lastName = test_input($_POST["lastName"]);
	    $gender = test_input($_POST["gender"]);
	    $emailAddress = test_input($_POST["emailAddress"]);
	    $userName = test_input($_POST["userName"]);
	    $password = test_input($_POST["password"]);

	    # Before adding the user to database, check whether the email ID provided conflicts
		# with any other email already present in the database
		$result = checkEmailAddress($emailAddress);
		$newEmail = false;

		switch ($result) {
		    case NEW_EMAIL:
		        $newEmail = true;
		        break;
		    case EMAIL_ALREADY_REGISTERED:
		        logMessage("Email already registered. Use a new email.");
		        break;
		    default:
		        echo "Error while interacting with database";
		}

		# If the email address is new then insert the user into users table
		# And also insert the emailAddress into the email_addresses table
		if($newEmail) {
			$result = addUserToDatabase($firstName,$lastName,$gender,$emailAddress,$userName,$password);
			if($result === SUCCESSFUL_OPR) {
				$result = addEmailToDatabase($userName,$emailAddress);
				if($result === SUCCESSFUL_OPR)
					echo "<script>window.location.href='http://localhost/home.html'</script>"; # Redirect to Home Page
				else
					logMessage("Unable to add email Address to the database! Delete the user who was added immediately");
			}
			else
				logMessage("Username already taken, try another one");
		}

	} else {
	    # If the page is reached by some other means then throw an error
		echo "Error!";
	}
?>