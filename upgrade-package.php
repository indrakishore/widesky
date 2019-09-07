<?php
    ini_set('max_execution_time', 60);
    include('php-includes/check-login.php');
    require('php-includes/connect.php');
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
    if (isset($_GET['update'])) {
        $pin = mysqli_real_escape_string($con,$_GET['pin']);
        $pack = mysqli_real_escape_string($con,$_GET['pack']);
        $c_pack = mysqli_real_escape_string($con,$_GET['c_pack']);
        $date = date("Y-m-d");
        $flag = 0;

        if ($pin!='' && $pack!='' && $c_pack!='') {
            if (pin_check($pin)) {
                if ($c_pack!='free' && $pack!='free' && $pack>$c_pack) {
                    $flag = 1;
                }
                else if($c_pack=='free'){
                    $flag = 1;
                }
                else{
                     echo '<script>alert("Can not upgrade to this pack.");</script>';
                }
                
            }
            else{
                 echo '<script>alert("Unexpected error occured. Please check your pin.");</script>';
            }
        }
        else{
            echo '<script>alert("Unexpected Error.");</script>';
        }

        
        if ($flag==1) {
            $query = mysqli_query($con,"update pin_list set status='close' where pin='$pin' ");
            $query = mysqli_query($con,"update user set pack='$pack',upgrade_date='$date',countdownWPCB=15 where email='$userid' ");
            $query = mysqli_query($con,"update tree set pack='$pack' where userid='$userid'");

            $q = mysqli_query($con, "select * from user where email='$userid'");
            $result = mysqli_fetch_array($q);
            $under_userid = $result['under_userid'];
            $side = $result['side'];
            $sponsorid = $result['sponsorid'];

            //Rewards unlocking
            if ($c_pack == 'free') {
                if ($pack == 999) {
                    mysqli_query($con,"update tree set rwd1='open' where userid='$userid'");
                }
                if ($pack == 4999) {
                    mysqli_query($con,"update tree set rwd1='open',rwd2='open',rwd3='open' where userid='$userid'");
                }
                if ($pack == 9999 || $pack==10000) {
                    mysqli_query($con,"update tree set rwd1='open',rwd2='open',rwd3='open',rwd4='open',rwd5='open',rwd6='open' where userid='$userid'");
                }
                if ($pack == 24999 || $pack==25000) {
                    mysqli_query($con,"update tree set rwd1='open',rwd2='open',rwd3='open',rwd4='open',rwd5='open',rwd6='open',rwd7='open' where userid='$userid'");
                }
                if ($pack >=39999) {
                    mysqli_query($con,"update tree set rwd1='open',rwd2='open',rwd3='open',rwd4='open',rwd5='open',rwd6='open',rwd7='open',rwd8='open',rwd9='open' where userid='$userid'");
                }
            }
            elseif ($c_pack==999 || $c_pack==1000) {
                if ($pack == 4999) {
                    mysqli_query($con,"update tree set rwd2='open',rwd3='open' where userid='$userid'");
                }
                if ($pack == 9999 || $pack==10000) {
                    mysqli_query($con,"update tree set rwd2='open',rwd3='open',rwd4='open',rwd5='open',rwd6='open' where userid='$userid'");
                }
                if ($pack == 24999 || $pack==25000) {
                    mysqli_query($con,"update tree set rwd2='open',rwd3='open',rwd4='open',rwd5='open',rwd6='open',rwd7='open' where userid='$userid'");
                }
                if ($pack >=39999) {
                    mysqli_query($con,"update tree set rwd2='open',rwd3='open',rwd4='open',rwd5='open',rwd6='open',rwd7='open',rwd8='open',rwd9='open' where userid='$userid'");
                }
            }
            elseif ($c_pack==4999 || $c_pack==5000) {
                if ($pack == 9999 || $pack==10000) {
                    mysqli_query($con,"update tree set rwd4='open',rwd5='open',rwd6='open' where userid='$userid'");
                }
                if ($pack == 24999 || $pack==25000) {
                    mysqli_query($con,"update tree set rwd4='open',rwd5='open',rwd6='open',rwd7='open' where userid='$userid'");
                }
                if ($pack >=39999) {
                    mysqli_query($con,"update tree set rwd4='open',rwd5='open',rwd6='open',rwd7='open',rwd8='open',rwd9='open' where userid='$userid'");
                }
            }
            elseif ($c_pack==9999 || $c_pack==10000) {
                if ($pack == 24999 || $pack==25000) {
                    mysqli_query($con,"update tree set rwd7='open' where userid='$userid'");
                }
                if ($pack >=39999) {
                    mysqli_query($con,"update tree set rwd7='open',rwd8='open',rwd9='open' where userid='$userid'");
                }
            }
            else{
                if ($pack >=39999) {
                    mysqli_query($con,"update tree set rwd8='open',rwd9='open' where userid='$userid'");
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

            mysqli_query($con,"update tree set rewardsach='$ra',timeupgrade='$timeupgrade' where userid = '$userid'");

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
                $mail->addAddress($userid);
                $mail->Subject = 'Pack Upgraded | Members Area | WIDESKY E-RETAILS PVT. LTD.';
                $mail->isHTML(TRUE);$mail->isSMTP();
                $mail->Host = "mail.widesky.online";
                // optional
                // used only when SMTP requires authentication  
                $mail->SMTPAuth = true;
                $mail->Username = 'admin@widesky.online';
                $mail->Password = 'wideskyadmin@007#';
                $mail->Body = "<h1>Members Area | WIDESKY E-RETAILS PVT. LTD.</h1>
                <h2>Pack Upgraded</h2>
                Your Pack is successfully upgraded. "."<br><br>
                <table rules='all' style='border-color: #666;'' cellpadding='10'>
                <tr style='background: #eee;'><td><strong>Email/UserID:</strong> </td><td>" . $userid . "</td></tr>
                <tr><td><strong>Old Pack:</strong> </td><td>" . $c_pack . "</td></tr>
                <tr><td><strong>New Pack:</strong> </td><td>" . $pack . "</td></tr>
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

            echo mysqli_error($con);
            echo '<script>alert("Successfully Updated.");window.location.assign("user-profile.php");</script>';
        }
    }

    
?>
<?php
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
    <title>Upgrade Package | WIDESKY E-RETAILS PVT. LTD.</title>
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
                <h1>UPGRADE PACKAGE</h1>
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <?php
                        $query = mysqli_query($con,"select * from tree where userid='$userid'");
                        $result = mysqli_fetch_array($query);
                        $timeup = $result['timeupgrade'];
                        if ($timeup == 0) {
                            echo   '<div class="row">
                                <div class="alert alert-danger">
                                    <strong>Error!</strong> You cannot upgrade upgrade your package as your time limit to upgrade package has been over.<br>
                                    <strong>Contact support for further details.</strong>
                                </div>
                            </div>';
                        }
                        else{
                            ?>
                    <div class="card">
                        <div class="body">
                            <p>This will upgrade your package to other package.<br>Please use a pin with greater value than your current package.</p>
                            <form method="get">
                                <form method="get">
                                <div class="form-group">
                                    <label>Pin</label>
                                    <div class="form-line">
                                        <input type="text" name="pin" class="form-control" autofocus required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="join_pin" class="btn btn-primary" value="Check Availiability">
                                </div>
                            </form>
                            <form method="get">
                                <?php
                                    if (isset($_GET['join_pin'])) {
                                        $pin = mysqli_real_escape_string($con,$_GET['pin']);
                                        if (pin_check($pin)) {
                                            $query = mysqli_query($con,"select * from pin_list where pin='$pin'");
                                            $result = mysqli_fetch_array($query);
                                            $pack = $result['pack'];
                                            $q = mysqli_query($con,"select * from user where email='$userid'");
                                            $r = mysqli_fetch_array($q);
                                            $c_pack = $r['pack'];
                                            echo 'Pin Available<br>';
                                            echo '<strong>Pin: </strong>'.$pin.'<br>';
                                            echo '<strong>Package: </strong>'.$pack.'<br><br>';
                                            echo '<strong>Current Package: </strong>'.$c_pack.'<br>';
                                            echo '<strong>New Package: </strong>'.$pack;
                                        }
                                        else{
                                            echo '<script>alert("Pin not available. Please use another pin.");</script>';
                                        }
                                    }
                                ?>
                                <div class="form-group">
                                    <input type="hidden" name="pin" value="<?php echo $pin; ?>" required>
                                    <input type="hidden" name="pack" value="<?php echo $pack; ?>" required>
                                    <input type="hidden" name="c_pack" value="<?php echo $c_pack; ?>" required>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="update" class="btn btn-primary" value="Upgrade">
                                </div>
                            </form>
                        </div>
                    </div>
                            <?php
                        }
                    ?>
                    
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
