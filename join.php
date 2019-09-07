<?php
ini_set('max_execution_time', 600);
include('php-includes/check-login.php');
include('php-includes/connect.php');
date_default_timezone_set("Asia/Calcutta");
$userid = $_SESSION['user_id'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\PHPException;

/* Exception class. */
require 'PHPMailer/src/Exception.php';

/* The main PHPMailer class. */
require 'PHPMailer/src/PHPMailer.php';

/* SMTP class, needed if you want to use SMTP. */
require 'PHPMailer/src/SMTP.php';
?>

<?php
//User clicked on join
if(isset($_GET['join_user'])){
	$side='';
	$pin = mysqli_real_escape_string($con,$_GET['pin']);
	$pack = mysqli_real_escape_string($con,$_GET['pack']);
	$sponsorid = mysqli_real_escape_string($con,$_GET['sponsorid']);
	$name = mysqli_real_escape_string($con,$_GET['name']);
	$email = mysqli_real_escape_string($con,$_GET['email']);
	$gender = mysqli_real_escape_string($con,$_GET['gender']);
	$dob = mysqli_real_escape_string($con,$_GET['dob']);
	$mobile = mysqli_real_escape_string($con,$_GET['mobile']);
	$address = mysqli_real_escape_string($con,$_GET['address']);
	$bank = mysqli_real_escape_string($con,$_GET['bank']);
	$ifsc = mysqli_real_escape_string($con,$_GET['ifsc']);
	$account = mysqli_real_escape_string($con,$_GET['account']);
	$pan = mysqli_real_escape_string($con,$_GET['pan']);
	$under_userid = mysqli_real_escape_string($con,$_GET['under_userid']);
	$side = mysqli_real_escape_string($con,$_GET['side']);
	$password = mysqli_real_escape_string($con,$_GET['password']);
	$terms = mysqli_real_escape_string($con,$_GET['terms']);
	$join_date = date("Y-m-d");
	
	$flag = 0;
	
	if($pin!='' && $sponsorid!='' && $name!='' && $email!='' && $password!='' && $gender!='' && $dob!='' && $mobile!='' && $address!='' && $bank!='' && $ifsc!='' && $account!='' && $pan!='' && $under_userid!='' && $side!='' && $terms!=''){
		//User filled all the fields.
		if(pin_check($pin)){
			//Pin is ok
			if(!email_check($sponsorid))
			{
				if(email_check($email)){
					//Email is ok
					if(!email_check($under_userid)){
						//Under userid is ok
						if(side_check($under_userid,$side)){
							//Side check
							$flag=1;
						}
						else{
							echo '<script>alert("The side you selected is not available.");</script>';
						}
					}
					else{
						//check under userid
						echo '<script>alert("Invalid Under userid.");</script>';
					}
				}
				else{
					//check email
					echo '<script>alert("This user id already availble.");</script>';
				}
			}
			else{
				echo '<script>alert("Invalid Sponsor ID");</script>';
			}

		}
		else{
			//check pin
			echo '<script>alert("Invalid pin");</script>';
		}
	}
	else{
		//check all fields are fill
		echo '<script>alert("Please fill all the fields.");</script>';
	}
	
	//Now we are here
	//It means all the information is correct
	//Now we will save all the information
	if($flag==1){
		
		//Insert into User profile
		$query = mysqli_query($con,"insert into user(`name`,`sponsorid`,`email`,`password`,`join_date`,`gender`,`dob`,`pack`,`upgrade_date`,`mobile`,`address`,`bank`,`ifsc`,`account`,`pan`,`under_userid`,`side`) values('$name','$sponsorid','$email','$password','$join_date','$gender','$dob','$pack','$join_date','$mobile','$address','$bank','$ifsc','$account','$pan','$under_userid','$side')");
		
		//Insert into Tree
		//So that later on we can view tree.
		$query = mysqli_query($con,"insert into tree(`userid`,`pack`,`countdownWPCB`) values('$email','$pack','15')");
		
		//Insert into nominee so later user can update their nominee details
		$query = mysqli_query($con,"insert into nominee(`name`,`userid`) values('$name','$email')");

		//Insert to side
		$query = mysqli_query($con,"update tree set `$side`='$email' where userid='$under_userid'");
		
		//Update pin status to close
		$query = mysqli_query($con,"update pin_list set status='close' where pin='$pin'");
		
		//Inset into Income
		$query = mysqli_query($con,"insert into income (`userid`) values('$email')");

		//Inset into level data
		$query = mysqli_query($con,"insert into level_data (`userid`) values('$email')");

		//Insert into level data lifetime for rank purpose
		$query = mysqli_query($con,"insert into level_data_lifetime (`userid`) values('$email')");

		//INsert into Purchase INcome
		$query = mysqli_query($con, "insert into purchase_income(userid) values('$email')");

		echo mysqli_error($con);
		//This is the main part to join a user
		//If you will do any mistake here. Then the site will not work.

		//Rewards unlocking process
		if ($pack!='free') {
			if ($pack == 999) {
				mysqli_query($con,"update tree set rwd1='open' where userid='$email'");
			}
			if ($pack == 4999) {
				mysqli_query($con,"update tree set rwd1='open',rwd2='open',rwd3='open' where userid='$email'");
			}
			if ($pack == 9999 || $pack==10000) {
				mysqli_query($con,"update tree set rwd1='open',rwd2='open',rwd3='open',rwd4='open',rwd5='open',rwd6='open' where userid='$email'");
			}
			if ($pack == 24999 || $pack==25000) {
				mysqli_query($con,"update tree set rwd1='open',rwd2='open',rwd3='open',rwd4='open',rwd5='open',rwd6='open',rwd7='open' where userid='$email'");
			}
			if ($pack >=39999) {
				mysqli_query($con,"update tree set rwd1='open',rwd2='open',rwd3='open',rwd4='open',rwd5='open',rwd6='open',rwd7='open',rwd8='open',rwd9='open' where userid='$email'");
			}
		}
		

		
		//Direct Income(Shubham)
		if($sponsorid!=""){
			$income_data = income($sponsorid);
			$tree_data = tree($sponsorid);

			//Check again from here 04/06/2019

			if ($pack!='free') {
				if ($pack % 2 == 0) {
					$percentage = 5;
					$new_day_bal = $income_data['day_bal']+($pack*$percentage)/100;
					$new_direct_bal = $income_data['direct_bal']+($pack*$percentage)/100;
					mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$sponsorid' limit 1");
				}
				else{
					if ($tree_data['pack'] == 39999) {
						$new_day_bal = $income_data['day_bal']+($pack * 15)/100;
						$new_direct_bal = $income_data['direct_bal']+($pack * 15)/100;
						mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$sponsorid' limit 1");
					}
					elseif ($tree_data['pack'] == 24999) {
						$new_day_bal = $income_data['day_bal']+($pack * 10)/100;
						$new_direct_bal = $income_data['direct_bal']+($pack * 10)/100;
						mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$sponsorid' limit 1");
						$tPack = 2;
						$tSponsor = $sponsorid;
						while (($tPack%2==0) || ($tPack == 999) || ($tPack == 4999) || ($tPack == 9999) || ($tPack == 24999) || ($tPack=='free')) {
							$query = mysqli_query($con,"select * from user where email='$tSponsor'");
							$result = mysqli_fetch_array($query);
							$tPack = $result['pack'];

							if ($tPack == 'free') {
								$tSponsor = $result['sponsorid'];
								continue;
							}

							if ($tPack%2!=0 && $tPack==39999) {
								$income_data1 = income($tSponsor);
								$new_day_bal = $income_data1['day_bal']+($pack * 5)/100;
								$new_direct_bal = $income_data1['direct_bal']+($pack * 5)/100;
								mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$tSponsor' limit 1");
								break;
							}
							if ($result['id']==1) {
								break;
							}


							$tSponsor = $result['sponsorid'];
						}
					}
					elseif ($tree_data['pack'] == 9999) {
						$new_day_bal = $income_data['day_bal']+($pack * 10)/100;
						$new_direct_bal = $income_data['direct_bal']+($pack * 10)/100;
						mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$sponsorid' limit 1");
						$tPack = 2;
						$tSponsor = $sponsorid;
						$per = 5;
						while (($tPack%2==0) || ($tPack == 999) || ($tPack == 4999) || ($tPack == 9999) || ($tPack == 24999) || ($tPack=='free')) {
							$query = mysqli_query($con,"select * from user where email='$tSponsor'");
							$result = mysqli_fetch_array($query);
							$tPack = $result['pack'];
							$income_data1 = income($tSponsor);

							if ($tPack == 'free') {
								$tSponsor = $result['sponsorid'];
								continue;
							}

							if (($tPack%2!=0) && ($tPack == 39999)) {
								$new_day_bal = $income_data1['day_bal']+($pack * $per)/100;
								$new_direct_bal = $income_data1['direct_bal']+($pack * $per)/100;
								mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$tSponsor'");
								break;
							}

							/*if (($tPack%2!=0) && ($tPack == 24999) && ($per == 10)) {
								$new_day_bal = $income_data1['day_bal']+($pack * 5)/100;
								$new_direct_bal = $income_data1['direct_bal']+($pack * 5)/100;
								mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$tSponsor'");
								$per = 5;
							}*/

							if ($result['id']==1) {
								break;
							}


							$tSponsor = $result['sponsorid'];
						}
					}
					else
					{
						$new_day_bal = $income_data['day_bal']+($pack * 5)/100;
						$new_direct_bal = $income_data['direct_bal']+($pack * 5)/100;
						mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$sponsorid' limit 1");
						$tPack = 2;
						$tSponsor = $sponsorid;
						$per = 10;
						while (($tPack%2==0) || ($tPack == 999) || ($tPack == 4999) || ($tPack == 9999) || ($tPack == 24999) ($tPack=='free')) {
							$query = mysqli_query($con,"select * from user where email='$tSponsor'");
							$result = mysqli_fetch_array($query);
							$tPack = $result['pack'];
							$income_data1 = income($tSponsor);

							if ($tPack == 'free') {
								//$tPack = 2;
								$tSponsor = $result['sponsorid'];
								continue;
							}

							if (($tPack%2!=0) && ($tPack == 39999) && ($per == 10)) {
								$new_day_bal = $income_data1['day_bal']+($pack * $per)/100;
								$new_direct_bal = $income_data1['direct_bal']+($pack * $per)/100;
								mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$tSponsor'");
								break;
							}

							if (($tPack%2!=0) && ($tPack == 24999) && ($per == 10)) {
								$new_day_bal = $income_data1['day_bal']+($pack * 5)/100;
								$new_direct_bal = $income_data1['direct_bal']+($pack * 5)/100;
								mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$tSponsor'");
								$per = 5;
							}

							if (($tPack%2!=0) && ($tPack == 9999) && ($per == 10)) {
								$new_day_bal = $income_data1['day_bal']+($pack * 5)/100;
								$new_direct_bal = $income_data1['direct_bal']+($pack * 5)/100;
								mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$tSponsor'");
								$per = 5;
							}

							if (($tPack%2!=0) && ($tPack == 39999) && ($per == 5)) {
								$new_day_bal = $income_data1['day_bal']+($pack * $per)/100;
								$new_direct_bal = $income_data1['direct_bal']+($pack * $per)/100;
								mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$tSponsor'");
								break;
							}

							if ($result['id']==1) {
								break;
							}


							$tSponsor = $result['sponsorid'];
						}
					}
				}	
			}
		}


		//Update count and Income.
		$temp_under_userid = $under_userid;
		$temp_side_count = $side.'count'; //leftcount or rightcount
		$temp_side_amount = $side.'amount'; //leftamount or rightamount

		$temp_side_rp = $side.'rp'; //leftrp or right rp

		//Reward Points Calculation
		$rp = 0;
		$ra = 0;
		$timeUpgrade = -1;
		if ($pack == 999) {
			$ra = 1;
			$timeUpgrade = 91;
		}
		if ($pack == 4999) {
			$rp = 0.10;
			$ra = 3;
			$timeUpgrade = 182;
		}
		if ($pack == 9999) {
			$rp = 0.25;
			$ra = 6;
			$timeUpgrade = 365;
		}
		if ($pack == 24999) {
			$rp = 0.625;
			$ra = 7;
			$timeUpgrade = 365;
		}
		if ($pack == 39999) {
			$rp = 1;
			$ra = 9;		
			$timeUpgrade = -1;
		}
		mysqli_query($con,"update tree set rewardsach='$ra',timeupgrade='$timeUpgrade' where userid = '$email'");


		$temp_side = $side;
		$total_count=1;
		$i=1;
		while($total_count>0){
			$i;
			$q = mysqli_query($con,"select * from tree where userid='$temp_under_userid'");
			$r = mysqli_fetch_array($q);
			$current_temp_side_count = $r[$temp_side_count]+1;
			if($pack!='free'){
				$current_temp_side_amount = $r[$temp_side_amount]+$pack;
				$current_temp_side_rp = $r[$temp_side_rp]+$rp;
			}
			else{
				$current_temp_side_amount = $r[$temp_side_amount]+0;
				$current_temp_side_rp = $r[$temp_side_rp] + 0;
			}
			$temp_under_userid;
			$temp_side_count;
			$temp_side_amount;
			$temp_side_rp;
			mysqli_query($con,"update tree set `$temp_side_count`=$current_temp_side_count where userid='$temp_under_userid'");
			mysqli_query($con,"update tree set `$temp_side_amount`=$current_temp_side_amount where userid='$temp_under_userid'");
			mysqli_query($con,"update tree set `$temp_side_rp`=$current_temp_side_rp where userid='$temp_under_userid'");
			
			//income
			if($temp_under_userid!=""){
				$income_data = income($temp_under_userid);
				//check capping
				//if($income_data['matching_bal']<$capping){
					$tree_data = tree($temp_under_userid);
					
					$temp_left_amount = $tree_data['leftamount'];
					$temp_right_amount = $tree_data['rightamount'];
					$temp_left_rp = $tree_data['leftrp'];
					$temp_right_rp = $tree_data['rightrp'];


					//Both left and right side should at least have pair amount(pair will be on less amount)
					if($temp_left_amount>0 && $temp_right_amount>0){
						$per = 20;
						//if($temp_side=='left'){
							$temp_left_amount;
							$temp_right_amount;
							if($temp_left_amount<$temp_right_amount){
		
								$new_day_bal = $income_data['day_bal']+($temp_left_amount*$per)/100;
								$new_direct_bal = $income_data['direct_bal']+0;
								$new_matching_bal = $income_data['matching_bal']+($temp_left_amount*$per)/100;

								////////////////////////////////////////////////////////////////

								$sql = mysqli_query($con,"select * from tree where userid='$temp_under_userid'");
								$ans = mysqli_fetch_array($sql);
								$c_matchedamount = $ans['matchedamount'];
								$new_matchedamount = $c_matchedamount + $temp_left_amount;
								mysqli_query($con,"update tree set matchedamount='$new_matchedamount' where userid='$temp_under_userid'");
								mysqli_query($con,"insert into monthly_matching_balance(userid,date,matchedbalance) values('$temp_under_userid','$join_date','$temp_left_amount')");


								////////////////////////////////////////////////////////////////

								$temp_right_amount = $temp_right_amount - $temp_left_amount;
								$temp_left_amount = 0;


								mysqli_query($con,"update tree set leftamount='$temp_left_amount' where userid='$temp_under_userid'");
								mysqli_query($con,"update tree set rightamount='$temp_right_amount' where userid='$temp_under_userid'");
								//update income
								mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal',matching_bal='$new_matching_bal' where userid='$temp_under_userid'");	
								echo mysqli_error($con);
							}
						//}
						//else{
							if($temp_right_amount<=$temp_left_amount){
						
								$new_day_bal = $income_data['day_bal']+($temp_right_amount*$per)/100;
								$new_direct_bal = $income_data['direct_bal']+0;
								$new_matching_bal = $income_data['matching_bal']+($temp_right_amount*$per)/100;
								
								////////////////////////////////////////////////////////////////

								$sql = mysqli_query($con,"select * from tree where userid='$temp_under_userid'");
								$ans = mysqli_fetch_array($sql);
								$c_matchedamount = $ans['matchedamount'];
								$new_matchedamount = $c_matchedamount + $temp_right_amount;
								mysqli_query($con,"update tree set matchedamount='$new_matchedamount' where userid='$temp_under_userid'");
								mysqli_query($con,"insert into monthly_matching_balance(userid,date,matchedbalance) values('$temp_under_userid','$join_date','$temp_right_amount')");


								////////////////////////////////////////////////////////////////

								$temp_under_userid;
								$temp_left_amount = $temp_left_amount - $temp_right_amount;
								$temp_right_amount = 0;
								mysqli_query($con,"update tree set leftamount='$temp_left_amount' where userid='$temp_under_userid'");
								mysqli_query($con,"update tree set rightamount='$temp_right_amount' where userid='$temp_under_userid'");
								//update income
								if(mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal', matching_bal='$new_matching_bal' where userid='$temp_under_userid'")){
								echo mysqli_error($con);
								}
							}
						//}
						//Both left and right side....
					}
				//}

					//RP Distribution created: 04/06/2019
					if ($temp_left_rp>0 && $temp_right_rp>0) {
						$temp_left_rp;
						$temp_right_rp;
						if ($temp_left_rp<$temp_right_rp) {
							$sql = mysqli_query($con,"select * from tree where userid='$temp_under_userid'");
							$ans = mysqli_fetch_array($sql);
							$c_matchedrp = $ans['matchedrp'];
							$new_matchedrp = $c_matchedrp+$temp_left_rp;
							mysqli_query($con,"update tree set matchedrp='$new_matchedrp' where userid='$temp_under_userid'");

							//////////////////////

							$temp_right_rp = $temp_right_rp - $temp_left_rp;
							$temp_left_rp = 0;

							mysqli_query($con,"update tree set leftrp='$temp_left_rp' where userid='$temp_under_userid'");
							mysqli_query($con,"update tree set rightrp='$temp_right_rp' where userid='$temp_under_userid'");
						}
						if ($temp_right_rp<=$temp_left_rp) {
							$sql = mysqli_query($con,"select * from tree where userid='$temp_under_userid'");
							$ans = mysqli_fetch_array($sql);
							$c_matchedrp = $ans['matchedrp'];
							$new_matchedrp = $c_matchedrp+$temp_right_rp;
							mysqli_query($con,"update tree set matchedrp='$new_matchedrp' where userid='$temp_under_userid'");

							//////////////////////

							$temp_left_rp = $temp_left_rp - $temp_right_rp;
							$temp_right_rp = 0;

							mysqli_query($con,"update tree set leftrp='$temp_left_rp' where userid='$temp_under_userid'");
							mysqli_query($con,"update tree set rightrp='$temp_right_rp' where userid='$temp_under_userid'");
						}
					}

				
				//change under_userid
				$next_under_userid = getUnderId($temp_under_userid);
				$temp_side = getUnderIdPlace($temp_under_userid);
				$temp_side_count = $temp_side.'count';
				$temp_side_amount = $temp_side.'amount';
				$temp_side_rp = $temp_side.'rp';
				$temp_under_userid = $next_under_userid;	
				
				$i++;
			}
			
			//Check for the last user
			if($temp_under_userid==""){
				$total_count=0;
			}
			
		}//Loop
		
		$link = "http://widesky.online/login";
		$mail = new PHPMailer(TRUE);
		try{
			$mail->setFrom('admin@widesky.online', 'WIDESKY E-RETAILS PVT. LTD.');
			$mail->addAddress($email,$name);
			$mail->Subject = 'Login Details | Members Area | WIDESKY E-RETAILS PVT. LTD.';
			$mail->isHTML(TRUE);$mail->isSMTP();
            $mail->Host = "mail.widesky.online";
            // optional
            // used only when SMTP requires authentication  
            $mail->SMTPAuth = true;
            $mail->Username = 'admin@widesky.online';
            $mail->Password = 'wideskyadmin@007#';
			$mail->Body = "<h1>Members Area | WIDESKY E-RETAILS PVT. LTD.</h1>
			<h2>Registration Details</h2>
			Thank you for registring with us. Your login details are following: <br>
			<table rules='all' style='border-color: #666;'' cellpadding='10'>
			<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . $name . "</td></tr>
			<tr><td><strong>Email/UserID:</strong> </td><td>" . $email . "</td></tr>
			<tr><td><strong>Password:</strong> </td><td>" . $password . "</td></tr>
			<tr><td><strong>Sponsor ID:</strong> </td><td>" . $sponsorid . "</td></tr>
			<tr><td><strong>Login here:</strong> </td><td>" . $link . "</td></tr></table><br>
			<p>For any queries please call us at: +91 7452000510</p>
			";
			$mail->send();
		}
		catch (Exception $e)
		{
		   /* PHPMailer exception. */
		   echo $e->errorMessage();
		}
		catch (\Exception $e)
		{
		   /* PHP exception (note the backslash to select the global namespace Exception class). */
		   echo $e->getMessage();
		}
		
		echo '<script>alert("Successfully Registered. Login details has been send to given email id.");</script>';
	}
	
}
?><!--/join user-->
<?php 
//functions
function pin_check($pin){
	global $con,$userid;
	
	$query =mysqli_query($con,"select * from pin_list where pin='$pin' and userid='$userid' and status='open'");
	if(mysqli_num_rows($query)>0){
		return true;
	}
	else{
		return false;
	}
}
function email_check($email){
	global $con;
	
	$query =mysqli_query($con,"select * from user where email='$email'");
	if(mysqli_num_rows($query)>0){
		return false;
	}
	else{
		return true;
	}
}
function side_check($email,$side){
	global $con;
	
	$query =mysqli_query($con,"select * from tree where userid='$email'");
	$result = mysqli_fetch_array($query);
	$side_value = $result[$side];
	if($side_value==''){
		return true;
	}
	else{
		return false;
	}
}
function income($userid){
	global $con;
	$data = array();
	$query = mysqli_query($con,"select * from income where userid='$userid'");
	$result = mysqli_fetch_array($query);
	$data['day_bal'] = $result['day_bal'];
	$data['direct_bal'] = $result['direct_bal'];
	$data['matching_bal'] = $result['matching_bal'];
	$data['current_bal'] = $result['current_bal'];
	$data['total_bal'] = $result['total_bal'];
	
	return $data;
}
function tree($userid){
	global $con;
	$data = array();
	$query = mysqli_query($con,"select * from tree where userid='$userid'");
	$result = mysqli_fetch_array($query);
	$data['left'] = $result['left'];
	$data['right'] = $result['right'];
	$data['pack'] = $result['pack'];
	$data['leftcount'] = $result['leftcount'];
	$data['rightcount'] = $result['rightcount'];
	$data['leftamount'] = $result['leftamount'];
	$data['rightamount'] = $result['rightamount'];
	$data['matchedamount'] = $result['matchedamount'];
	$data['leftrp'] = $result['leftrp'];
	$data['rightrp'] = $result['rightrp'];
	$data['matchedrp'] = $result['matchedrp'];
	return $data;
}
function getUnderId($userid){
	global $con;
	$query = mysqli_query($con,"select * from user where email='$userid'");
	$result = mysqli_fetch_array($query);
	return $result['under_userid'];
}
function getUnderIdPlace($userid){
	global $con;
	$query = mysqli_query($con,"select * from user where email='$userid'");
	$result = mysqli_fetch_array($query);
	return $result['side'];
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Join | WIDESKY E-RETAILS PVT. LTD.</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="theme-red">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <?php include('php-includes/menu.php'); ?>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h1>JOIN</h1>
            </div>
            <div class="row clearfix">
            	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            		<div class="card">
            			<div class="header">Pin Validation</div>
            			<div class="body">
            				<form method="get">
		                		<div class="form-group">
				                    <label>Pin</label>
				                    <div class="form-line">
				                    	<input type="text" name="pin" class="form-control" required>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <input type="submit" name="join_pin" class="btn btn-primary" value="Check Availiability">
				                </div>
	                		</form>
            			</div>
            		</div>
            	</div>
            </div>
            <div class="row clearfix">
            	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            		<div class="card">
            			<div class="header">Registration Form</div>
            			<div class="body">
            				<form method="get">
	                            <div class="form-group">
	                                	<?php
											if (isset($_GET['join_pin'])) {
												$pin = mysqli_real_escape_string($con,$_GET['pin']);
												if (pin_check($pin)) {
													$query = mysqli_query($con,"select * from pin_list where pin='$pin'");
													$result = mysqli_fetch_array($query);
													$pack = $result['pack'];
													echo 'Pin Available<br>';
													echo '<strong>Pin: </strong>'.$pin.'<br>';
													echo '<strong>Package: </strong>'.$pack;
												}
												else{
													echo '<script>alert("Pin not available. Please use another pin.");</script>';
												}
											}
										?>
	                                </p>
		                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			                            <input type="hidden" name="pin" value="<?php echo $pin; ?>">
			                            <input type="hidden" name="pack" value="<?php echo $pack; ?>">
			                            <div class="form-group">
			                                <label>Sponsor ID</label>
			                                <div class="form-line">
			                                	<input type="email" name="sponsorid" class="form-control" required>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label>Name</label>
			                                <div class="form-line">
			                                	<input type="text" name="name" class="form-control" required>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label>Email</label>
			                                <div class="form-line">
			                                	<input type="email" name="email" class="form-control" required>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label>Password</label>
			                                <div class="form-line">
			                                	<input type="text" name="password" class="form-control" required>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label>Mobile</label>
			                                <div class="form-line">
			                                	<input type="text" name="mobile" class="form-control" required>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label>Gender</label><br>
			                                <input type="radio" name="gender" id="male" value="Male" class="with-gap">
                                    		<label for="male">Male</label>
                                    		<input type="radio" name="gender" id="female" value="Female" class="with-gap">
                                    		<label for="female">Female</label>
                                    		<input type="radio" name="gender" id="other" value="Other" class="with-gap">
                                    		<label for="other">Other</label>
			                            </div>
			                            <div class="form-group">
			                                <label>Date of Birth</label>
			                                <div class="form-line">
			                                	<input type="date" name="dob" class="form-control" required>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
		                        <div class="row clearfix">
			                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			                            <div class="form-group">
			                                <label>Address</label>
			                                <div class="form-line">
			                                	<input type="text" name="address" class="form-control" required>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label>Bank Name</label>
			                                <div class="form-line">
			                                	<input type="text" name="bank" class="form-control" required>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label>IFSC</label>
			                                <div class="form-line">
			                                	<input type="text" name="ifsc" class="form-control" required>
			                                </div> 
			                            </div>
			                            <div class="form-group">
			                                <label>Account</label>
			                                <div class="form-line">
			                                	<input type="text" name="account" class="form-control" required>
			                                </div>   
			                            </div>
			                            <div class="form-group">
			                                <label>PAN</label>
			                                <div class="form-line">
			                                	<input type="text" name="pan" class="form-control" required>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label>Under Userid</label>
			                                <div class="form-line">
			                                	<input type="email" name="under_userid" class="form-control" required>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label>Side</label><br>
			                                <input type="radio" name="side" id="left" value="left" class="with-gap">
                                    		<label for="left">Left</label>
                                    		<input type="radio" name="side" id="right" value="right" class="with-gap">
                                    		<label for="right">Right</label>
			                            </div>
			                            <div class="form-group">
			                                <input type="checkbox" id="terms" name="terms" class="filled-in" unchecked />
                                			<label for="terms">I agree to the </label>
                                			<button type="button" class="btn btn-default waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Terms & Conditions.</button>
                                			<!-- Large Size -->
								            <div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
								                <div class="modal-dialog modal-lg" role="document">
								                    <div class="modal-content">
								                        <div class="modal-header">
								                            <h4 class="modal-title" id="largeModalLabel">Terms & Conditions</h4>
								                        </div>
								                        <div class="modal-body">
								                            <p>POLICIES &amp;&nbsp;&nbsp; PROCEDURES</p>
<p>SECTION 1 - INTRODUCTION</p>
<p>1.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; POLICIES INCORPORATED INTO THE INDEPENDENT DISTRIBUTOR(S) AGREEMENT These Policies and Procedures are an integral part of the WIDESKY E-RETAILS&nbsp; Private Limited (&ldquo;WIDESKY&rdquo;) Independent Distributor(s) Application &amp; Agreement in their present form and as amended from time to time at WIDESKY's discretion. It is the responsibility of each Independent Distributor(s) to read, understand, adhere to, and ensure that he/she is aware of and operating under the most current version of these Policies and Procedures. For the purposes of these policies the term Independent Distributor(s) refers to all individuals who entered into an Independent Distributor(s) Application and Agreement with WIDESKY by submitting the signed application and agreement to WIDESKY and whose application was accepted by WIDESKY. If you do not wish to adhere to the Policies and Procedures listed in this document or any of the terms of the Independent Distributor Agreement, please do not execute the application and/or execute the Independent Distributor Agreement.</p>
<p>1.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TITLES NOT SUBSTANTIVE The titles and headings to these Policies and Procedures are for reference purposes only and do not constitute, and shall not be construed as, substantive terms of these Policies and Procedures or used for interpretation purposes.</p>
<p>1.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WAIVER WIDESKY never forfeits its right to require an Independent Distributor(s) to comply with these Policies and Procedures or the Independent Distributor(s) Agreement or with applicable laws and regulations governing business conduct. Only in rare circumstances will a term of the policy be waived, and such waivers will be conveyed in writing by the Compliance Ofﬁcer of&nbsp; WIDESKY and shall apply only to that speciﬁc case.</p>
<p>1.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WIDESKY COMPLIANCE DEPARTMENT The Independent Distributor(s) may contact the WIDESKY Compliance Department personally during business hours at the corporate ofﬁce of WIDESKY or by e-mailing the WIDESKY Compliance Department at&nbsp; customersupport@widesky.online.</p>
<p>SECTION 2 - BECOMING AN INDEPENDENT DISTRIBUTOR(S)</p>
<p>2.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IDENTIFICATION AND INDEPENDENT DISTRIBUTOR(S) NUMBER Each Independent Distributor(s) will be allotted a unique membership number. This will become the Distributor(s) ID. Independent Distributor(s) must use their Distributor(s) ID whenever they call the WIDESKY Distributor(s) Services departments to place orders and track payouts and for any other communication with WIDESKY.</p>
<p>2.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MEMBERSHIP/Retail CARD Independent Distributor(s) will be issued a membership card within ﬁfteen (15) days from the date of joining i.e. from the date when WIDESKY accepts the application form in accordance with the Independent Distributor Agreement. If a Distributor(s) membership card is lost, broken or misplaced, a written letter along with a nominal fee of Rs.100 /-(Rupees Hundred Only) must be remitted for reissue of card and such card will be reissued within ﬁfteen (15) days from the date of receipt of such request along with the prescribed fee.</p>
<p>Version 1.1</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>2.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; INDEPENDENT DISTRIBUTOR(S) BENEFITS Once WIDESKY accepts an Independent Distributor(s) Application and Agreement, the beneﬁts of the Business Plan and the Independent Distributor(s) Agreement are available to the new Independent Distributor(s). These beneﬁts are as follows:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a.&nbsp;&nbsp; To purchase WIDESKY products and services at the distributor(s) price.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp; To participate in the WIDESKY Business Plan (receive payouts, if eligible). Sponsor other eligible&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; individuals as Independent Distributor(s) into the WIDESKY business and thereby build a Downline in the&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; organization and progress through the WIDESKY Business Plan. The Independent Distributor shall be&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; responsible for obtaining all clariﬁcations and understanding the process, terms, conditions, beneﬁts and&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; responsibilities of the Independent Distributor and WIDESKY and shall be deemed to have understood&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the same by executing the Independent Distributor Agreement and Application. No adverse inference shall&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; be made against WIDESKY by virtue of it having drafted the Independent Distributor Agreement and&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Application and the Policies and Procedures and the Business Plan.&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c.&nbsp;&nbsp;&nbsp; Receive Ofﬁcial WIDESKY Material and other WIDESKY communications.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d.&nbsp; Participate in WIDESKY-sponsored support, service, training, motivational, and recognition functions upon&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; payment of appropriate charges, if applicable.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e.&nbsp;&nbsp;&nbsp; Participate in promotional and incentive contests and programs sponsored by WIDESKY for its Independent Distributor(s).&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; f.&nbsp;&nbsp;&nbsp; Independent Distributor(s) may retail WIDESKY products and earn retail proﬁt from these sales,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;subject to the terms and conditions of these Policies and Procedures.</p>
<p>2.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; REFUND POLICY</p>
<p>2.4.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CUSTOMER REFUND POLICY Retail customers are guaranteed 100% product satisfaction within 30 days from the date of purchase of the product. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a.&nbsp;&nbsp;&nbsp; Retail customers can obtain a new replacement for any defective product from the distributor.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp; Retail customers can cancel the purchase, return the products and obtain a full refund from the distributor.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Widesky reserves the right to reject repetitive product returns.</p>
<p>2.4.2&nbsp;&nbsp;&nbsp;&nbsp; BUY BACK FROM DISTRIBUTORS WIDESKY shall buy back any unsold, saleable WIDESKY products( other than literature) that has been purchased within the previous twelve months from any WIDESKY distributor who terminates his WIDESKY distributorship.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a.&nbsp;&nbsp;&nbsp; The products should be returned with relevant invoices and should be unopened and in saleable condition&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and must be purchased in the previous twelve months.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp; Upon receipt of these items, reimbursement will be issued to the distributor for the full amount paid for the&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; returned product by the distributor, less 15% processing fee.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c.&nbsp;&nbsp;&nbsp; Shipping charges, service tax and sales tax paid on the original order will not be reimbursed.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d.&nbsp;&nbsp; Widesky &nbsp;will deduct the amount of commissions or any other earnings, beneﬁts paid on the returned&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; products from the appropriate distributors and adjust ranks as needed. 2.4.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PRODUCT STACKING AND INVENTORY LOADING Every distributor shall ensure that atleast 70% of the products purchased in the prior order is retailed before placing the next product order with the company.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a.&nbsp;&nbsp;&nbsp;&nbsp; Distributors should keep accurate records of monthly sales to their customers and must be produced upon&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; company&rsquo;s request for inspection&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp;&nbsp; Products previously certiﬁed as having been sold, consumed or retailed shall not be subject to repurchase&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; under the Buy back scheme.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c.&nbsp;&nbsp;&nbsp;&nbsp; The Buy back policy is designed to ensure that the distributors are buying products wisely.</p>
<p>4</p>
<p>Version 1.1</p>
<p>5</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>SECTION 3 - OPERATING A WIDESKY DISTRIBUTOR(S)SHIP</p>
<p>3.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ACTIONS OF HOUSEHOLD MEMBERS If any member of an Independent Distributor(s) immediate household engages in any activity which, if performed by the Independent Distributor(s) would violate any provision of the Independent Distributor(s) Agreement, such activity will be deemed a violation by the Independent Distributor(s).</p>
<p>3.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ADHERENCE TO THE WIDESKY BUSINESS PLAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a.&nbsp;&nbsp;&nbsp;&nbsp; Independent Distributor(s) must adhere to the terms of the WIDESKY Business Plan as set forth in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the Ofﬁcial WIDESKY Material .&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp;&nbsp; Independent Distributor(s) shall not offer the WIDESKY opportunity through, or in combination&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; with, any other opportunity or unapproved method of marketing such as misrepresentation about the&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;healing qualities of a product or providing any information about the product which is incorrect, false or&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; exaggerated.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c.&nbsp;&nbsp;&nbsp; Independent Distributor(s) shall not require or encourage other current or prospective Independent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s) to participate in WIDESKY in any manner that deviates / varies from the program as&nbsp; set&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; forth in&nbsp; Ofﬁcial WIDESKY Material.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d.&nbsp;&nbsp;&nbsp; Independent Distributor(s) shall not require or encourage other current or prospective independent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; distributor(s) to execute any agreement or contract other than ofﬁcial WIDESKY agreements and&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; contracts in order to become a WIDESKY Independent Distributor(s).&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e.&nbsp;&nbsp;&nbsp; Similarly, Independent Distributor(s) shall not require or encourage other current or prospective Independent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s) to make any purchase from, or payment to, any individual or other entity to participate in the&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WIDESKY Business Plan, other than those purchases or payments identiﬁed as recommended or&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; required&nbsp; in Ofﬁcial WIDESKY Material.</p>
<p>3.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ADVERTISING</p>
<p>3.3.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IN GENERAL Independent Distributor(s) must avoid all discourteous, deceptive, misleading, illegal, unethical, or immoral conduct or practices in their marketing and promotion of WIDESKY, the WIDESKY&nbsp; opportunity, the Business Plan, and WIDESKY's products.</p>
<p>3.3.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TELEVISION, RADIO AND PRINT Independent Distributor(s) may advertise on television and radio and in print or by any other mode, subject to WIDESKY's express prior written approval</p>
<p>3.3.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MEDIA INQUIRIES Independent Distributor(s) must refer all media inquiries regarding WIDESKY to the WIDESKY Compliance Department. This will ensure that accurate and consistent information reaches the general public.</p>
<p>3.3.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; INTELLECTUAL PROPERTY RIGHTS The Independent Distributor acknowledges that the right, title and interest in all the products of WIDESKY and all the Ofﬁcial WIDESKY Material belongs to and is proprietary to WIDESKY and/or its afﬁliated entities (whether registered, registrable or not) and shall remain so and the Independent Distributor shall not be responsible, whether directly or indirectly, for any action or omission which infringes or potentially infringes this right. WIDESKY shall be entitled to speciﬁc performance. Without prejudice to the generality of the foregoing:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a.&nbsp;&nbsp;&nbsp;&nbsp; An Independent Distributor(s) should not use the WIDESKY trademark or trade name or corporate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; logo to promote their independent business without the prior written approval from WIDESKY.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp;&nbsp; Independent Distributor(s) may describe themselves as a &ldquo;WIDESKY Independent Distributor(s)&rdquo;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; in the business pages of the telephone directory.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c.&nbsp;&nbsp;&nbsp; Independent Distributor(s) should not answer the telephone in any manner that might indicate or&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; suggest that the caller has reached a WIDESKY corporate ofﬁce.</p>
<p>3.3.5&nbsp; Widesky E-retails pvt ltd. has right to change/amend or replace any policy or business plan terms etc.</p>
<p>Version 1.1</p>
<p>6</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<ol>
<li>Independent Distributor(s) may not record, reproduce, or copy any presentation, or materials from&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; any WIDESKY corporate function or event, or speech by any WIDESKY spokesperson,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; representative, speaker, ofﬁcer, director, or other Independent Distributor(s) or any Ofﬁcial&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WIDESKY Material.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e.&nbsp;&nbsp;&nbsp; Independent Distributor(s) may not reproduce or copy any recording of a WIDESKY produced&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; media presentation including audio tapes, videotapes, compact discs, etc.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; f.&nbsp;&nbsp;&nbsp;&nbsp; Independent Distributor(s) may not publish, or cause to be published, in any written or electronic&nbsp; media,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the name, photograph or likeness, copyrighted materials, or property of individuals or Independent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s) with WIDESKY without express prior written authorization from the individual&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and WIDESKY.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; g.&nbsp;&nbsp;&nbsp; Independent Distributor(s) may not publish, or cause to be published, in any written form or electronic&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; media, the copyrighted materials or property of WIDESKY, without express written authorization&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; from WIDESKY.</li>
</ol>
<p>3.3.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; USE OF INDEPENDENT DISTRIBUTOR(S) NAME, LIKENESS, AND IMAGE Independent Distributor(s) hereby consents to WIDESKY's use of his/her name, testimonial (or other statements about WIDESKY, its products or opportunity in printed or recorded form, including translations, paraphrases, and electronic reproductions of the same), and image or likeness (as produced or recorded in photographic, digital, electronic, video or ﬁlm media) in connection with advertising, promoting, and publicizing the WIDESKY opportunity or products, or any WIDESKY-related or -sponsored events.</p>
<p>3.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;INDEPENDENT DISTRIBUTOR(S) CLAIMS AND REPRESENTATIONS</p>
<p>3.4.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PRODUCT CLAIMS WIDESKY Independent Distributor(s) may not make claims or representations that WIDESKY products have therapeutic or curative properties except those contained in Ofﬁcial WIDESKY Material. In particular, no Independent Distributor(s) may make any claim or representation that WIDESKY products are useful in the cure, treatment, diagnosis, mitigation, or prevention of any diseases. Such statements can be perceived as medical or drug claims. Not only are such claims and representations violative of the Independent Distributor(s) Agreement, but they also violate the laws and regulations of India.</p>
<p>3.4.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; INCOME CLAIMS Independent Distributor(s) may not make income projections or claims or when presenting or discussing the WIDESKY opportunity or Business Plan, except as set forth in Ofﬁcial WIDESKY Material.</p>
<p>3.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; COMMERCIAL OUTLETS Independent Distributor(s) may only display and retail WIDESKY products in approved service-oriented establishments where professional services are the primary source of revenue and product sales are secondary. Such approved service-oriented establishments shall include (but are not limited to) health spas, beauty shops, and physicians' ofﬁces. Unapproved retail-oriented establishments may include (but are not limited to) retail stores, Internet auction sites, and pharmacies.</p>
<p>3.6&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; UNAUTHORIZED RECRUITING WIDESKY Independent Distributor(s)may participate in other direct selling or network marketing or direct selling ventures (collectively, &ldquo;Direct Selling&rdquo;),and Independent Distributor(s)may engage in selling activities related to non-WIDESKY products and services, if they desire to do so. Although an Independent Distributor(s) may elect to participate in another Direct Selling opportunity, he/she is prohibited from unauthorized recruiting activities, which include the following:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a.&nbsp;&nbsp; Recruiting or enrolling WIDESKY customers or Independent Distributor(s) for other Direct&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selling ventures, either directly or through a third party. This includes, but is not limited to,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; presenting or assisting in the presentation of other Direct Selling ventures to any WIDESKY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Independent Distributor(s), or implicitly or explicitly encouraging any WIDESKY Independent</p>
<p>Version 1.1</p>
<p>7</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s) to join other business ventures. It is a violation of this policy to recruit or enroll a&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WIDESKY Independent Distributor(s) for another Direct Selling business, even if the&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Independent Distributor(s) does not know that the prospect is also a WIDESKY Independent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s). Therefore, each WIDESKY Independent Distributor should speciﬁcally seek&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; information from a prospective independent distributor about his/her/its participation in other&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; direct selling agencies and companies;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp;&nbsp; Producing any literature, tapes, or promotional material of any nature for another Direct Selling&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; business which is used by the Independent Distributor(s) or any third person to recruit Widesky &nbsp;Independent Distributor(s) for that business venture;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;c.&nbsp;&nbsp;&nbsp; Selling, offering to sell, or promoting any competing products or services to WIDESKY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Independent Distributor(s);&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d.&nbsp;&nbsp;&nbsp; Offering WIDESKY products or promoting the WIDESKY Business Plan in conjunction with&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;any non-WIDESKY business plan, opportunity, product, or incentive;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e.&nbsp;&nbsp;&nbsp; Offering any non-WIDESKY products or opportunities in conjunction with the offering of&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WIDESKY products or business plan or at any WIDESKY meeting, seminar, launch,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; convention, or other WIDESKY function;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; f.&nbsp;&nbsp;&nbsp;&nbsp; Where a prospective Independent Distributor(s) accompanies an Independent Distributor(s) to a&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WIDESKY meeting or function, no other WIDESKY Independent Distributor(s) may recruit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the prospect to enroll in WIDESKY or any other Direct Selling business for a period of fourteen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (14) days or unless and until the Independent Distributor(s) who brought the prospect to the&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;function advises the other WIDESKY Independent Distributor(s) that the prospect has elected&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; not to enroll in WIDESKY and that the Independent Distributor(s) is no longer recruiting the&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; prospect to enroll in WIDESKY, whichever occurs ﬁrst. WIDESKY will immediately cancel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the independent distributor(s)ship of any Independent Distributor(s) who violates this provision.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Violations of this policy are especially detrimental to the growth and sales of other Independent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s) and to WIDESKY's business; and&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; g.&nbsp;&nbsp;&nbsp; Where an Independent Distributor(s) participates in other Direct Selling ventures, they may not&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; participate in WIDESKY's Leadership Bonus Program.</p>
<p>3.6.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; POST CANCELLATION SOLICITATION PROHIBITED A former Independent Distributor(s) shall not directly or through a third party solicit any WIDESKY Independent Distributor(s) to enroll in any direct sales, network marketing or Direct Selling program or opportunity for a period of one (1) year after the cancellation or termination of an individual or entity's Independent Distributor(s) Agreement. This provision shall survive the expiration of the Independent Distributor(s) obligations to WIDESKY , pursuant to the Independent Distributor(s) Agreement.</p>
<p>3.6.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DOWNLINE ORGANIZATION REPORTS The WIDESKY Downline Organization Reports online are CONFIDENTIAL and contain proprietary business trade secrets. An Independent Distributor(s) may not use the reports for any purpose other than for developing their WIDESKY business. Where an Independent Distributor(s) participates in other Direct Selling ventures, he/she is not eligible to have access to Downline Organization Reports. Therefore, each Independent Distributor is required to disclose his/her/its membership with other Direct Selling entities and any other entity who may be or is a competitor of WIDESKY. The Independent Distributor(s) acknowledges and agrees that the Downline Organization Reports are being provided to the Independent Distributor(s) subject to this agreement of conﬁdentiality and nondisclosure and in the event of non-compliance with these requirements, WIDESKY will take severe action which may even lead to termination and shall not provide access to Downline Organization Reports to the Independent Distributor(s). During any term of the Independent Distributor(s) Agreement and for a period of ﬁve (5) years after the termination or expiration of the Independent Distributor(s) Agreement between Independent Distributor(s) and WIDESKY, for any reason whatsoever, an Independent Distributor(s) shall not, on his/her own behalf or on behalf of any other person, partnership, association, corporation, or other entity:</p>
<p>Version 1.1</p>
<p>8</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<ol>
<li>Disclose any information contained in the Downline Organization Reports to any third party;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp; Use the Downline Organization Reports or any information contained in it, to compete with&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WIDESKY; or&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c.&nbsp;&nbsp;&nbsp; Recruit or solicit any Independent Distributor(s) listed on the Downline Organization Reports to&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; participate in other Direct Selling ventures.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; This provision shall survive the termination or expiration of the Independent Distributor(s)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Agreement.</li>
</ol>
<p>3.7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CORPORATION, PARTNERSHIPS, AND TRUSTS A corporation, partnership, or trust (collectively referred to in this section as an &ldquo;Entity&rdquo;) may apply to be a WIDESKY Independent Distributor(s)by submitting its Certiﬁcate of Incorporation, Partnership Agreement, or trust documents (these documents are collectively referred to as the &ldquo;Entity Documents&rdquo;) to WIDESKY, along with a properly completed Corporation, Partnership Registration. Distributor(s)ship may change its status under the same sponsor from an individual to a partnership, corporation, or trust, or from one type of entity to another. To do so, the Independent Distributor(s) must provide the Entity Documents and submit a properly completed Independent Distributor(s) Application and Agreement to WIDESKY. The Independent Distributor(s) Application and Agreement must be signed by all of the shareholders, partners, trustees, or other individuals having an ownership or interest in the business. Members of the Entity are jointly and severally liable for any indebtedness or other obligation to WIDESKY. It is the responsibility of those persons involved in the Entity to conform to the laws of the state in which their Entity is formed. WIDESKY reserves the right to approve or disapprove any Independent Distributor(s) Application and Agreement submitted by an Entity, as well as any Independent Distributor(s) Application and Agreement submitted by any current Independent Distributor(s)for the formation of an Entity for tax, estate planning, and limited liability purposes.</p>
<p>3.8&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DECEPTIVE PRACTICES Independent Distributor(s) must fairly and truthfully explain the&nbsp; WIDESKY products, opportunity business Plan and Policies and Procedures to prospective Independent Distributor(s). This includes:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a.&nbsp;&nbsp;&nbsp; Being honest and thorough in presenting material from the WIDESKY Business Plan to all&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; potential Independent Distributor(s).&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp;&nbsp; Making clear that income from the WIDESKY Business Plan is based on product sales to get&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the generation income and by sponsoring new member(s) to get the binary income.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c.&nbsp;&nbsp;&nbsp; Making estimates of proﬁt that are based on reasonable predictions for what an average&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Independent Distributor(s) would achieve in normal circumstances.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d.&nbsp;&nbsp;&nbsp; Representing that past earnings in a given set of circumstances do not necessarily reflect future&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; earnings.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e.&nbsp;&nbsp;&nbsp; Not misrepresenting the amount of expenditure that an average Independent Distributor(s)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; might incur in carrying on the business.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; f.&nbsp;&nbsp;&nbsp;&nbsp; Not misrepresenting the amount of time an average Independent Distributor(s) would have to&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;devote to the business to achieve the proﬁt estimated, and not stating that proﬁts or earnings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; are guaranteed for any individual Independent Distributor(s).&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; g.&nbsp;&nbsp;&nbsp; Never stating or inferring that you will build a Downline Organization for anyone else.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; h.&nbsp;&nbsp;&nbsp; Never stating that any consumer, business, or government agency has approved or endorsed&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the WIDESKY products or its Business Plan.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; i.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Never participating in downline purchasing (placing a sales order in a different ID other than&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the ID under which the sale was generated).</p>
<p>3.9&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; INDEPENDENT CONTRACTOR STATUS Independent Distributor(s) are independent contractors and are not purchasers of a franchise or business opportunity. The agreement between WIDESKY and its Independent Distributor(s) does not create an employer/employee relationship, agency, partnership, or joint venture between the Company and the Independent Distributor(s). All Independent Distributor(s) are responsible for paying their own income and employment taxes. Independent Distributor(s) will not be treated as an employee for&nbsp; any</p>
<p>Version 1.1</p>
<p>9</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>purpose. Each Independent Distributor(s) is encouraged to establish his/her own goals, hours, and methods of sale, as long as he/she complies with applicable laws and the terms and conditions of the Independent Distributor(s) Agreement.</p>
<p>3.10&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ADHERENCE TO LAWS You must obey all laws that apply to your business.</p>
<p>3.11&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ONE DISTRIBUTORSHIP PER PERSON An independent distributor may have only one WIDESKY distributorship. A legal married couple is only allowed to apply for one distributorship</p>
<p>3.12&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; REPACKAGING AND RELABELING PROHIBITED Independent Distributor(s) may not re-label or alter the labels on any WIDESKY products, information, materials, or programs in any way. Independent Distributor(s) may not repackage or reﬁll any WIDESKY products. WIDESKY products must be sold in their original containers only. Such re-labelling or repackaging would violate governing laws, which could result in severe criminal penalties. Civil liability may also result when the persons using the products suffer any type of injury or their property is damaged as a consequence of the repackaging or re-labelling of products.</p>
<p>3.13&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TRANSFER, OR ASSIGNMENT OF DISTRIBUTOR(S)SHIP An Independent Distributor(s) may not transfer, or assign their Distributor(s)ship rights to any person or entity without WIDESKY's express written approval. To obtain approval, you must:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a.&nbsp;&nbsp;&nbsp; Opt for transfer of the distributor(s)ship within thirty (30) days from the date of joining and&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; subject to the transferee being eligible to become a distributor of WIDESKY.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp;&nbsp; Be an Independent Distributor(s) in good standing as determined by WIDESKY in its sole&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; discretion.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c.&nbsp;&nbsp;&nbsp; Before any transfer will be approved by WIDESKY, any debt obligations the transferring&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Independent Distributor(s) has with WIDESKY must be satisﬁed.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d.&nbsp;&nbsp;&nbsp; The transferring Independent Distributor(s) must be in good standing and not in violation of&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; any of the terms of the Independent Distributor(s) Agreement or Policies and Procedures, to&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; transfer his/her Distributor(s)ship.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e.&nbsp;&nbsp;&nbsp;&nbsp; The combining of Distributor(s)ships is not permitted.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; f.&nbsp;&nbsp;&nbsp;&nbsp; WIDESKY will not approve the transfer of a Distributor(s)ship to any individual or Entity&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; that is a current Independent Distributor(s) or who has an ownership interest in any&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s)ship. Similarly, WIDESKY will not approve the transfer of a Distributor(s)ship&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to any individual or Entity that has previously had any ownership interest in, or operated, a&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WIDESKY Distributor(s)ship.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; g.&nbsp;&nbsp;&nbsp; No Independent Distributor(s) who is a also a Stockist may transfer his/her Distributor(s)ship&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; independently of his Stockist. If an Independent Distributor(s) wishes to transfer his/her&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s)ship, all Stockists must be included in the transfer.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; h.&nbsp;&nbsp;&nbsp; The transferring Independent Distributor(s) must notify the WIDESKY Compliance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Department of his/her intent to transfer the Distributor(s) ship by completing and submitting a&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; signed Transfer of Distributor(s)ship and Independent Distributor(s) Application Form.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; i.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No changes in line of sponsorship can result from the transfer of a Distributor(s)ship.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; j.&nbsp;&nbsp;&nbsp;&nbsp; Approval from WIDESKY Compliance Department by submitting the &ldquo;Transfer Agreement&rdquo;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; duly signed by the existing &amp; the New Distributor(s).&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; k.&nbsp;&nbsp;&nbsp; The No objection certiﬁcate (NOC) from the Sponsor.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; l.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Remit Rs.2500/- (Rupees Two Thousand Five Hundred Only) in favour of WIDESKY towards &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;the transfer processing fee.</p>
<p>3.14&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SEPARATION OF A DISTRIBUTOR(S)SHIP If Independent Distributor(s) wish to dissolve their jointly held Distributor(s)ship, they must do so in such a way as to not disturb the income or interests of their upline and downline Organizations. Independent Distributor(s) should consider the following when deciding whether or not to dissolve a jointly held Distributor(s)ship:</p>
<p>Version 1.1</p>
<p>10</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<ol>
<li>If a jointly owned Distributor(s)ship is dissolved, the primary applicant/member of the joint&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; owners may continue to operate the Distributor(s)ship, but the other joint owner(s) must&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; relinquish his/their rights to, and interests in, the Distributor(s)ship.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp;&nbsp; WIDESKY cannot divide a Downline Organization, nor can it divide the payout cheques&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; between the joint owners unless otherwise agreed to in writing by WIDESKY.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c.&nbsp;&nbsp;&nbsp; If a jointly owned Distributor(s)ship is dissolved, the individual(s) who relinquished ownership&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; in the original Distributor(s)ship may apply as new Independent Distributor(s) under any&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sponsor but may not purchase or join an existing Distributor(s)ship.&nbsp;</li>
</ol>
<p>3.15&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SUCCESSION If an Independent Distributor(s) dies or becomes incapacitated, his/her rights to payouts and Downline Organization, together with all Independent Distributor(s) responsibilities, will pass to his/her successor(s) as stated in the Distributor(s) will or as otherwise ordered by a court of competent jurisdiction. Upon death or incapacitation, the successor(s) must present the WIDESKY Compliance Department with proof of death or incapacitation, along with proof of succession [including but not limited to a court order, copy of the will and/or letters of administration in the event there is no will] and a properly completed Independent Distributor(s) Application and Agreement. You may inherit and retain another Distributor(s)ship even though you currently own or operate a Distributor(s)ship.</p>
<p>3.16&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAXES</p>
<p>3.16.1&nbsp;&nbsp;&nbsp; INCOME TAXES Tax return ﬁling and reporting is the responsibility of the individual Independent Distributor. Any individual operating a business is required to obtain a Permanent Account Number from the income Tax Department when their earnings become taxable under the Indian income Tax Act. Please consult your tax advisor for rules and details as Tax laws will change from time to time. It is mandatory for all Independent Distributor(s) to provide their PAN number details. Currently, TDS deduction of 5% is applicable for those who provide PAN details and 20% deduction for those who do not provide the PAN number details as per the Income Tax Act.</p>
<p>3.16.2&nbsp;&nbsp;&nbsp; TAXES WIDESKY will collect and remit sales taxes on behalf of Independent Distributor(s) at the maximum retail price according to applicable tax rates to which the shipment is destined. In the event an Independent Distributor(s) has submitted, and WIDESKY has accepted a Sales Tax Registration Certiﬁcate Agreement with a photocopy of the Independent Distributor(s) valid state resale registration certiﬁcate, sales taxes will not be added to the invoice and the responsibility of collecting and remitting sales taxes to the appropriate authorities will be upon the Independent Distributor(s). Exemption from the payment of sales tax is applicable only to orders which are shipped to a jurisdiction for which the proper tax registration papers have been ﬁled and accepted. Sales taxes will be charged on orders that are shipped to another jurisdiction, based on the sales tax laws of the destination. Any sales tax exemption accepted by WIDESKY is not retrospective.</p>
<p>3.17&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TELEPHONE AND E-MAIL SOLICITATION The use of any automated telephone solicitation equipment in connection with the marketing or promotion of WIDESKY, its products, or the WIDESKY opportunity is strictly prohibited. Independent Distributor(s) are also forbidden from sending unsolicited e-mail messages or &ldquo;spamming&rdquo; to sell products or to recruit Independent Distributor(s).</p>
<p>3.18&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TERRITORIES There are no exclusive territories for marketing WIDESKY products or services, nor shall any Independent Distributor(s) imply or state that he/she has an exclusive territory to market WIDESKY products or services.</p>
<p>Version 1.1</p>
<p>11</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>3.19&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TRADE SHOWS AND EXPOSITIONS Independent Distributor(s) may display and/or sell WIDESKY products at trade shows and expositions subject to obtaining prior written approval from WIDESKY, but Independent Distributor(s) shall not display or sell WIDESKY products at swap meets, garage sales, flea markets, or farmers' markets as these events are not conducive to the image WIDESKY wishes to portray. All literature displayed at the event must be Ofﬁcial WIDESKY Material and must clearly identify the individual(s) as Independent Distributor(s).</p>
<p>3.20&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TRANSFER OF SPONSORSHIP</p>
<p>3.20.1&nbsp;&nbsp;&nbsp; CONFLICTING ENROLLMENTS Every prospective Independent Distributor(s) has the ultimate right to choose his/her own Sponsor. As a general rule, the ﬁrst Independent Distributor(s) who does meaningful work with a prospective Independent Distributor(s) is considered to have ﬁrst claim to sponsorship. Basic tenets of common sense and consideration should govern any dispute that may arise. In the event that a prospective Independent Distributor(s) or any Independent Distributor(s) on behalf of a prospective independent distributor(s), submits more than one Independent Distributor(s) Application and Agreement to WIDESKY, listing a different Sponsor on each, WIDESKY will only consider valid the ﬁrst Independent Distributor(s) Application and Agreement that it receives, accepts, and processes. If there is any question concerning the sponsorship of an Independent Distributor(s), the ﬁnal decision will be made by WIDESKY.</p>
<p>3.20.2&nbsp;&nbsp;&nbsp; CROSS-LINE RAIDING WIDESKY will not permit any change in the line of sponsorship except in the following circumstances:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a.&nbsp;&nbsp;&nbsp; Where an Independent Distributor(s) has been fraudulently or unethically induced into joining&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WIDESKY.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b.&nbsp;&nbsp;&nbsp; Where an incorrect placement was made due to an Independent Distributor(s) error, a change&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; in the line of sponsorship cannot be made&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c.&nbsp;&nbsp;&nbsp; If you terminate your Distributor(s)ship in writing you may rejoin under the Sponsor of your&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; choice after a period of six (6) months. Following termination of your Distributor(s)ship, you&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; may participate as a retail Customer during the six (6) month period. In the event you terminate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; your Distributor(s)ship, you forfeit all rights, payouts and commissions under your previous&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; line of sponsoring. You may not avoid compliance with this policy through the use of assumed&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; names, corporations, partnerships, trusts, spouse names, ﬁctitious ID numbers, etc.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d.&nbsp;&nbsp;&nbsp; Cross-line raiding is strictly prohibited. &ldquo;Cross-line raiding&rdquo; is deﬁned as the enrolment or&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; attempted enrolment of an individual or Entity that already has an Independent Distributor(s)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Agreement on ﬁle with WIDESKY, or who has had such an agreement within the preceding&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; six (6) calendar months within a different line of sponsorship. The use of trade names,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; corporations, partnerships, trusts, spouse names, or ﬁctitious ID numbers to circumvent this&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; policy is prohibited. Independent Distributor(s) may not demean, discredit, or invalidate other&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WIDESKY Independent Distributor(s) in an attempt to entice another Independent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s) to become part of the ﬁrst Independent Distributor(s) Downline Organization.</p>
<p>SECTION 4 - RESPONSIBILITIES OF INDEPENDENT DISTRIBUTOR(S) AND SPONSORS</p>
<p>4.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ONGOING SUPERVISION, TRAINING, AND SALES Any Independent Distributor(s) who sponsors another Independent Distributor(s) into WIDESKY must train the new Independent Distributor(s) in product knowledge, effective sales techniques, the Business Plan, and the Policies andProcedures. Independent Distributor(s) must also supervise and monitor Independent Distributor(s) in their Downline Organization to ensure they conduct business professionally and ethically, promote sales properly, and provide quality customer service. As an Independent Distributor(s) progresses through the various Levels of leadership, his/her responsibilities to train and motivate downline Independent Distributor(s) will increase.&nbsp;</p>
<p>Version 1.1</p>
<p>12</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>4.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NON-DISPARAGEMENT In setting the proper example for their downline, Independent Distributor(s) must not disparage other WIDESKY Independent Distributor(s), WIDESKY&rsquo;s Products, the Business Plan, or WIDESKY's employees, personnel or agents. Such disparagement constitutes a material breach of these Policies and Procedures.</p>
<p>4.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HOLDING APPLICATIONS OR ORDERS All Independent Distributor(s) must forward to WIDESKY any forms and applications they receive from other Independent Distributor(s) or applicant Independent Distributor(s), on the same or the next business day following the date on which the forms or applications are signed.</p>
<p>4.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; REPORTING POLICY VIOLATIONS Independent Distributor(s) should report any observed violations of a policy violation to the WIDESKY Compliance Department.</p>
<p>4.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rank holders from Gold &amp; above upto CCM or their spouse/s may not represent any other direct selling company without WIDESKY&rsquo;s written consent. Violation of this clause will result into a disciplinary action as per clause 7.2g.</p>
<p>SECTION 5 - SALES REQUIREMENTS</p>
<p>5.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; RETAIL CUSTOMER SALES Independent Distributor(s) shall sell WIDESKY Products at the maximum retail price (MRP) mentioned on the product label.</p>
<p>5.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EXCESSIVE PURCHASES OF INVENTORY PROHIBITED WIDESKY strictly prohibits the purchase of products in unreasonable amounts solely for the purpose of qualifying for commissions, bonuses, or advancement in the Business Plan. Independent Distributor(s)may not purchase more than they can reasonably resell or consume in any four-week rolling period, nor encourage others to do so. Each Independent Distributor(s) must make his/her own decision with regard to these matters.</p>
<p>5.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DEPOSITS No monies should be paid to or accepted by Independent Distributor(s) for a sale except at the time of product delivery.</p>
<p>SECTION 6 - BONUS AND COMMISSIONS</p>
<p>6.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; BONUS AND COMMISSION CYCLES An Independent Distributor(s) must review his/her commissions and report any errors or discrepancies to WIDESKY within Fifteen (15) days from the date of the commission cheque. Errors or discrepancies which are not brought to WIDESKY's attention within the said ﬁfteen (15) days period will be deemed waived or accepted by the Independent Distributor(s).</p>
<p>6.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; LOSS OF RIGHTS TO COMMISSIONS You must be an Active Independent Distributor(s) and in compliance with the terms of the Independent Distributor(s) Agreement, the Business Plan and these Policy and Procedures to qualify for commissions and payouts. NO amount shall stand to be payable under the circumstances of Termination or Resignation from the Distributorship and ALL beneﬁts accrued or otherwise shall seize with immediate effect without any previous consideration, if any.</p>
<p>6.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; UNCLAIMED COMMISSIONS AND CREDITS: Independent Distributor(s) must deposit commission and payout cheques within three (3) months of the date of the cheque. A cheque that remains uncashed after six months will be void.&nbsp;</p>
<p>Version 1.1</p>
<p>13</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>6.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; INDEX SAFETY FEATURE: Independent Index Safety Feature (trimming) will be applicable when required by the company.</p>
<p>SECTION 7- DISPUTE RESOLUTION AND DISCIPLINARY PROCEEDINGS</p>
<p>7.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DISPUTES BETWEEN INDEPENDENT DISTRIBUTOR(S)</p>
<p>7.1.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; GRIEVANCES AND COMPLAINTS When an Independent Distributor(s) has a grievance or complaint with another Independent Distributor(s) regarding any practice or conduct in relationship to their respective WIDESKY businesses, the complaining Independent Distributor(s) should ﬁrst discuss the problem with the other Independent Distributor(s). If this does not resolve the problem, the complaining Independent Distributor(s) should report the problem to his/her upline Diamond Director to resolve the issue at a local level. If the matter cannot be resolved within thirty (30) days from the date on which it was reported to the upline Diamond Director, it must be reported in writing to the WIDESKY Compliance Department. The Compliance Department will review the complaint and make a ﬁnal decision. The complaint should identify speciﬁc instances of alleged improper conduct and, to the extent possible, identify the relevant dates on which the event(s) complained of took place, the location(s) where they occurred, and all persons who have ﬁrst-hand knowledge of the improper conduct.</p>
<p>7.1.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; COMPLIANCE DEPARTMENT REVIEW Upon receipt of a written complaint, the WIDESKY Compliance Department will investigate the matter, review the applicable policies, and render a decision on how the dispute shall be resolved. The Compliance Department may also issue disciplinary sanctions consistent with the provisions of Section 7.3. Subject to applicable law, WIDESKY Compliance Department&rsquo;s decision shall be ﬁnal and binding on the Independent Distributors to the dispute.</p>
<p>7.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DISCIPLINARY ACTIONS Violation of any of the terms and conditions of the Independent Distributor(s) Agreement or these Policies and Procedures, or any illegal, fraudulent, deceptive, or unethical business conduct by an Independent Distributor(s), may result, at WIDESKY's discretion, in one or more of the following sanctions:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A written warning, clarifying the meaning and application of a speciﬁc policy or procedure,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and advising that a continued breach will result in further sanctions;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;b.&nbsp;&nbsp;&nbsp;&nbsp; Probation, which may include requiring an Independent Distributor(s) to take remedial action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and will include follow-up monitoring by WIDESKY to ensure compliance with the Agreement;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c.&nbsp;&nbsp;&nbsp;&nbsp; Withdrawal or denial of an award or recognition, or restricting participation in WIDESKY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; sponsored events for a speciﬁed period of time or until the Independent Distributor(s) satisﬁes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; certain speciﬁed conditions;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d.&nbsp;&nbsp;&nbsp;&nbsp; Suspension of certain privileges of Distributor(s)ship, including but not limited to placing a&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; product order, participating in WIDESKY programs, progressing in the Business Plan, or&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; participating as a Sponsor, for a speciﬁed period of time or until the Independent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s) satisﬁes certain speciﬁed conditions or any other right or privilege;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Withholding commissions or payouts for a speciﬁed period of time or until the Independent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distributor(s) satisﬁes certain speciﬁed conditions;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; f.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Imposing fair and reasonable ﬁnes or other penalties in proportion to actual damages&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; incurred by WIDESKY and as permitted by law; and/or&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; g.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Terminating the Distributor(s)ship by terminating the Independent Distributor Agreement.</p>
<p>SECTION 8 - ORDER PROCESSING</p>
<p>8.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ORDERING METHODS Independent Distributor(s) may place phone orders, fax orders, e-mail, walk into the nearest WIDESKY distribution.</p>
<p>Version 1.1</p>
<p>14</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>8.1.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Walk-ins: When ordering in person at the WIDESKY distribution centre, complete the order form and hand over the same along with the payment to the Distributor(s) services ofﬁcer at the counter. Payments can be made by cash, credit card, debit card, bank draft or electronic fund transfer to WIDESKY&rsquo;s bank account mentioned in Section 9.1.3 of these Policies and Procedures</p>
<p>8.1.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Phone: When ordering by phone - be prepared to present all information requested on the Independent Distributor(s) Product Order Form along with the Bank in of orders amount details. For Phone orders contact +91 7452000510. Payments must be made by Bank draft, credit card, or electronic fund transfer to WIDESKY&rsquo;s bank account mentioned in Section 9.1.3 of these Policies and Procedures.</p>
<p>8.1.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fax: When ordering by fax - print information legibly on the order form and use the white copy to fax along with the payment details to +91 7452000510. Payments may be made by Bank Draft, credit card, or Electronic Fund transfer to WIDESKY&rsquo;s bank account mentioned in Section 9.1.3 of these Policies and Procedures.</p>
<p>8.1.4&nbsp;&nbsp;&nbsp;&nbsp; E-mail: When ordering by email &mdash; Send Completed order with the Payment to order@widesky.online. Payments may be made by Bank Draft, credit card, or Electronic Fund transfer to WIDESKY&rsquo;s bank account mentioned in Section 9.1.3 of these Policies and Procedures.</p>
<p>8.1.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mail: When ordering by mail&mdash;send completed white order form with the payment to M/s. WIDESKY E-RETAILS PVT LTD, &nbsp;rajwaha road , Agarsain Vihar , Muzaffarnagar up-251001. Keep a copy of the order form for your records. Payment may be made by credit card, bank draft, Electronic fund transfer to WIDESKY&rsquo;s bank account mentioned in Section 9.1.3 of these Policies and Procedures.</p>
<p>8.1.6&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; From Stockist: Orders can also be placed from our authorized Stockist. Please call our Customer support for Stockist Locations.</p>
<p>8.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Banking info for Orders:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bank&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; bandhan Bank ltd.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Account Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; WIDESKY E-RETAILS&nbsp; PVT. LTD.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Branch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; Muzaffarnagar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Account Number&nbsp;&nbsp; :&nbsp; 10180006535572&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IFS Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; <strong>&nbsp;</strong>BDBL0001888</p>
<p>8.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; GENERAL ORDER POLICIES Mail orders received with invalid or incorrect payment, WIDESKY will attempt to contact the Independent Distributor(s) by telephone and/or e-mail, at the telephone number and e-mail ID mentioned in the Independent Distributor&rsquo;s Application, to try to obtain payment. If these attempts are unsuccessful after ﬁve working days, the order will be returned unprocessed. No C.O.D.(Cash on delivery) orders will &nbsp;be accepted. Orders for products and sales aids (product guides) may be combined.</p>
<p>8.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PURCHASING WIDESKY PRODUCTS Each Independent Distributor(s) must purchase his/her products directly from WIDESKY in order to receive the Sales Volume credits. 8.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; BACK ORDER POLICY As a general rule, WIDESKY will not book an order for out-of-stock items. 8.6&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SHIPPING DISCREPANCIES The shipments are in perfect condition when the carrier takes possession of the same. By signing &ldquo;received&rdquo; on the delivery note, the recipient(s) acknowledges that the order was received in satisfactory</p>
<p>Version 1.1</p>
<p>15</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>condition. Do not sign in the event of damages or product shortages. Hidden damages discovered after the carrier has left and all other discrepancies must be notiﬁed within twenty-four (24) hours of receipt of shipment. Failure to notify WIDESKY of any shipping discrepancy or damage within twenty-four (24) hours of receipt of the shipment will cancel an Independent Distributor(s) right to request a correction and shall be considered deemed acceptance of the products.</p>
<p>SECTION 9 - PAYMENT AND SHIPPING</p>
<p>9.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; METHODS OF PAYMENT All forms and authorizations must be accompanied by the Independent Distributor(s) signature.</p>
<p>9.1.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; BANK DRAFT Make payable to M/s. WIDESKY E-RETAILS PVT LTD, Muzaffarnagar &nbsp;for the full amount of your order, including applicable sales tax and shipping and handling charges. 9.1.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CREDIT CARDS WIDESKY accepts VISA, MasterCard, American Express. In the event that the charge is declined, the order will not be accepted. Using someone else's credit card without their express, written permission is prohibited and may be grounds for involuntarily cancellation of a Distributor(s)ship and termination of the Independent Distributor(s) Agreement. 9.1.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ELECTRONIC FUND TRANSFER The Independent Distributor(s) may deposit the order amount, including taxes and shipping into WIDESKY&rsquo;s bank account indicated below &amp; then fax the deposit copy to WIDESKY along with the order number [if provided] for further processing of the order. Bank name : Axis Bank Ltd</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Account name : WIDESKY E-RETAILS PVT LTD.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Branch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : KORAMANGALA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Account number : 10180006535572&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IFS Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <strong>&nbsp;</strong>BDBL0001888</p>
<p>SECTION 10 - DISTRIBUTOR(S) SERVICES</p>
<p>10.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CHANGES TO THE DISTRIBUTOR(S)SHIP 10.1.1&nbsp;&nbsp;&nbsp; IN GENERAL Each Independent Distributor(s) must immediately notify WIDESKY of all changes to the information contained on the Independent Distributor(s) Application and Agreement by submitting a written request, a properly executed Independent Distributor(s) Application and Agreement, and appropriate supporting documentation 10.1.2&nbsp;&nbsp;&nbsp; ADDITION OF CO - APPLICANTS When adding a co-applicant to an existing Distributor(s)ship, WIDESKY requires both a written request and a properly completed Independent Distributor(s) Application and Agreement containing the applicant's and co-applicant's proof of residency documents and signatures. The modiﬁcations permitted within the scope of this paragraph do not include a change of sponsorship. Addition of a co-applicant is subject to WIDESKY&rsquo;s approval and discretion.</p>
<p>10.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; COMMISSION STATEMENTS Commission Statements are printed for all active Independent Distributor(s) receiving a commission cheque and are mailed with the commission cheques.</p>
<p>10.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ERRORS OR QUESTIONS In the event an Independent Distributor(s) has questions about or believes that any errors have been made regarding commissions, bonuses or orders. The Independent Distributor(s) must notify WIDESKY within Fifteen (15) days of the date of the purported error or incident in question. WIDESKY will not be responsible for any error, omission, or problem not reported within the said ﬁfteen (15) days period.</p>
<p>Version 1.1</p>
<p>16</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>10.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; RESOLVING PROBLEMS If you have any questions regarding shipments, orders, commissions and bonuses, or the Business Plan, please call our Customer Support at +91 080 403 81881 or email at customersupport@WIDESKY .com.</p>
<p>SECTION 11 - CANCELLATION POLICIES</p>
<p>11.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; INVOLUNTARY CANCELLATION An Independent Distributor(s) violation of any of the terms of the Independent Distributor(s) Agreement, including any amendments which may be made by WIDESKY in its sole discretion from time to time, constitutes a material breach of the Independent Distributor(s) Agreement and may result, at WIDESKY's option, in any of the Disciplinary Actions listed in Section 7.3, including cancellation of his/her Distributor(s)ship and termination of the Independent Distributor(s) Agreement. Involuntary Cancellation of a Distributor(s)ship will result in the Independent Distributor(s) loss of all rights to his/her Downline Organization and any bonuses and commissions generated thereby. An Independent Distributor(s) whose Independent Distributor(s) Agreement is involuntarily cancelled shall receive commissions and bonus only for the last full calendar week prior to termination, subject to payment of any dues payable to WIDESKY. When a Distributor(s)ship is involuntarily cancelled, the Independent Distributor(s) will be notiﬁed by registered mail at the address on ﬁle with WIDESKY or by e-mail to the e-mail ID mentioned on the Independent Distributor&rsquo;s Application. Cancellation shall be effected in writing and may be served:</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (a)&nbsp;&nbsp; personally;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (b)&nbsp;&nbsp; By registered post acknowledgment due or courier;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (c)&nbsp;&nbsp; By facsimile transmission; or&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (d)&nbsp;&nbsp; By email. Cancellation shall be deemed to have been effective:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (a)&nbsp;&nbsp;&nbsp;&nbsp; if it was served in person, at the time of service;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (b) if it was served by registered post or courier, upon receipt, as reflected by the conﬁrmation&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of delivery receipt provided by the delivery receipt provider;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (c) if it was served by facsimile transmission, on receipt of conﬁrmation of successful transmission; and&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (d) if sent by email, twenty (24) hours after the email is sent. In the event of such Involuntary Cancellation, the Independent Distributor(s) must immediately cease to represent himself/herself as a WIDESKY Independent Distributor(s). The Independent Distributor(s) may appeal the termination to the WIDESKY Compliance Department. The Independent Distributor(s) appeal must be in writing and must be received by WIDESKY within ﬁfteen (15) calendar days of the date of WIDESKY's cancellation letter. Subject to applicable law, if WIDESKY does not receive the appeal within the ﬁfteen-day period, the cancellation will be ﬁnal. The Independent Distributor(s) must submit all supporting documentation with his/her appeal correspondence. The written appeal will be reviewed by the Compliance Department. If the Independent Distributor(s) ﬁles a timely appeal of termination, the Compliance Department will review and reconsider the termination, consider any other appropriate action, and notify the Independent Distributor(s) in writing of its decision. Subject to applicable law, this decision of the Compliance Department will be ﬁnal. An Independent Distributor(s) whose Independent Distributor(s) Agreement is involuntarily canceled may reapply to become an Independent Distributor(s) six (6) calendar months from the date of cancellation. Any such Independent Distributor(s) wishing to re-apply must submit a letter to the WIDESKY Compliance Department setting forth the reasons why he/she believes WIDESKY should allow him or her to operate a Distributor(s)ship. It is within WIDESKY's sole discretion whether to permit such an individual to again operate a WIDESKY business.</p>
<p>11.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WRITTEN CANCELLATION An Independent Distributor(s) may cancel his/her Agreement with WIDESKY at any time and for any reason by providing written notice to WIDESKY indicating his/her intent to discontinue his/her Distributor(s)ship. The written notice must include the Independent Distributor(s)signature, printed name, address, appropriate identiﬁcation number &amp; No Objection Certiﬁcate (NOC) from the sponsor.</p>
<p>Version 1.1</p>
<p>17</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>11.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EFFECT OF CANCELLATION Following an Independent Distributor(s) voluntary or involuntary cancellation, such former Independent Distributor(s) shall have no right, title, claim, or interest to the Downline Organization which he/she operated or any bonus and/or commission from the sales generated by the organization. Following an Independent Distributor(s) voluntary or involuntary cancellation, the former Independent Distributor(s) shall not hold himself or herself out as a WIDESKY Independent Distributor(s), must remove any WIDESKY sign from public view, and must discontinue using any other materials bearing any WIDESKY logo, trademark, or service mark. An Independent Distributor(s) who is voluntarily cancelled will receive commissions and bonuses only for the last full calendar week prior to his/her cancellation. An Independent Distributor(s) whose Independent Distributor(s) Agreement is involuntarily cancelled will receive commissions and bonuses only for the last full calendar week prior to cancellation, unless monies were withheld by the Company during an investigation period. If an investigation of the Independent Distributor(s) conduct results in his/her involuntary cancellation, he/she shall not be entitled to recover withheld commissions and bonuses. Upon request, an Independent Distributor(s) who voluntarily cancels his/her Independent Distributor(s) Agreement may become a retail Customer.</p>
<p>SECTION 12 - CLOSING DATES</p>
<p>12.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; BINARY &nbsp;BONUS PLAN :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) After login, Placements cannot be replaced or changed or cancelled or inactivated. It's the&nbsp;&nbsp; responsibility of the Distributor(s) to clearly place the downlines after thoroughly going&nbsp;&nbsp; through the marketing plan.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b)&nbsp;&nbsp;&nbsp;&nbsp; On every Fifteen days bonus payout, 10% will be deducted towards the generation plan which&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; will be automatically uploaded into the system on every month end closing.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c)&nbsp;&nbsp;&nbsp;&nbsp; fifteen days &nbsp;Closing dates are as follows:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<p>12.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; GENERATION REPURCHASE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; a) Independent Distributor(s) has to maintain a minimum of 500 BV to get the generation plan income.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b) Generation plan closing date will be on the last date of every calendar month.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c) Generation Payouts will be released on 10th of the next calendar month.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d) Business Volume (BV) cannot be transferred from one Independent Distributor(s) to another.</p>
<p>SECTION 13 - REWARDS &amp; RECOGNITIONS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. All rewards &amp; promotions should be achieved as per the WIDESKY's Business plan.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Rewards, Gifts, Royalty, Business Development Fund, Car Fund &amp; House Fund will be released as per&nbsp;&nbsp; the discretion of WIDESKY .&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3. Rewards, Gifts, Royalty, Business Development fund, Car Fund &amp; House Fund cannot be transferred.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 4. Business Development Fund &amp; Royalty's can be used only for trips ﬁxed by WIDESKY.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 5. Business Development Fund &amp; Royalty's cannot be carried forwards for the next year.&nbsp; Independent Distributor(s) has to ﬁrst qualify for the Car fund &amp; then qualify for the House fund.</p>
<p>SECTION 14 - GENERAL INFORMATION Business Hours: Monday &ndash; Friday: 10.00 am - 6.00 pm &amp; Saturday: OFF&nbsp; Sunday : 10:00 am to 6:00 pm Week as per the calendar Month</p>
<p>&nbsp;</p>
<p>Bonus Processing Date</p>
<p>1-15&nbsp; on 17<sup>th</sup> date</p>
<p>16- month last on 2<sup>nd</sup> date</p>
<p>&nbsp;</p>
<p>Version 1.1</p>
<p>18</p>
<p>Copyright &copy; 2019. WIDESKY. All rights reserved.</p>
<p>Downline Organization Report - A report generated by WIDESKY that provides critical data relating to the identities of Independent Distributor(s)and sales information of each Independent Distributor(s)Downline Organization. This report contains proprietary trade secret information. (See Section 3.6.2). Independent Distributor(s) - An individual who has executed an Independent Distributor(s) Application and Agreement which has been accepted by WIDESKY. Independent Distributor(s) are required to meet certain qualiﬁcations and are responsible for the training, motivation, support, and development of the Independent Distributor(s) in their respective Downline Organizations. Independent Distributor(s) are entitled to purchase WIDESKY products at wholesale prices, enroll new Independent Distributor(s), and take part in all Company Independent Distributor(s) programs.Independent Distributor(s) Agreement - The term Independent Distributor(s) Agreement, as used in the Policies and Procedures, refers to the Independent Distributor(s) Application and Agreement, WIDESKY's Policies and Procedures, and the Business Plan. Involuntary Cancellation - The termination of an Independent Distributor(s) Agreement which is initiated by WIDESKY. Level - The layers of downline Independent Distributor(s) in a particular Independent Distributor(s) Downline Organisation.This term&nbsp; refers&nbsp; to the relationship of an Independent Distributor(s) relative to a particular upline Independent Distributor(s),determined by the number of Independent Distributor(s ) between them who are related by sponsorship.Ofﬁcial WIDESKY Material - Literature, audio or video tapes, and other materials developed, printed, published, or distributed by WIDESKY. PAN Number - Permanent Account Number (PAN) is a ten-digit alphanumeric number, issued in the form of a laminated card, by the Ofﬁcial WIDESKY Material - Literature, audio or video tapes, and other materials developed, printed, published, or distributed by WIDESKY. PAN Number - Permanent Account Number (PAN) is a ten-digit alphanumeric number, issued in the form of a laminated card, by the Income Tax Department, for ﬁling the taxes. Sales Volume (SV) - The commissionable value of products purchased by an Independent Distributor(s).Sponsor - A Independent Distributor(s) who brings another individual into WIDESKY as a Independent Distributor(s). TDS - Tax Deducted at Source will be applicable on Commissions earned as per the Income Tax rules. Upline Organisation - Your Sponsor &amp; their Sponsor &amp; so on.Voluntary Cancellation - The termination of an Independent Distributor(s) Agreement instituted by the Independent Distributor(s) who elects to discontinue his/her afﬁliation with WIDESKY for any reason.</p>
<p>SECTION 15 &ndash; SOCIAL MEDIA POLICY &lsquo;Social media&rsquo; is a term that describes websites &amp; online tools that people use, to connect &amp; interact with other people, share content, proﬁle, experiences and opinions. Online tools usually are blogs, message boards, photo sharing sites etc. While WIDESKY encourages its distributors to engage themselves to use social media, the distributors must ensure that they do not violate any clauses in the rules, policies &amp; procedures, guidelines manual.</p>
<p>15.1&nbsp;&nbsp; Do&rsquo; s FOR DISTRIBUTOR a) Create your social media proﬁle. Connect with family, friends. b) Communicate with your upline / downline. c) Be authentic &amp; honest d) Share good experiences with WIDESKY products. e) Connect with WIDESKY ofﬁcial website &amp; share photos, videos posted by WIDESKY. f) Follow rules &amp; regulations laid down by WIDESKY at all times.</p>
<p>15.2&nbsp;&nbsp; Don&rsquo;t s FOR DISTRIBUTOR&nbsp; a) Do not create websites. Pages for selling WIDESKY products b) Do not offer promotions that are not ofﬁcially offered by WIDESKY&nbsp; c) Do not use logos, trademarks. Images without prior permission d) Do not upload contents that are not authorized by WIDESKY. e) Do not make claims on WIDESKY products, that are misleading. f) Do not post anything, that violates the policy guidelines. Ask. g) Do not post any material that will spoil your &amp; WIDESKY&rsquo;s reputation.</p>
<p>SECTION 16 - DEFINITION OF TERMS</p>
<p>Active Independent Distributor(s) - An Independent Distributor(s) who satisﬁes the minimum Personal Sales Volume requirements as set forth in the WIDESKY Business Plan. Cancellation - Termination of an individual's Independent Distributor(s) Agreement. Cancellation may be either voluntary or involuntary. Company - The term &ldquo;Company&rdquo; as it is used throughout these Policies and Procedures, and in all WIDESKY Material, means WIDESKY E-RETAILS&nbsp; PVT. LTD. Downline Organization - An Independent Distributor(s) Downline Organization consists of all Independent Distributor</p>
								                        </div>
								                        <div class="modal-footer">
								                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
								                        </div>
								                    </div>
								                </div>
								            </div>
			                            </div>
			                            <div class="form-group">
			                        		<input type="submit" name="join_user" class="btn btn-primary btn-block" value="Join">
			                        	</div>
			                        </div>
			                    </div>
	                        </form>
            			</div>
            		</div>
            	</div>
            </div>
        </div>
    </section>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>

    <!-- Demo Js -->
    <script src="js/demo.js"></script>
</body>

</html>
