<?php
	session_start();
	require('php-includes/connect.php');
	$email = mysqli_real_escape_string($con,$_POST['email']);
	$password = mysqli_real_escape_string($con,$_POST['password']);

	$query = mysqli_query($con,"select * from user where email = '$email' and password = '$password' ");
	if(mysqli_num_rows($query)>0){
		$result = mysqli_fetch_array($query);
		$isActive = $result['isactive'];
		if ($isActive == 'Yes') {
			$_SESSION['user_id'] = $email;
			$_SESSION['id'] = session_id();
			$_SESSION['login_type'] = "user";

			echo '<script>window.location.assign("home.php");</script>';
		}
		else{
			echo '<script>alert("Your ID has been deactivated please contact support.");window.location.assign("index.php");</script>';
		}
	} 
	else{
		echo '<script>alert("Email id or password is wrong.");window.location.assign("index.php");</script>';
	}
?>