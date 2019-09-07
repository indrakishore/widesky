<?php
    include('php-includes/check-login.php');
    include('php-includes/connect.php');
    $userid = $_SESSION['user_id'];
    $search = $userid;
?>
<?php
function tree_data($userid){
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
    $data['pack'] = $result['pack'];
    $data['leftrp'] = $result['leftrp'];
    $data['rightrp'] = $result['rightrp'];
    $data['matchedrp'] = $result['matchedrp'];
    return $data;
}
?>
<?php
if (isset($_GET['search-id'])) {
    $search_id = mysqli_real_escape_string($con,$_GET['search-id']);
    if ($search_id!='') {
        $query_check= mysqli_query($con,"select * from user where email='$search_id'");
        if (mysqli_num_rows($query_check)>0) {
            $search = $search_id;
        }
        else{
            echo '<script>alert("Access Denied");window.location.assign("tree.php")</script>';
        }
    }
    else{
        echo '<script>alert("Access Denied");window.location.assign("tree.php")</script>';
    }
    
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Tree | WIDESKY E-RETAILS PVT. LTD.</title>
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
                <h1>TREE</h1>
            </div>
            <div class="row clearfix">

                            <?php
                                $data =  tree_data($search);
                            ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4">
                                <div class="info-box-2 bg-purple hover-zoom-effect">
                                    <div class="icon">
                                        <i class="fa fa-rupee"></i>
                                    </div>
                                    <div class="content">
                                        <div class="text">MATCHED AMOUNT</div>
                                        <div class="number">
                                            <?php
                                                echo $data['matchedamount'];
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="info-box-2 bg-orange hover-zoom-effect">
                                    <div class="icon">
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <div class="content">
                                        <div class="text">REWARD POINTS</div>
                                        <div class="number">
                                            <?php
                                                echo $data['matchedrp'];
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div><br><br>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <form>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="search-id" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="search" class="btn btn-primary" value="Search">        
                                </div>
                            </form>

                        </div>
                        


                        <div class="body">
                            <div class="table-responsive">
                                <table class="table" align="center" border="0" style="text-align: center;">
                                    <?php
                                    $query = mysqli_query($con,"select * from user where email='$search'");
                                    $result =  mysqli_fetch_array($query);
                                    $name = $result['name'];
                                    ?>
                                    <tr height="120">
                                        <td><?php echo "Left Users: ".$data['leftcount']."<br>Left Amount: ".$data['leftamount']."<br>Left Reawrd Points: ".$data['leftrp']; ?></td>
                                        <td colspan="2"><i class="fa fa-user fa-4x" style="color: blue"></i><p><?php echo $name."<br>".$search."<br>Pack: ".$data['pack']; ?></p></td>
                                        <td><?php echo "Right Users: ".$data['rightcount']."<br>Right Amount: ".$data['rightamount']."<br>Right Reward Points: ".$data['rightrp']; ?></td>
                                    </tr>
                                    <tr height="120"> 
                                    <?php
                                    $l = $data['left'];
                                    $first_left_user = $l;
                                    $q = mysqli_query($con,"select * from tree where userid='$l'");
                                    $r = mysqli_fetch_array($q);
                                    $first_left_pack = $r['pack'];
                                    $query = mysqli_query($con,"select * from user where email='$first_left_user'");
                                    $result = mysqli_fetch_array($query);
                                    $first_left_name = $result['name'];

                                    $r = $data['right'];
                                    $first_right_user = $r;
                                    $q = mysqli_query($con,"select * from tree where userid='$r'");
                                    $r = mysqli_fetch_array($q);
                                    $first_right_pack = $r['pack'];
                                    $query = mysqli_query($con,"select * from user where email='$first_right_user'");
                                    $result = mysqli_fetch_array($query);
                                    $first_right_name = $result['name'];
                                    ?>
                                    <?php
                                        if ($first_left_user!='') {
                                    ?>
                                        <td colspan="2"><a href="tree.php?search-id=<?php echo $first_left_user; ?>"><i class="fa fa-user fa-4x" style="color: #736e6f"></i><p><?php echo $first_left_name."<br>".$first_left_user."<br>Pack: ".$first_left_pack; ?></p></a></td> 
                                    <?php
                                        }
                                        else{
                                    ?>
                                        <td colspan="2"><a href="join.php?under_userid=<?php echo $search; ?>&side=left"><i class="fa fa-user fa-4x" style="color: #736e6f"></i></a></td> 
                                    <?php

                                        }
                                    ?> 
                                    <?php
                                        if ($first_right_user!='') {
                                    ?>
                                        <td colspan="2"><a href="tree.php?search-id=<?php echo $first_right_user; ?>"><i class="fa fa-user fa-4x" style="color: #736e6f"></i><p><?php echo $first_right_name."<br>".$first_right_user."<br>Pack: ".$first_right_pack; ?></p></a></td>
                                    <?php
                                        }
                                        else{
                                    ?>
                                        <td colspan="2"><a href="join.php?under_userid=<?php echo $search; ?>&side=right"><i class="fa fa-user fa-4x" style="color: #736e6f"></i></a></td>
                                    <?php

                                        }
                                    ?>               
                                    </tr>
                                    <tr height="120">
                                    <?php
                                    $data_first_left_user = tree_data($first_left_user);
                                    $l = $data_first_left_user['left'];
                                    $second_left_user = $l;
                                    $q = mysqli_query($con,"select * from tree where userid='$l'");
                                    $r = mysqli_fetch_array($q);
                                    $second_left_pack = $r['pack'];
                                    $query = mysqli_query($con,"select * from user where email='$second_left_user'");
                                    $result = mysqli_fetch_array($query);
                                    $second_left_name = $result['name'];


                                    $r = $data_first_left_user['right'];
                                    $second_right_user = $r;
                                    $q = mysqli_query($con,"select * from tree where userid='$r'");
                                    $r = mysqli_fetch_array($q);
                                    $second_right_pack = $r['pack'];
                                    $query = mysqli_query($con,"select * from user where email='$second_right_user'");
                                    $result = mysqli_fetch_array($query);
                                    $second_right_name = $result['name'];


                                    $data_first_right_user = tree_data($first_right_user);
                                    $l = $data_first_right_user['left'];
                                    $third_left_user = $l;
                                    $q = mysqli_query($con,"select * from tree where userid='$l'");
                                    $r = mysqli_fetch_array($q);
                                    $third_left_pack = $r['pack'];
                                    $query = mysqli_query($con,"select * from user where email='$third_left_user'");
                                    $result = mysqli_fetch_array($query);
                                    $third_left_name = $result['name'];

                                    $r = $data_first_right_user['right'];
                                    $third_right_user = $r;
                                    $q = mysqli_query($con,"select * from tree where userid='$r'");
                                    $r = mysqli_fetch_array($q);
                                    $third_right_pack = $r['pack'];
                                    $query = mysqli_query($con,"select * from user where email='$third_right_user'");
                                    $result = mysqli_fetch_array($query);
                                    $third_right_name = $result['name'];
                                    ?>
                                        <?php
                                        if ($second_left_user!='') {
                                    ?>
                                        <td ><a href="tree.php?search-id=<?php echo $second_left_user; ?>"><i class="fa fa-user fa-4x" style="color: brown"></i><p><?php echo $second_left_name."<br>".$second_left_user."<br>Pack: ".$second_left_pack; ?></p></a></td>
                                    <?php
                                        }
                                        else{
                                    ?>
                                        <td ><i class="fa fa-user fa-4x" style="color: brown"></i><p><?php echo $second_left_user; ?></p></td> 
                                    <?php

                                        }
                                    ?>         
                                    <?php
                                        if ($second_right_user!='') {
                                    ?>
                                        <td ><a href="tree.php?search-id=<?php echo $second_right_user; ?>"><i class="fa fa-user fa-4x" style="color: brown"></i><p><?php echo $second_right_name."<br>".$second_right_user."<br>Pack: ".$second_right_pack; ?></p></a></td>
                                    <?php
                                        }
                                        else{
                                    ?>
                                        <td ><i class="fa fa-user fa-4x" style="color: brown"></i><p><?php echo $second_right_user; ?></p></td> 
                                    <?php

                                        }
                                    ?>    
                                    <?php
                                        if ($third_left_user!='') {
                                    ?>
                                        <td ><a href="tree.php?search-id=<?php echo $third_left_user; ?>"><i class="fa fa-user fa-4x" style="color: brown"></i><p><?php echo $third_left_name."<br>".$third_left_user."<br>Pack: ".$third_left_pack; ?></p></a></td>
                                    <?php
                                        }
                                        else{
                                    ?>
                                        <td ><i class="fa fa-user fa-4x" style="color: brown"></i><p><?php echo $third_left_user; ?></p></td> 
                                    <?php

                                        }
                                    ?>
                                    <?php
                                        if ($third_right_user!='') {
                                    ?>
                                        <td ><a href="tree.php?search-id=<?php echo $third_right_user; ?>"><i class="fa fa-user fa-4x" style="color: brown"></i><p><?php echo $third_right_name."<br>".$third_right_user."<br>Pack: ".$third_right_pack; ?></p></a></td>
                                    <?php
                                        }
                                        else{
                                    ?>
                                        <td ><i class="fa fa-user fa-4x" style="color: brown"></i><p><?php echo $third_right_user; ?></p></td> 
                                    <?php

                                        }
                                    ?>        
                                        </tr>
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

    <!-- Custom Js -->
    <script src="js/admin.js"></script>

    <!-- Demo Js -->
    <script src="js/demo.js"></script>
</body>

</html>
