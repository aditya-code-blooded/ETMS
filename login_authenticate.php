<?php
	# Perform server side validation before adding it to database
	
	function test_input($data) {
	  	$data = trim($data);
	  	$data = stripslashes($data);
	  	$data = htmlspecialchars($data);
	  	return $data;
	}

	function present($userName,$password) {
		return false;
	}

	$userName = $password = "";

	echo "Result: " . isset($_POST['login_button']);
	if (isset($_POST['login_button'])) {
		echo "Post method";
	  	$userName= test_input($_POST["userName"]);
	  	$password = test_input($_POST["password"]);

	  	if(present($userName,$password))
	  		echo "<script>window.location.href="http:\/localhost/home.html"</script>";
	  	else
	  		echo "Invalid";
	} else {
		echo "Error";
	}

?>