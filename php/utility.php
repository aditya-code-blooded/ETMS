<?php

	# This script file contains various miscellaneous functions which will be used
	# by other php script files

	# A function which logs messages to the browser console
	function logMessage($msg) {
		echo "<script>console.log('" . $msg . "')</script>";
	}
?>