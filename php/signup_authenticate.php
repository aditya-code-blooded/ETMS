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

	    $result = validateFirstName($firstName);
	    if(!$result) {
	    	logMessage(FIRST_NAME_ERROR);
	    	echo "<script>window.location.href='http://localhost/ETMS/signup.html'</script>"; # Redirect to Signup Page
	    }
	    else {
	    	$result = validateLastName($lastName);
	    	if(!$result) {
	    		logMessage(LAST_NAME_ERROR);
	    		echo "<script>window.location.href='http://localhost/ETMS/signup.html'</script>"; # Redirect to Signup Page
	    	}
	    	else {
	    		$result = validateEmail($emailAddress);
	    		if(!$result) {
	    			logMessage(EMAIL_ERROR);
	    			echo "<script>window.location.href='http://localhost/ETMS/signup.html'</script>"; # Redirect to Signup Page
	    		}
	    		else {
	    			$result = validateUserName($userName);
					if(!$result) {
						logMessage(USER_NAME_ERROR);
						echo "<script>window.location.href='http://localhost/ETMS/signup.html'</script>"; # Redirect to Signup Page
					}
					else {
						$result = validatePassword($password);
						if(!($result === true)) {
							logMessage(PASSWORD_ERROR);
							echo "<script>window.location.href='http://localhost/ETMS/signup.html'</script>"; # Redirect to Signup Page
						}
						else {
							# Before adding the user to database, check whether the userName provided conflicts
							# with any other userName already present in the database
							$result = checkUserName($userName);	    
							$newUser = false;
							switch($result) {
								case NEW_USER:
									$newUser = true;
									break;
								case USER_ALREADY_REGISTERED:
									logMessage(USER_ALREADY_REGISTERED);
									echo "<script>window.location.href='http://localhost/ETMS/signup.html'</script>"; # Redirect to Signup Page
									break;
								default:
									echo "Error while interacting with database";
							}

							if($newUser) {
								# Before adding the user to database, check whether the email ID provided conflicts
								# with any other email already present in the database
								$result = checkEmailAddress($emailAddress);
								$newEmail = false;

								switch ($result) {
								    case NEW_EMAIL:
								        $newEmail = true;
								        break;
								    case EMAIL_ALREADY_REGISTERED:
								        logMessage(EMAIL_ALREADY_REGISTERED);
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
											echo "<script>window.location.href='http://localhost/ETMS/home.html'</script>"; # Redirect to Home Page
										else
											logMessage("Unable to add email Address to the database! Delete the user who was added immediately");
									}
									else
										echo "Error while interacting with database";
								}
							}
						}
					}
				}
	    	}
	    }

	}
	else if(isset($_POST["firstName"]) && isset($_POST["userName"]) &&
			isset($_POST["gender"]) && isset($_POST["emailAddress"]) &&
			isset($_POST["password"])) {
		# This block of code gets executed when AJAX request is sent
		# Note that we did not check for lastName since it is optional

		# Test for malicious input (The return value will be free from sql injection attack)
	    $firstName = test_input($_POST["firstName"]);
	    $lastName = test_input($_POST["lastName"]);
	    $gender = test_input($_POST["gender"]);
	    $emailAddress = test_input($_POST["emailAddress"]);
	    $userName = test_input($_POST["userName"]);
	    $password = test_input($_POST["password"]);

	    # Before adding the user to database, check whether the userName provided conflicts
		# with any other userName already present in the database
		# checkUserName returns ERROR_VALUE, USER_ALREADY_REGISTERED and NEW_USER status codes
		# Out of those, the error values which are to be reported to the user are:
		# USER_ALREADY_REGISTERED and ERROR_VALUE
		$result = checkUserName($userName);	    
		if($result === ERROR_VALUE || $result === USER_ALREADY_REGISTERED)
			echo $result;
		else {
			# If the userName is not present in the database check emailAddress
			# Before adding the user to database, check whether the email ID provided conflicts
			# with any other email already present in the database
			$result = checkEmailAddress($emailAddress);

			# checkEmailAddress returns ERROR_VALUE, EMAIL_ALREADY_REGISTERED and NEW_EMAIL status codes
			# Out of those, the error values which are to be reported to the user are:
			# EMAIL_ALREADY_REGISTERED and ERROR_VALUE
			if($result === ERROR_VALUE || $result === EMAIL_ALREADY_REGISTERED)
				echo $result;
			else {
				# If the email address is new then insert the user into users table
				# And also insert the emailAddress into the email_addresses table

				$result = addUserToDatabase($firstName,$lastName,$gender,$emailAddress,$userName,$password);
				if($result === SUCCESSFUL_OPR)
					$result = addEmailToDatabase($userName,$emailAddress);
				echo $result;
			}
		}

	}
	else {
	    # If the page is reached by some other means then throw an error
		echo "Error!";
	}
?>