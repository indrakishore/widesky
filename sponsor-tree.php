<?php
    include('php-includes/check-login.php');
    require('php-includes/connect.php');
    $userid = $_SESSION['user_id'];
    $search = $userid;
    $level = 1;
?>
<?php
if (isset($_GET['search-id'])) {

    $search_id = mysqli_real_escape_string($con,$_GET['search-id']);
    $level = mysqli_real_escape_string($con,$_GET['level']);
    $level++;
    if ($search_id!='') {
        $query_check= mysqli_query($con,"select * from user where email='$search_id'");
        if (mysqli_num_rows($query_check)>0) {
            $search = $search_id;
        }
        else{
            echo '<script>alert("Access Denied");window.location.assign("sponsor-tree.php")</script>';
        }
    }
    else{
        echo '<script>alert("Access Denied");window.location.assign("sponsor-tree.php")</script>';
    }  
}
?>
<?php
    function getSponsoredUsers($userid){
        $i=0;
        $query = mysqli_query($con,"select * from user where sponsorid='$userid'");
        while ($row=mysqli_fetch_array($query)) {
            $i++;
            $data[$i] = $row['email'];
        }
    }
?>
<?php
            $i=0;$j=0;$k=0;$l=0;$m=0;$n=0;$o=0;$p=0;$q=0;$r=0;$s=0;$t=0;$u=0;$v=0;$w=0;$x=0;$y=0;
            $tbv1=0; $tbv2=0; $tbv3=0; $tbv4=0; $tbv5=0; $tbv6=0; $tbv7=0; $tbv8=0;
            $tbv9=0; $tbv10=0; $tbv11=0; $tbv12=0; $tbv13=0; $tbv14=0; $tbv15=0;
            #.............................................................................#

            $lvl0 = mysqli_query($con,"select lv0 from level_data where userid='$search'");
            $re = mysqli_fetch_array($lvl0);
            $tbv0 = $re['lv0'];

            $lvl1 = mysqli_query($con,"select * from user where sponsorid='$search'");    
            while ($lvlr1=mysqli_fetch_array($lvl1)) {
                $id = $lvlr1['email'];
                $bv = $lvlr1['bv'];
                $tbv1 = $tbv1 + $bv;
                                    
                $lvl2 = mysqli_query($con,"select * from user where sponsorid='$id'");
                while ($lvlr2=mysqli_fetch_array($lvl2)) {
                    $id = $lvlr2['email'];
                    $bv = $lvlr2['bv'];
                    $tbv2 = $tbv2 + $bv;

                    $lvl3 = mysqli_query($con,"select * from user where sponsorid='$id'");
                    while ($lvlr3=mysqli_fetch_array($lvl3)) {
                        $id = $lvlr3['email'];
                        $bv = $lvlr3['bv'];
                        $tbv3 = $tbv3 + $bv;
                                  
                        $lvl4 = mysqli_query($con,"select * from user where sponsorid='$id'");
                        while ($lvlr4=mysqli_fetch_array($lvl4)) {
                            $id = $lvlr4['email'];
                            $bv = $lvlr4['bv'];
                            $tbv4 = $tbv4 + $bv;
                                  
                            $lvl5 = mysqli_query($con,"select * from user where sponsorid='$id'");
                            while ($lvlr5=mysqli_fetch_array($lvl5)) {
                                $id = $lvlr5['email'];
                                $bv = $lvlr5['bv'];
                                $tbv5 = $tbv5 + $bv;
                                    
                                $lvl6 = mysqli_query($con,"select * from user where sponsorid='$id'");
                                while ($lvlr6=mysqli_fetch_array($lvl6)) {
                                    $id = $lvlr6['email'];
                                    $bv = $lvlr6['bv'];
                                    $tbv6 = $tbv6 + $bv;

                                    $lvl7 = mysqli_query($con,"select * from user where sponsorid='$id'");
                                    while ($lvlr7=mysqli_fetch_array($lvl7)) {
                                        $id = $lvlr7['email'];
                                        $bv = $lvlr7['bv'];
                                        $tbv7 = $tbv7 + $bv;

                                        $lvl8 = mysqli_query($con,"select * from user where sponsorid='$id'");
                                        while ($lvlr8=mysqli_fetch_array($lvl8)) {
                                            $id = $lvlr8['email'];
                                            $bv = $lvlr8['bv'];
                                            $tbv8 = $tbv8 + $bv;

                                            $lvl9 = mysqli_query($con,"select * from user where sponsorid='$id'");
                                            while ($lvlr9=mysqli_fetch_array($lvl9)) {
                                                $id = $lvlr9['email'];
                                                $bv = $lvlr9['bv'];
                                                $tbv9 = $tbv9 + $bv;

                                                $lvl10 = mysqli_query($con,"select * from user where sponsorid='$id'");
                                                while ($lvlr10=mysqli_fetch_array($lvl10)) {
                                                    $id = $lvlr10['email'];
                                                    $bv = $lvlr10['bv'];
                                                    $tbv10 = $tbv10 + $bv;

                                                    $lvl11 = mysqli_query($con,"select * from user where sponsorid='$id'");
                                                    while ($lvlr11=mysqli_fetch_array($lvl11)) {
                                                        $id = $lvlr11['email'];
                                                        $bv = $lvlr11['bv'];
                                                        $tbv11 = $tbv11 + $bv;

                                                        $lvl12 = mysqli_query($con,"select * from user where sponsorid='$id'");
                                                        while ($lvlr12=mysqli_fetch_array($lvl12)) {
                                                            $id = $lvlr12['email'];
                                                            $bv = $lvlr12['bv'];
                                                            $tbv12 = $tbv12 + $bv;

                                                            $lvl13 = mysqli_query($con,"select * from user where sponsorid='$id'");
                                                            while ($lvlr13=mysqli_fetch_array($lvl13)) {
                                                                $id = $lvlr13['email'];
                                                                $bv = $lvlr13['bv'];
                                                                $tbv13 = $tbv13 + $bv;

                                                                $lvl14 = mysqli_query($con,"select * from user where sponsorid='$id'");
                                                                while ($lvlr14=mysqli_fetch_array($lvl14)) {
                                                                    $id = $lvlr14['email'];
                                                                    $bv = $lvlr14['bv'];
                                                                    $tbv14 = $tbv14 + $bv;

                                                                    $lvl15 = mysqli_query($con,"select * from user where sponsorid='$id'");
                                                                    while ($lvlr15=mysqli_fetch_array($lvl15)) {
                                                                        $id = $lvlr15['email'];
                                                                        $bv = $lvlr15['bv'];
                                                                        $tbv15 = $tbv15 + $bv;
                                                                        $y++;
                                                                    }   
                                                                    $x++;
                                                                }    
                                                                $w++;
                                                            }    
                                                            $v++;
                                                        }    
                                                        $u++;
                                                    }    
                                                    $t++;
                                                }    
                                                $r++;
                                            }    
                                            $q++;
                                        }    
                                        $p++;
                                    }    
                                    $o++;
                                }
                                $m++;
                            }
                            $l++; 
                        }
                        $k++;
                    }
                    $j++;
                }
                $i++;
            }
            $totalbv = $tbv0+$tbv1+$tbv2+$tbv3+$tbv4+$tbv5+$tbv6+$tbv7+$tbv8+$tbv9+$tbv10+$tbv11+$tbv12+$tbv13+$tbv14+$tbv15;
            $income = ($tbv0*5)/100 + ($tbv1*5)/100 + ($tbv2*3)/100 + ($tbv3*3)/100 + ($tbv4*2)/100 + ($tbv5*1)/100 + ($tbv6*1)/100 + ($tbv7*1)/100 + ($tbv8*0.5)/100 + ($tbv9*0.5)/100 + ($tbv10*0.5)/100 + ($tbv11*0.5)/100 + ($tbv12*0.5)/100 + ($tbv13*0.5)/100 + ($tbv14*0.5)/100 + ($tbv15*0.5)/100;
            $total_users = $i+$j+$k+$l+$m+$o+$p+$q+$r+$t+$u+$v+$w+$x+$y;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sponsor Tree | WIDESKY E-RETAILS PVT. LTD.</title>
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
                <h1>SPONSOR TREE</h1>
            </div>
            <div class="row clearfix">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header" align="center">
                            <?php
                                $query = mysqli_query($con,"select * from user where email='$search'");
                                $result = mysqli_fetch_array($query);
                                $name = $result['name'];
                                $bv = $result['bv'];
                            ?>
                            <p class="form-control"><strong>Name: </strong><?php echo $name; ?><strong> UserID: </strong><?php echo $search; ?><strong> BV: </strong><?php echo $bv; ?></p>
                            </div>
                        <div class="body">
                            <h4 class="page-header" align="center"><strong>Level: <?php echo $level; ?></strong></h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" align="center">
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>User ID</th>
                                        <?php
                                        if ($level == 1) {
                                        ?>
                                        <th>Mobile</th>
                                        <?php
                                        }
                                        ?>
                                        <th>Rank</th>
                                        <th>BV</th>
                                        <?php
                                            $a=1;
                                            $query = mysqli_query($con,"select * from user where sponsorid='$search'");
                                            $total_bv = 0;
                                            if (mysqli_num_rows($query)>0) {
                                                while ($row=mysqli_fetch_array($query)) {
                                                    $name = $row['name'];
                                                    $user = $row['email'];
                                                    $rank = $row['rank'];
                                                    $mobile = $row['mobile'];
                                                    $bv = $row['bv'];
                                                    $total_bv = $total_bv + $bv;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $a;?></td>
                                                        <td><?php echo $name;?></td>
                                                        <td><a href="sponsor-tree.php?search-id=<?php echo $user?>&search=Search&level=<?php echo $level; ?>"><?php echo $user;  ?></a></td>
                                                        <?php
                                                        if ($level == 1) {
                                                        	?>
                                                        	<td><?php echo $mobile;?></td>
                                                        	<?php
                                        	
				                                        }
				                                        ?>
                                                        <td><?php echo $rank;?></td>
                                                        <td><?php echo $bv;?></td>
                                                    </tr>

                                                    <?php
                                                    $a++;
                                                }
                                                ?>
                                                <tr>
                                                    <th colspan="4">Total BV:</th>
                                                    <th colspan="1"><?php echo $total_bv;?></th>
                                                </tr>
                                                <?php
                                            }
                                            else{
                                            ?>
                                                <tr>
                                                	<?php
                                                	if ($level == 1) {
                                                		?>
                                                		<td colspan="6">Sorry, <?php echo $name; ?> have not sponsored anyone yet.</td>
                                                		<?php
                                                	}
                                                	else{
                                                	?>
                                                    <td colspan="5">Sorry, <?php echo $name; ?> have not sponsored anyone yet.</td>
                                                    <?php
                                                	}
                                                    ?>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                    <div class="info-box-2 bg-orange hover-zoom-effect">
                        <div class="icon">
                            <i class="fa fa-rupee"></i>
                        </div>
                        <div class="content">
                            <div class="text">PURCHASE INCOME(monthly)</div>
                            <div class="number">
                                <?php
                                    $query = mysqli_query($con,"select * from purchase_income where userid='$search'");
                                    $result = mysqli_fetch_array($query);
                                    echo $result['total_income'];
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="info-box-2 bg-cyan hover-zoom-effect">
                        <div class="icon">
                            <i class="fa fa-adjust"></i>
                        </div>
                        <div class="content">
                            <div class="text"><b>BV (Lifetime)</b></div>
                            <div class="number">
                                <?php
                                    $query = mysqli_query($con,"select * from level_data_lifetime where userid='$search'");
                                    $result = mysqli_fetch_array($query);
                                    echo $result['lifetime_bv'];
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                            PURCHASE INCOME
                        </div>
                        <div class="body">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>Level</th>
                                    <th>Total Users</th>
                                    <th>Total BV</th>
                                    <th>Income</th>
                                </tr>
                                <tr>
                                    <td>0</td>
                                    <td><?php echo "You"; ?></td>
                                    <td><?php echo $tbv0; ?></td>
                                    <td><?php echo ($tbv0*5)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $tbv1; ?></td>
                                    <td><?php echo ($tbv1*5)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $tbv2; ?></td>
                                    <td><?php echo ($tbv2*3)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td><?php echo $k; ?></td>
                                    <td><?php echo $tbv3; ?></td>
                                    <td><?php echo ($tbv3*3)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td><?php echo $l; ?></td>
                                    <td><?php echo $tbv4; ?></td>
                                    <td><?php echo ($tbv4*2)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td><?php echo $m; ?></td>
                                    <td><?php echo $tbv5; ?></td>
                                    <td><?php echo ($tbv5*1)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td><?php echo $o; ?></td>
                                    <td><?php echo $tbv6; ?></td>
                                    <td><?php echo ($tbv6*1)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td><?php echo $p; ?></td>
                                    <td><?php echo $tbv7; ?></td>
                                    <td><?php echo ($tbv7*1)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td><?php echo $q; ?></td>
                                    <td><?php echo $tbv8; ?></td>
                                    <td><?php echo ($tbv8*0.5)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td><?php echo $r; ?></td>
                                    <td><?php echo $tbv9; ?></td>
                                    <td><?php echo ($tbv9*0.5)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td><?php echo $t; ?></td>
                                    <td><?php echo $tbv10; ?></td>
                                    <td><?php echo ($tbv10*0.5)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td><?php echo $u; ?></td>
                                    <td><?php echo $tbv11; ?></td>
                                    <td><?php echo ($tbv11*0.5)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td><?php echo $v; ?></td>
                                    <td><?php echo $tbv12; ?></td>
                                    <td><?php echo ($tbv12*0.5)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>13</td>
                                    <td><?php echo $w; ?></td>
                                    <td><?php echo $tbv13; ?></td>
                                    <td><?php echo ($tbv13*0.5)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>14</td>
                                    <td><?php echo $x; ?></td>
                                    <td><?php echo $tbv14; ?></td>
                                    <td><?php echo ($tbv14*0.5)/100; ?></td>
                                </tr>
                                <tr>
                                    <td>15</td>
                                    <td><?php echo $y; ?></td>
                                    <td><?php echo $tbv15; ?></td>
                                    <td><?php echo ($tbv15*0.5)/100; ?></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <th><?php echo $total_users; ?></th>
                                    <th><?php echo $totalbv; ?></th>
                                    <th><?php echo $income; ?></th>
                                </tr>
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
</body>

</html>
