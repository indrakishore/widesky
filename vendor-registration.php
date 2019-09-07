<?php
    include('php-includes/check-login.php');
    include('php-includes/connect.php');
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
if (isset($_POST['join'])) {
    $name = mysqli_real_escape_string($con,$_POST['name']);
    $email = mysqli_real_escape_string($con,$_POST['email']);
    $gender = mysqli_real_escape_string($con,$_POST['gender']);
    $dob = mysqli_real_escape_string($con,$_POST['dob']);
    $mobile = mysqli_real_escape_string($con,$_POST['mobile']);
    $address = mysqli_real_escape_string($con,$_POST['address']);
    $bank = mysqli_real_escape_string($con,$_POST['bank']);
    $ifsc = mysqli_real_escape_string($con,$_POST['ifsc']);
    $account = mysqli_real_escape_string($con,$_POST['account']);
    $pan = mysqli_real_escape_string($con,$_POST['pan']);
    $password = mysqli_real_escape_string($con,$_POST['password']);
    $shopname = mysqli_real_escape_string($con,$_POST['shopname']);
    $sponsorid = $userid;
    $category = mysqli_real_escape_string($con,$_POST['category']);

    $town = "FR1";
    $city = "FR1";

    date_default_timezone_set("Asia/Calcutta");
    $join_date=  date("Y-m-d H:i:s");

    $flag = 0;


    
    if($sponsorid!='' && $name!='' && $email!='' && $password!='' && $gender!='' && $dob!='' && $mobile!='' && $address!='' && $shopname!='' && $category!='' && $town!=''&& $city!=''){
        //User filled all the fields.
        if(!email_check($sponsorid))
        {
            if(email_check2($email)){
                //Email is ok
                $flag = 1;
            }
            else{
                //check email
                echo '<script>alert("This user id already used.");</script>';
            }
        }
        else{
            echo '<script>alert("Invalid Sponsor ID");</script>';
        }
    }
    else{
        //check all fields are fill
        echo '<script>alert("Please fill all the fields.");</script>';
    }

    if ($flag==1) {
        mysqli_query($con,"insert into vendor_info(`name`,`email`,`password`,`joindate`,`gender`,`dob`,`mobile`,`address`,`bank`,`ifsc`,`account`,`pan`,`shopname`,`sponsor`,`category`,`townfranchise`,`cityfranchise`) values('$name','$email','$password','$join_date','$gender','$dob','$mobile','$address','$bank','$ifsc','$account','$pan','$shopname','$sponsorid','$category','$town','$city')");
        mysqli_query($con,"insert into vendor_wallet(userid) values('$email')");

       /* $income_data = income($sponsorid);
        $new_day_bal = $income_data['day_bal'] + 500;
        $new_franchise_bal = $income_data['franchise_bal'] + 500;
        mysqli_query($con,"update income set day_bal='$new_day_bal',franchise_bal='$new_franchise_bal' where userid='$sponsorid' limit 1");*/

        //PHPMAILER mail delivery subsystem
        $link = "https://widesky.online/login/vendor";
        $mail = new PHPMailer(TRUE);
        try{
            $mail->setFrom('admin@widesky.online', 'WIDESKY E-RETAILS PVT. LTD.');
            $mail->addAddress($email,$name);
            $mail->Subject = 'Vendor Registration | Members Area | WIDESKY E-RETAILS PVT. LTD.';
            $mail->isHTML(TRUE);$mail->isSMTP();
            $mail->Host = "mail.widesky.online";  
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

        echo '<script>alert("Successfully Registered.");</script>';
    }
    
}
?>
<?php
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
function email_check2($email){
    global $con;
    
    $query =mysqli_query($con,"select * from vendor_info where email='$email'");
    if(mysqli_num_rows($query)>0){
        return false;
    }
    else{
        return true;
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
    $data['franchise_bal'] = $result['franchise_bal'];
    $data['total_bal'] = $result['total_bal'];
    
    return $data;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Vendor Registration | WIDESKY E-RETAILS PVT. LTD.</title>
    <!-- Favicon-->
    <link rel="icon" href="../favicon.ico" type="image/x-icon">

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

    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.jquery.min.js"></script-->
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
                <h1>Vendor Registration</h1>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-cyan">
                            Vendor Registration
                        </div>
                        <div class="body">
                            <form method="post">
                                <div class="form-group">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <div class="form-line">
                                                <input type="text" name="name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Shop Name</label>
                                            <div class="form-line">
                                                <input type="text" name="shopname" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <div class="form-line">
                                                <input type="text" name="category" class="form-control">
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
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <div class="form-line">
                                                <input type="date" name="dob" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <div class="form-line">
                                                <input type="text" name="address" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <div class="form-line">
                                                <input type="text" name="bank" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>IFSC</label>
                                            <div class="form-line">
                                                <input type="text" name="ifsc" class="form-control">
                                            </div> 
                                        </div>
                                        <div class="form-group">
                                            <label>Account</label>
                                            <div class="form-line">
                                                <input type="text" name="account" class="form-control">
                                            </div>   
                                        </div>
                                        <div class="form-group">
                                            <label>PAN</label>
                                            <div class="form-line">
                                                <input type="text" name="pan" class="form-control">
                                            </div>
                                        </div><br>
                                        <div class="form-group">
                                            <input type="submit" name="join" class="btn bg-cyan btn-lg btn-block waves-effect" value="Register">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="card">
                    <div class="header">
                        List of Vendors Sponsored by you
                    </div>
                    <div class="body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTable js-exportable">
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Vendor Name</th>
                                        <th>Shop Name</th>
                                        <th>E-mail</th>
                                        <th>Category</th>
                                        <th>Address</th>
                                        <th>Registration Date</th>
                                    </tr>
                                    <?php
                                        $query = mysqli_query($con,"select * from vendor_info where sponsor='$userid'");
                                        $i=1;
                                        if (mysqli_num_rows($query)>0) {
                                            while ($row=mysqli_fetch_array($query)) {
                                                $id = $row['id'];
                                                $email = $row['email'];
                                                $name = $row['name'];
                                                $shopname = $row['shopname'];
                                                $category = $row['category'];
                                                $joindate = $row['joindate'];
                                                $address = $row['address'];
                                    ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $name; ?></td>
                                                    <td><?php echo $shopname; ?></td>
                                                    <td><?php echo $email; ?></td>
                                                    <td><?php echo $category; ?></td>
                                                    <td><?php echo $address; ?></td>
                                                    <td><?php echo $joindate; ?></td>
                                                </tr>
                                    <?php
                                                $i++;
                                            }
                                        }
                                        else{
                                    ?>
                                            <tr>
                                                <td colspan="7" align="center">You have not registered vendor.</td>
                                            </tr>
                                    <?php
                                        }  
                                    ?> 
                                </table>
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

    <!-- Jquery DataTable Plugin Js -->
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <!--script type="text/javascript">
      $(".chosen").chosen();
    </script-->
</body>

</html>
