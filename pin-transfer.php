<?php
    include('php-includes/check-login.php');
    include('php-includes/connect.php');
    $userid = $_SESSION['user_id'];
?>
<?php
    if (isset($_POST['transfer'])) {
        $pin = mysqli_real_escape_string($con,$_POST['pin']);
        $pack = mysqli_real_escape_string($con,$_POST['pack']);
        $user = mysqli_real_escape_string($con,$_POST['user']);
        if ($pin!='' && $pack!='' && $user!='') {
            if (email_check($user)) {
                if (pin_check($pin)) {
                    mysqli_query($con,"delete from pin_list where userid='$userid' and pin='$pin' and pack='$pack' ");
                    mysqli_query($con,"insert into pin_list(userid,pin,pack,status) values('$user','$pin','$pack','open') ");
                    echo '<script>alert("Pin Transfered Successfully");</script>';
                }
                else{
                    echo '<script>alert("Pin Transfer Failed! Pin not available.");</script>';
                }
            }
            else{
                echo '<script>alert("Pin Transfer Failed! No such user exist.");</script>';
            }
        }
        else{
            echo '<script>alert("Error occurred. Try Again.");</script>';
        }
    }
?>
<?php
    function email_check($email){
        global $con;
        
        $query =mysqli_query($con,"select * from user where email='$email'");
        if(mysqli_num_rows($query)>0){
            return true;
        }
        else{
            return false;
        }
    }    

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

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Pin Transfer | WIDESKY E-RETAILS PVT. LTD.</title>
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

    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

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
                <h1>PIN TRANSFER</h1>
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <form method="post">
                            <div class="form-group">
                                <label>Pin</label>
                                <div class="form-line">
                                    <input type="text" name="pin" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="check" class="btn btn-primary btn-block" value="Check Availiability">
                            </div>
                        </form>
                        <form method="post">
                            <?php
                                if (isset($_POST['check'])) {
                                    $pin = mysqli_real_escape_string($con,$_POST['pin']);
                                    if (pin_check($pin)) {
                                        $query = mysqli_query($con,"select * from pin_list where pin='$pin'");
                                        $result = mysqli_fetch_array($query);
                                        $pack = $result['pack'];
                                        echo 'Pin Available<br>';
                                        echo '<strong>Pin: </strong>'.$pin.'<br>';
                                        echo '<strong>Package: </strong>'.$pack.'<br><br>';
                                    }
                                    else{
                                        echo '<script>alert("Pin not available. Please use another pin.");</script>';
                                    }
                                }
                            ?>
                            <div class="form-group">
                                <input type="hidden" name="pin" value="<?php echo $pin; ?>" required>
                                <input type="hidden" name="pack" value="<?php echo $pack; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>UserID</label>
                                <div class="form-line">
                                    <input type="email" name="user" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="transfer" class="btn btn-primary btn-block" value="Transfer">
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            PIN LIST
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Pin</th>
                                            <th>Pack</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i=1;
                                        $query = mysqli_query($con,"select * from pin_list where userid='$userid' and status='open'");
                                        if (mysqli_num_rows($query)>0) {
                                            while ($row=mysqli_fetch_array($query)) {
                                                $pin = $row['pin'];
                                                $pack = $row['pack'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $i;?></td>
                                                    <td><?php echo $pin;?></td>
                                                    <td><?php echo $pack;?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            }
                                        }
                                        else{
                                        ?>
                                            <tr>
                                                <td colspan="3">Sorry, You have no pins.</td>
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

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

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

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>

    <!-- Demo Js -->
    <script src="js/demo.js"></script>
</body>

</html>
