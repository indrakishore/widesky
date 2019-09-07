<?php
    include('php-includes/check-login.php');
    require('php-includes/connect.php');
    $userid = $_SESSION['user_id'];
?>
<?php
    if (isset($_GET['update'])) {
        $bank = mysqli_real_escape_string($con,$_GET['bank']);
        $ifsc = mysqli_real_escape_string($con,$_GET['ifsc']);
        $account = mysqli_real_escape_string($con,$_GET['account']);
        $pan = mysqli_real_escape_string($con,$_GET['pan']);
        if ($bank!='' && $ifsc!='' && $account!='' && $ifsc!='') {
            $query = mysqli_query($con,"update user set bank='$bank' where email='$userid' ");
            $query = mysqli_query($con,"update user set ifsc='$ifsc' where email='$userid' ");
            $query = mysqli_query($con,"update user set account='$account' where email='$userid' ");
            $query = mysqli_query($con,"update user set pan='$pan' where email='$userid' ");
            echo '<script>alert("Successfully Updated.");window.location.assign("user-profile.php");</script>';
        }
        else{
            echo '<script>alert("Please fill all the fields.");</script>';
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Update Bank Details | WIDESKY E-RETAILS PVT. LTD.</title>
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
                <h1>UPDATE BANK DETAILS</h1>
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <form method="get">
                                <div class="form-group">
                                    <label class="form-label">Bank Name</label>
                                    <div class="form-line">
                                        <input type="text" name="bank" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">IFSC</label>
                                    <div class="form-line">
                                        <input type="text" name="ifsc" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">A/C No.</label>
                                    <div class="form-line">
                                        <input type="text" name="account" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">PAN</label>
                                    <div class="form-line">
                                        <input type="text" name="pan" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="update" class="btn btn-primary" value="Update">
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
