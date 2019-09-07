<?php
    include('php-includes/check-login.php');
    require('php-includes/connect.php');
    $userid = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>User Profile | WIDESKY E-RETAILS PVT. LTD.</title>
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
                <h1>USER PROFILE</h1>
            </div>
            <div class="row">
                    <?php
                        $query = mysqli_query($con,"select * from user where email='$userid'");
                        $result = mysqli_fetch_array($query);
                    ?>
                    <div class="col-lg-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h2 class="panel-title" style="font-size:25px"><?php echo $result['name']; ?></h2>
                            </div>
                            <div class="panel-body bg-light-blue">
                                <p style="font-size:18px; line-height: 35px"><?php
                                    echo '<i class="fa fa-check-circle fa-fw"></i><strong>Package: </strong>'.$result['pack']."<br>";
                                    echo '<i class="fa fa-envelope fa-fw"></i><strong>E-Mail: </strong>'.$result['email']."<br>";
                                    echo '<i class="fa fa-mobile-phone fa-fw"></i><strong>Mobile: </strong>'.$result['mobile']."<br>";
                                    if ($result['gender']=='Male') {
                                        echo '<i class="fa fa-male fa-fw"></i><strong>Gender: </strong>'.$result['gender']."<br>";
                                    }
                                    elseif ($result['gender']=='Female') {
                                        echo '<i class="fa fa-female fa-fw"></i><strong>Gender: </strong>'.$result['gender']."<br>";
                                    }
                                    else{
                                        echo '<i class="fa fa-male fa-fw"></i><strong>Gender: </strong>'.$result['gender']."<br>";
                                    }
                                    echo '<i class="fa fa-home fa-fw"></i><strong>Address: </strong>'.$result['address']."<br>";
                                ?></p>
                            </div>
                            <div class="panel-footer">
                                <button onclick="window.location.href='update-profile.php'" class="btn btn-primary btn-block" name="update-profile">Update Profile</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h4 class="panel-title" style="font-size:25px">Bank Details</h4>
                            </div>
                            <div class="panel-body bg-orange">
                                <p style="font-size:18px; line-height: 35px"><?php
                                    echo  '<i class="fa fa-font fa-fw"></i><strong>A/C No.: </strong>'.$result['account']."<br>";
                                    echo '<i class="fa fa-bank fa-fw"></i><strong>Bank: </strong>'.$result['bank']."<br>";
                                    echo '<i class="fa fa-chevron-circle-right fa-fw"></i><strong>IFSC: </strong>'.$result['ifsc']."<br>";
                                    echo '<i class="fa fa-credit-card fa-fw"></i><strong>PAN: </strong>'.$result['pan']."<br>";
                                ?></p>
                            </div>
                            <div class="panel-footer">
                                <button onclick="window.location.href='update-bank-details.php'" class="btn btn-warning btn-block" name="bank-details">Update Bank Details</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php
                        $query = mysqli_query($con,"select * from nominee where userid='$userid'");
                        $r = mysqli_fetch_array($query);
                    ?>
                    <div class="col-lg-6">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h2 class="panel-title" style="font-size:25px">Nominee Details</h2>
                            </div>
                            <div class="panel-body bg-green">
                                <p style="font-size:18px; line-height: 35px"><?php
                                    echo '<i class="fa fa-user fa-fw"></i><strong>Name: </strong>'.$r['nname']."<br>";
                                    if ($r['gender']=='Male') {
                                        echo '<i class="fa fa-male fa-fw"></i><strong>Gender: </strong>'.$r['gender']."<br>";
                                    }
                                    elseif ($r['gender']=='Female') {
                                        echo '<i class="fa fa-female fa-fw"></i><strong>Gender: </strong>'.$r['gender']."<br>";
                                    }
                                    else{
                                        echo '<i class="fa fa-male fa-fw"></i><strong>Gender: </strong>'.$r['gender']."<br>";
                                    }
                                    echo '<i class="fa fa-chevron-right fa-fw"></i><strong>Relation: </strong>'.$r['relation']."<br>";
                                    echo '<i class="fa fa-mobile-phone fa-fw"></i><strong>Mobile: </strong>'.$r['mobile']."<br>";
                                    echo '<i class="fa fa-chevron-right fa-fw"></i><strong>Date of Birth: </strong>'.$r['dob']."<br>";
                                ?></p>
                            </div>
                            <div class="panel-footer">
                                <button onclick="window.location.href='update-nominee.php'" class="btn btn-success btn-block" name="update-nominee">Update Nominee Details</button>
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
