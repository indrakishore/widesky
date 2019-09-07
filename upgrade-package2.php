<?php
    include('php-includes/check-login.php');
    require('php-includes/connect.php');
    $userid = $_SESSION['user_id'];
?>
<?php
    if (isset($_GET['update'])) {
        $pin = mysqli_real_escape_string($con,$_GET['pin']);
        $pack = mysqli_real_escape_string($con,$_GET['pack']);
        $c_pack = mysqli_real_escape_string($con,$_GET['c_pack']);
        
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
            $query = mysqli_query($con,"update user set pack='$pack' where email='$userid' ");
            $query = mysqli_query($con,"update tree set pack='$pack' where userid='$userid'");

            $q = mysqli_query($con, "select * from user where email='$userid'");
            $result = mysqli_fetch_array($q);
            $under_userid = $result['under_userid'];
            $side = $result['side'];
            $sponsorid = $result['sponsorid'];

            $temp_under_userid = $under_userid;
            $temp_side_count = $side.'count'; //leftcount or rightcount
            $temp_side_amount = $side.'amount';

            //User registration in partnership program
            if ($pack!='free') {
                $start_date = date("Y-m-d");
                if ($pack=='1000') {
                    $amnt = ($pack*10)/100;
                    $months = 20;
                    $end_date = date("Y-m-d",mktime(0,0,0,date("m")+20,date("d"),date("Y")));
                    mysqli_query($con,"insert into partnership_program(userid,start_date,end_date,total_months,months_left) values('$userid','$start_date','$end_date','20','20')");
                }
                if ($pack=='5000' || $pack=='10000') {
                    $amnt = ($pack*10)/100;
                    $months = 24;
                    $end_date = date("Y-m-d",mktime(0,0,0,date("m")+24,date("d"),date("Y")));
                    mysqli_query($con,"insert into partnership_program(userid,start_date,end_date,total_months,months_left) values('$userid','$start_date','$end_date','24','24')");
                }
                if ($pack=='25000') {
                    $amnt = ($pack*10)/100;
                    $months = 26;
                    $end_date = date("Y-m-d",mktime(0,0,0,date("m")+26,date("d"),date("Y")));
                    mysqli_query($con,"insert into partnership_program(userid,start_date,end_date,total_months,months_left) values('$userid','$start_date','$end_date','26','26')");
                }
                if ($pack=='40000') {
                    $months = 28;
                    $amnt = ($pack*10)/100;
                    $end_date = date("Y-m-d",mktime(0,0,0,date("m")+28,date("d"),date("Y")));
                    mysqli_query($con,"insert into partnership_program(userid,start_date,end_date,total_months,months_left) values('$userid','$start_date','$end_date','28','28')");
                }
                if ($pack=='100000') {
                    $months = 30;
                    $amnt = ($pack*10)/100;
                    $end_date = date("Y-m-d",mktime(0,0,0,date("m")+30,date("d"),date("Y")));
                    mysqli_query($con,"insert into partnership_program(userid,start_date,end_date,total_months,months_left) values('$userid','$start_date','$end_date','30','30')");
                }
                if ($pack=='500000') {
                    $months = 32;
                    $amnt = ($pack*10)/100;
                    $end_date = date("Y-m-d",mktime(0,0,0,date("m")+32,date("d"),date("Y")));
                    mysqli_query($con,"insert into partnership_program(userid,start_date,end_date,total_months,months_left) values('$userid','$start_date','$end_date','32','32')");
                }
                if ($pack=='1000000') {
                    $months = 36;
                    $amnt = ($pack*10)/100;
                    $end_date = date("Y-m-d",mktime(0,0,0,date("m")+36,date("d"),date("Y")));
                    mysqli_query($con,"insert into partnership_program(userid,start_date,end_date,total_months,months_left) values('$userid','$start_date','$end_date','36','36')");
                }
               
                $i=1;
                while($i<=$months){
                    $query = mysqli_query($con,"select * from partnership_program where userid='$userid'and start_date='$start_date'");
                    $result = mysqli_fetch_array($query);
                    $ppid = $result['id'];
                    $next_payment_date = date("Y-m-d",mktime(0,0,0,date("m")+$i,date("d"),date("Y")));
                    mysqli_query($con,"insert into pp_payment(userid,ppid,amount,start_date,next_payment_date) values('$userid','$ppid','$amnt','$start_date','$next_payment_date')");
                    $i++;
                }
            }

            //Direct Income(Shubham)
            if($sponsorid!=""){
                $income_data = income($sponsorid);
                $tree_data = tree($sponsorid);

                //written by shubham (direct income)
                $percentage = 5;
                if ($pack!='free') {
                    $new_day_bal = $income_data['day_bal']+($pack*$percentage)/100;
                    $new_direct_bal = $income_data['direct_bal']+($pack*$percentage)/100;
                }
                else{
                    $new_day_bal = $income_data['day_bal']+0;
                    $new_direct_bal = $income_data['direct_bal']+0;
                }   
                //update income
                mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal' where userid='$sponsorid' limit 1");
            }

            $temp_side = $side;
            $total_count=1;
            $i=1;
            while ($total_count>0) {
                $i;
                $q = mysqli_query($con,"select * from tree where userid='$temp_under_userid'");
                $r = mysqli_fetch_array($q);
                $current_temp_side_amount = $r[$temp_side_amount]+$pack;

                $temp_under_userid;
                $temp_side_count;
                $temp_side_amount;

                mysqli_query($con,"update tree set `$temp_side_amount`=$current_temp_side_amount where userid='$temp_under_userid'");

                $upgrade_date = date("Y-m-d");

                //income
                if($temp_under_userid!=""){
                    $income_data = income($temp_under_userid);
                    $tree_data = tree($temp_under_userid);
                        
                    $temp_left_amount = $tree_data['leftamount'];
                    $temp_right_amount = $tree_data['rightamount'];

                    //Both left and right side should at least have pair amount(pair will be on less amount)
                    if($temp_left_amount>0 && $temp_right_amount>0){
                        $per = 10;
                        $temp_left_amount;
                        $temp_right_amount;
                        if($temp_left_amount<=$temp_right_amount){
                                        
                            $new_day_bal = $income_data['day_bal']+($temp_left_amount*$per)/100;
                            $new_direct_bal = $income_data['direct_bal']+0;
                            $new_matching_bal = $income_data['matching_bal']+($temp_left_amount*$per)/100;

                            ////////////////////////////////////////////////////////////////

                           $sql = mysqli_query($con,"select * from tree where userid='$temp_under_userid'");
                            $ans = mysqli_fetch_array($sql);
                            $c_matchedamount = $ans['matchedamount'];
                            $new_matchedamount = $c_matchedamount + $temp_left_amount;
                            mysqli_query($con,"update tree set matchedamount='$new_matchedamount' where userid='$temp_under_userid'");
                            mysqli_query($con,"insert into monthly_matching_balance(userid,date,matchedbalance) values('$temp_under_userid','$upgrade_date','$temp_left_amount')");


                            ////////////////////////////////////////////////////////////////

                            $temp_right_amount = $temp_right_amount - $temp_left_amount;
                            $temp_left_amount = 0;
                            mysqli_query($con,"update tree set leftamount='$temp_left_amount' where userid='$temp_under_userid'");
                            mysqli_query($con,"update tree set rightamount='$temp_right_amount' where userid='$temp_under_userid'");
                            //update income
                            mysqli_query($con,"update income set day_bal='$new_day_bal',direct_bal='$new_direct_bal',matching_bal='$new_matching_bal' where userid='$temp_under_userid' limit 1");  
                            echo mysqli_error($con);
                        }

                        if($temp_right_amount<$temp_left_amount){
                            
                            $new_day_bal = $income_data['day_bal']+($temp_right_amount*$per)/100;
                            $new_direct_bal = $income_data['direct_bal']+0;
                            $new_matching_bal = $income_data['matching_bal']+($temp_right_amount*$per)/100;

                            ////////////////////////////////////////////////////////////////

                           $sql = mysqli_query($con,"select * from tree where userid='$temp_under_userid'");
                            $ans = mysqli_fetch_array($sql);
                            $c_matchedamount = $ans['matchedamount'];
                            $new_matchedamount = $c_matchedamount + $temp_right_amount;
                            mysqli_query($con,"update tree set matchedamount='$new_matchedamount' where userid='$temp_under_userid'");
                            mysqli_query($con,"insert into monthly_matching_balance(userid,date,matchedbalance) values('$temp_under_userid','$upgrade_date','$temp_right_amount')");


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
                    }
                    //change under_userid
                    $next_under_userid = getUnderId($temp_under_userid);
                    $temp_side = getUnderIdPlace($temp_under_userid);
                    $temp_side_count = $temp_side.'count';
                    $temp_side_amount = $temp_side.'amount';
                    $temp_under_userid = $next_under_userid;    
                    
                    $i++;
                }
                //Check for the last user
                if($temp_under_userid==""){
                    $total_count=0;
                }
            }

            //mail delivery system
            $to = $userid;
            $subject = "Pack Upgraded | Members Area | WIDESKY E-RETAILS PVT. LTD.";
            $link = "http://account.ultimateshop.online/";
            $headers = "Reply-To: WIDESKY E-RETAILS PVT. LTD. <admin@ultimateshop.online>\r\n"; 
            $headers .= "Return-Path: WIDESKY E-RETAILS PVT. LTD. <admin@ultimateshop.online>\r\n"; 
            $headers .= "From: WIDESKY E-RETAILS PVT. LTD. <admin@ultimateshop.online>\r\n";  
            $headers .= "Organization: WIDESKY E-RETAILS PVT. LTD.\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $headers .= "X-Priority: 3\r\n";
            $headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;
            $message = "<html><body>";
            $message .= "<h1>Members Area | WIDESKY E-RETAILS PVT. LTD.</h1>";
            $message .= "<h2>Pack Upgraded</h2>";
            $message .= "Your Pack is successfully upgraded. "."<br><br>";
            $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
            $message .= "<tr style='background: #eee;'><td><strong>UserID:</strong> </td><td>" . $userid . "</td></tr>";
            $message .= "<tr><td><strong>Old Pack:</strong> </td><td>" . $c_pack . "</td></tr>";
            $message .= "<tr><td><strong>New Pack:</strong> </td><td>" . $pack . "</td></tr></table><br>";
            $message .= "<a href='http://account.ultimateshop.online'>Click Here to Login</a><br>";
            $message .= "<p>For any queries contact us at: +91 7669459250</p>";
            $message .= "</html></body>";       
            mail($to,$subject,$message,$headers);

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
    $data['leftcount'] = $result['leftcount'];
    $data['rightcount'] = $result['rightcount'];
    $data['leftamount'] = $result['leftamount'];
    $data['rightamount'] = $result['rightamount'];
    $data['matchedamount'] = $result['matchedamount'];
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
