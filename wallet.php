<?php
    include('php-includes/check-login.php');
    include('php-includes/connect.php');
    $userid = $_SESSION['user_id'];
    $fid='';
?>
<?php
    if (isset($_POST['send'])) {
        $amount = mysqli_real_escape_string($con,$_POST['amount']);
        $prefix = mysqli_real_escape_string($con,$_POST['prefix']);
        $fremail = mysqli_real_escape_string($con,$_POST['fremail']);
        date_default_timezone_set("Asia/Calcutta");
        $date = date("Y-m-d H:i:s");
        $query = mysqli_query($con,"select * from income where userid='$userid'");
        $result = mysqli_fetch_array($query);
        $sender_type = 'Customer';
        $wallet_bal = $result['wallet_bal'];
        if ($amount<=$wallet_bal) {
            $new_wallet_bal = $wallet_bal - $amount;
            mysqli_query($con,"update income set wallet_bal='$new_wallet_bal' where userid='$userid'");
            mysqli_query($con,"insert into wallet_record(userid,wallet_bal,sender_type,date,sentto) values('$userid','$amount','$sender_type','$date','$fremail')");
            if ($prefix=='FR') {
                $q = mysqli_query($con,"select * from fr_income where userid='$fremail'");
                $r = mysqli_fetch_array($q);
                $wbal = $r['wallet_bal'];
                $new_wbal = $wbal + $amount;
                mysqli_query($con,"update fr_income set wallet_bal='$new_wbal' where userid='$fremail'");
            }
            if ($prefix=='VD') {
                $q = mysqli_query($con,"select * from vendor_wallet where userid='$fremail'");
                $r = mysqli_fetch_array($q);
                $wbal = $r['wallet_bal'];
                $new_wbal = $wbal + $amount;
                mysqli_query($con,"update vendor_wallet set wallet_bal='$new_wbal' where userid='$fremail'");
            }


            echo '<script>alert("Amount sent successfully.");</script>';
        }
        else{
            echo '<script>alert("Invalid amount or some error has occured. Please Try Again.");</script>';
        }
        

    }
?>

