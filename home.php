<?php
    include('php-includes/check-login.php');
    include('php-includes/connect.php');
    //Checking current user who is logged in
    $userid = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Home | WIDESKY E-RETAILS PVT. LTD.</title>
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

    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
                <h1>Dashboard</h1>
            </div>
            <?php
                $query = mysqli_query($con,"select * from tree where userid='$userid'");
                $result = mysqli_fetch_array($query);
                $timeup = $result['timeupgrade'];
                if ($timeup<30 && $timeup>-1) {
                echo   '<div class="row">
                        <div class="alert alert-warning">
                            <strong>Warning!</strong> You have '.$timeup.' days to upgrade your package. After that you will not be able to upgrade your package.
                        </div>
                    </div>';
                }
            ?>
            
            <div class="row">
                <?php
                    $query = mysqli_query($con,"select * from income where userid='$userid'");
                    $result = mysqli_fetch_array($query);
                ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-2 bg-purple hover-zoom-effect">
                        <div class="icon">
                            <i class="fa fa-rupee"></i>
                        </div>
                        <div class="content">
                            <div class="text">DIRECT BONUS</div>
                            <div class="number">
                                <?php
                                    echo $result['direct_bal'];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-2 bg-teal hover-zoom-effect">
                        <div class="icon">
                            <i class="fa fa-rupee"></i>
                        </div>
                        <div class="content">
                            <div class="text">MATCHING BONUS</div>
                            <div class="number">
                                <?php
                                    echo $result['matching_bal'];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-2 bg-grey hover-zoom-effect">
                        <div class="icon">
                            <i class="fa fa-rupee"></i>
                        </div>
                        <div class="content">
                            <div class="text">FRANCHISE COMISSION</div>
                            <div class="number">
                                <?php
                                    echo $result['franchise_bal'];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-2 bg-indigo hover-zoom-effect">
                        <div class="icon">
                            <i class="fa fa-rupee"></i>
                        </div>
                        <div class="content">
                            <div class="text">CLUB BONUS</div>
                            <div class="number">
                                <?php
                                    echo $result['club_bal'];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-2 bg-light-green hover-zoom-effect">
                        <div class="icon">
                            <i class="fa fa-rupee"></i>
                        </div>
                        <div class="content">
                            <div class="text">CURRENT INCOME</div>
                            <div class="number">
                                <?php
                                    echo $result['current_bal'];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-2 bg-orange hover-zoom-effect">
                        <div class="icon">
                            <i class="fa fa-rupee"></i>
                        </div>
                        <div class="content">
                            <div class="text">TOTAL INCOME</div>
                            <div class="number">
                                <?php
                                    echo $result['total_bal'];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-2 bg-light-blue hover-zoom-effect">
                        <div class="icon">
                            <i class="fa fa-rupee"></i>
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
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box-2 bg-pink hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">adjust</i>
                        </div>
                        <div class="content">
                            <div class="text">AVAILABLE PINS</div>
                            <div class="number">
                                <?php
                                    echo mysqli_num_rows(mysqli_query($con,"select * from pin_list where userid='$userid' and status='open'"));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="header bg-teal">
                            <center><strong>Rewards</strong></center>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <th>S.No.</th>
                                        <th>Reward</th>
                                        <th>Needed Reward Pts.</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query = mysqli_query($con,"select * from rewards");
                                            $i=1;
                                           while($result = mysqli_fetch_array($query)) {
                                            $name = $result['name'];
                                            $npoints = $result['npoints'];
                                            $rid = $result['rwdid'];

                                            $sql = mysqli_query($con,"select * from tree where userid='$userid'");
                                            $r = mysqli_fetch_array($sql);
                                            $status = $r['rwd'.$i];
                                            if ($status=='open') {
                                                $class = "btn btn-warning";
                                                $stat = "Unachieved";
                                            }
                                            elseif ($status=='close') {
                                                $class = "btn btn-success";
                                                $stat = "Achieved";
                                            }
                                            else{
                                                $class = "btn btn-error";
                                                $stat = "Inactive";
                                            }

                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><strong><?php echo $name; ?></strong></td>
                                                <td><?php echo $npoints; ?></td>
                                                <td><button class="<?php echo $class;?>"><?php echo $stat;?></button></td>
                                            </tr>
                                            <?php
                                            $i++;
                                           }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">Links for Registration</h4>
                        </div>
                        <div class="panel-body bg-success">
                            <?php
                                echo "<strong>Left: </strong><input type = 'text' value='www.widesky.online/userRegistration.php?suser=".$userid."&side=left' class='form-control'><br>";
                                echo "<strong>Right: </strong><input type = 'text' value='www.widesky.online/userRegistration.php?suser=".$userid."&side=right' class='form-control'><br>";
                                echo "Use these registration links to get free registrations.";
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                    $query = mysqli_query($con,"select * from user where email='$userid'");
                    $result = mysqli_fetch_array($query);
                ?>
                <div class="col-lg-4">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">User Info</h4>
                        </div>
                        <div class="panel-body bg-teal">
                            <?php
                                echo "<strong>User Code: </strong>".$result['id']."<br>";
                                echo "<strong>Package: </strong>".$result['pack']."<br>";
                                echo "<strong>Name: </strong>".$result['name']."<br>";
                                echo "<strong>E-Mail: </strong>".$result['email']."<br>";
                                echo "<strong>Sponsor ID: </strong>".$result['sponsorid']."<br>";
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">Bank Details</h4>
                        </div>
                        <div class="panel-body bg-orange">
                            <?php
                                echo "<strong>A/C No.: </strong>".$result['account']."<br>";
                                echo "<strong>Bank: </strong>".$result['bank']."<br>";
                                echo "<strong>IFSC: </strong>".$result['ifsc']."<br>";
                                echo "<strong>PAN: </strong>".$result['pan']."<br>";
                            ?>
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
