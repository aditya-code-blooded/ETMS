<?php

	# Start the session
	session_start();
	# Include the file for gaining access to several functions
	require 'databaseQueries.php';
	/*
		Check whether the user is logged in. This can be done by
		checking whether the session variables: userName and password
		are set.
	*/ 
	logMessage("Inside profilePhotoUpload.php");
	if(isset($_SESSION['userName']) && isset($_SESSION['password'])) {
		# If they are set we need to make sure they are valid
		# We do this by making a database call
		logMessage("Session variables are present");
		$result = present($_SESSION['userName'],$_SESSION['password']);
		if($result === SUCCESSFUL_OPR) {
			// The user is logged in, store the userName
			logMessage("They are valid too!");
			$userName = $_SESSION['userName'];
		}
		else {
			logMessage("They are invalid");
			# The sessions variables are set but are incorrect!
			# Security threat - Go back to login page
			header("Location: " . LOGIN_PAGE_URL);
		}
	}
	else {
		logMessage("Session variables are not present");
		# If the session variables are not set then redirect to LOGIN page
		header("Location: " . LOGIN_PAGE_URL);
	}

	# Actual upload script starts here

	$target_dir = "../uploads/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$msg = "";
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	    if($check !== false) {
	        $msg = "File is an image - " . $check["mime"] . ".";
	        logMessage($msg);
	        $uploadOk = 1;
	    } else {
	        $msg = "File is not an image.";
	        logMessage($msg);
	        $uploadOk = 0;
	    }
	}
	// Check if file already exists
	if (file_exists($target_file)) {
	    $msg = "Sorry, file already exists.";
	    logMessage($msg);
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
	    $msg = "Sorry, your file is too large.";
	    logMessage($msg);
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    $msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		logMessage($msg);
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    $msg = "Sorry, your file was not uploaded.";
	    logMessage($msg);
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	        $msg = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	        logMessage($msg);

	        # If the file was successfully uploaded, then make an entry (of the path) into the database table
	        $profilePic = 'uploads/' . basename($_FILES["fileToUpload"]["name"]);
	        $result = uploadProfilePic($userName,$profilePic);
	        if($result == SUCCESSFUL_OPR)
	        	logMessage("Successfully made an entry into the database");
	        else
	        	logMessage("Error: Could not make an entry into the database");
	    } else {
	        $msg = "Sorry, there was an error uploading your file.";
	        logMessage($msg);
	    }
	}
	# Finally go to home page
	header("Location: " . HOME_PAGE_URL);
?>