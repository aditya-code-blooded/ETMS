<?php
	
	# This file performs server side validation of the input data from signup form.
	# After the data is validated (to prevent SQL injection attacks) insert into the database

	# Start the session
	session_start();
	# Include the file for gaining access to several functions
	require 'databaseQueries.php';
	/*
		Check whether the user is logged in. This can be done by
		checking whether the session variables: userName and password
		are set.
	*/ 
	if(isset($_SESSION['userName']) && isset($_SESSION['password'])) {

		// If they are set we need to make sure they are valid
		// We do this by making a database call

		$result = present($_SESSION['userName'],$_SESSION['password']);
		if($result === SUCCESSFUL_OPR) {
			// The user is logged in, we need to redirect him to home page
			header("Location: " . HOME_PAGE_URL);
		}
		else {
			# The sessions variables are set but are incorrect!
			# Security threat - Fallback to error page
			header("Location: " . ERROR_PAGE_URL);
		}
	}
	# If the session is not set, it means the user is trying to signup
	# No security issue

	if (isset($_POST['signup_button'])) {
	    # Test for malicious input (The return value will be free from sql injection attack)
	    $firstName = test_input($_POST["firstName"]);
	    $lastName = test_input($_POST["lastName"]);
	    $gender = test_input($_POST["gender"]);
	    $emailAddress = test_input($_POST["emailAddress"]);
	    $userName = test_input($_POST["userName"]);
	    $password = test_input($_POST["password"]);
	    $retypePassword = test_input($_POST["retypePassword"]);

	    $result = validateFirstName($firstName);
	    if(!$result)
	    	echo FIRST_NAME_ERROR;
	    else {
	    	$result = validateLastName($lastName);
	    	if(!$result)
	    		echo LAST_NAME_ERROR;
	    	else {
	    		$result = validateEmail($emailAddress);
	    		if(!$result)
	    			echo EMAIL_ERROR;
	    		else {
	    			$result = validateUserName($userName);
					if(!$result)
						echo USER_NAME_ERROR;
					else {
						$result = validatePassword($password);
						if(!($result === true))
							echo PASSWORD_ERROR;
						else {
							$result = passwordsMatch($password,$retypePassword);
							if(!$result)
								echo PASSWORD_MATCH_ERROR;
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
										echo USER_ALREADY_REGISTERED;
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
									        echo EMAIL_ALREADY_REGISTERED;
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
											if($result === SUCCESSFUL_OPR) {
												# Before redirecting the user to Home page, store the username and password in the session variables
												$_SESSION['userName'] = $userName;
				    							$_SESSION['password'] = $password;
												header("Location: http://localhost/ETMS/home.html"); # Redirect to Home Page
											}
											else
												echo "Unable to add email Address to the database! Delete the user who was added immediately";
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
				if($result === SUCCESSFUL_OPR) {
					# Before redirecting the user to Home page, store the username and password in the session variables
					$_SESSION['userName'] = $userName;
					$_SESSION['password'] = $password;
					$result = addEmailToDatabase($userName,$emailAddress);
				}
				echo $result;
			}
		}

	}
	else {
	    # If the page is reached by some other means then throw an error
		echo "Error!";
	}
?>