<?php
function usercheck($fid){
    global $con,$user;
    
    $query =mysqli_query($con,"select * from fr_partner where id='$fid'");
    if(mysqli_num_rows($query)>0){
        return true;
    }
    else{
        return false;
    }
}
function usercheck2($fid){
    global $con,$user;
    
    $query =mysqli_query($con,"select * from vendor_info where id='$fid'");
    if(mysqli_num_rows($query)>0){
        return true;
    }
    else{
        return false;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Wallet | WIDESKY E-RETAILS PVT. LTD.</title>
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

    <!-- Colorpicker Css -->
    <link href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />

    <!-- Dropzone Css -->
    <link href="plugins/dropzone/dropzone.css" rel="stylesheet">

    <!-- Multi Select Css -->
    <link href="plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- noUISlider Css -->
    <link href="plugins/nouislider/nouislider.min.css" rel="stylesheet" />

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
                <h1>WALLET</h1>
            </div>
            <div class="row clearfix">
                <?php
                    $query = mysqli_query($con,"select * from income where userid='$userid'");
                    $result = mysqli_fetch_array($query);
                ?>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="info-box-4 bg-light-green hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">account_balance_wallet</i>
                        </div>
                        <div class="content">
                            <div class="text">WALLET BALANCE</div>
                            <div class="number">
                                <?php
                                    echo $result['wallet_bal'];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            Send Wallet Amount to ULTIMATE SHOP<br>
                            <small>to buy products</small>
                        </div>
                        <div class="body">
                            <form method="post">
                                <div class="form-group">
                                    <label>Franchise/Vendor ID</label>
                                    <div class="form-line">
                                        <input type="text" name="fid" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="check" value="Check" class="btn btn-primary btn-lg btn-block" required>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                 <?php
                                    if (isset($_POST['check'])) {
                                        $str = mysqli_real_escape_string($con,$_POST['fid']);
                                        $fid = (int) filter_var($str, FILTER_SANITIZE_NUMBER_INT);
                                        $prefix = substr($str,0,2);
                                        $flag = 0;
                                        if ($prefix=='FR') {
                                            if (usercheck($fid)) {
                                                $query = mysqli_query($con,"select * from fr_partner where id='$fid'");
                                                $result = mysqli_fetch_array($query);
                                                $name = $result['name'];
                                                $address = $result['address'];
                                                $fremail = $result['email'];
                                                $type = $result['type'];
                                                
                                                echo '<b>Franchise Info</b>:<br>';
                                                echo '<strong>Name: </strong>'.$name.'<br>';
                                                echo '<strong>Franchise Mail: </strong>'.$fremail.'<br>';
                                                echo '<strong>Type: </strong>'.$type.'<br>';
                                                echo '<strong>Address: </strong>'.$address.'<br>';
                                                $flag = 1;
                                            }
                                            else{
                                                echo '<script>alert("Invalid Franchise Code.");</script>';
                                            }
                                        }
                                        else if ($prefix=='VD') {
                                            if (usercheck2($fid)) {
                                                $query = mysqli_query($con,"select * from vendor_info where id='$fid'");
                                                $result = mysqli_fetch_array($query);
                                                $name = $result['name'];
                                                $address = $result['address'];
                                                $fremail = $result['email'];
                                                $store = $result['shopname'];
                                                
                                                echo '<b>Vendor Info</b>:<br>';
                                                echo '<strong>Name: </strong>'.$name.'<br>';
                                                echo '<strong>Vendor Mail: </strong>'.$fremail.'<br>';
                                                echo '<strong>Store: </strong>'.$store.'<br>';
                                                echo '<strong>Address: </strong>'.$address.'<br>';
                                                $flag = 1;
                                            }
                                            else{
                                                echo '<script>alert("Invalid Vendor Code.");</script>';
                                            }
                                        }
                                        else{
                                            echo '<script>alert("Invalid Vendor/Franchise Code.");</script>';
                                        }
                                        
                                    }
                                    ?>
                <?php 
                if ($fid!='' && $flag==1) {
                    echo '<div class="row clearfix">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="card">
                                <div class="body">
                                    <form method="post">
                                        <div class="form-group">
                                        </div>
                                            <input type="hidden" name="prefix" value='.$prefix.' required>
                                            <input type="hidden" name="fremail" value='.$fremail.' required>
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <div class="form-line">
                                                <input type="number" name="amount" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" name="send" class="btn btn-success" value="Send">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
                }
                 ?>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">Wallet Amount Sent History</div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Sent Amount</th>
                                            <th>Date</th>
                                            <th>Franchise Mail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query = mysqli_query($con,"select * from wallet_record where userid='$userid'");
                                            $i=1;
                                            if (mysqli_num_rows($query)>0) {
                                                while ($row=mysqli_fetch_array($query)) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row['wallet_bal']; ?></td>
                                                    <td><?php echo $row['date']; ?></td>
                                                    <td><?php echo $row['sentto']; ?></td>
                                                </tr>
                                        <?php
                                                    $i++;
                                                }
                                            }
                                            else{
                                        ?>
                                                <tr>
                                                    <td colspan="4">No entries found.</td>
                                                </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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

    <!-- Bootstrap Colorpicker Js -->
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

    <!-- Dropzone Plugin Js -->
    <script src="plugins/dropzone/dropzone.js"></script>

    <!-- Input Mask Plugin Js -->
    <script src="plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <!-- Multi Select Plugin Js -->
    <script src="plugins/multi-select/js/jquery.multi-select.js"></script>

    <!-- Jquery Spinner Plugin Js -->
    <script src="plugins/jquery-spinner/js/jquery.spinner.js"></script>

    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

    <!-- noUISlider Plugin Js -->
    <script src="plugins/nouislider/nouislider.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/forms/advanced-form-elements.js"></script>

    <!-- Demo Js -->
    <script src="js/demo.js"></script>
</body>

</html>
