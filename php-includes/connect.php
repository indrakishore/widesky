<?php
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "mlm";

	$con =  mysqli_connect($db_host,$db_user,$db_pass,$db_name);
	if (mysqli_connect_error()) {
		echo "Connect to database failed.";
	}
?>