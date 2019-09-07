<?php
    require('php-includes/connect.php');
    include('php-includes/check-login.php');
?>
<?php
    //pin_request
    if (isset($_GET['pin_request'])) {
        $amount = mysqli_real_escape_string($con,$_GET['amount']);
        $pack = mysqli_real_escape_string($con,$_GET['pack']);
        $date = date("y-m-d");
        $email = $_SESSION['user_id'];

        if ($amount!='' && $pack!='') {
        	if ($pack!='free') {
        		if ($amount<$pack) {
        			echo '<script>alert("Amount should not be less than pack value.");window.location.assign("pin-request.php");</script>';
        		}
        		else{
        			$query = mysqli_query($con,"insert into pin_request(email,amount,pack,date) values('$email','$amount','$pack','$date')");
        			if ($query) {
		                echo '<script>alert("Pin request sent successfully");window.location.assign("pin-request.php");</script>';
		            }
		            else{
		                echo mysqli_error($con);
		                echo '<script>alert("Unknown error occurred");window.location.assign("
		                pin-request.php");</script>';
		            }
        		}
        	}
        	else{
        		$query = mysqli_query($con,"insert into pin_request(email,amount,pack,date) values('$email','$amount','$pack','$date')");
        		if ($query) {
		                echo '<script>alert("Pin request sent successfully");window.location.assign("pin-request.php");</script>';
		            }
		            else{
		                echo mysqli_error($con);
		                echo '<script>alert("Unknown error occurred");window.location.assign("
		                pin-request.php");</script>';
		            }
        	}
            
            
        }
        else{
            echo '<script>alert("Please fill all the fields");</script>';
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Pin Request | WIDESKY E-RETAILS PVT. LTD.</title>
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
                <h1>PIN REQUEST</h1>
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <form method="get">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <div class="form-line">
                                        <input type="number" name="amount" class="form-control" required>
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <label>Package</label><br>
                                    <input type="radio" name="pack" id="free" value="free" class="with-gap">
                                    <label for="free">Free</label>
                                    <input type="radio" name="pack" id="4999" value="4999" class="with-gap">
                                    <label for="4999">4,999</label>
                                    <input type="radio" name="pack" id="9999" value="9999" class="with-gap">
                                    <label for="9999">9,999</label>
                                    <input type="radio" name="pack" id="39999" value="39999" class="with-gap">
                                    <label for="39999">39,999</label>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="pin_request" class="btn btn-primary" value ="Pin Request" \>
                                </div>
                                <div class="form-group">
                                    <p>*If you want to generate free pins then fill no. of pins in amount box.</p>
                                    <p>*Please make individual request for individual package pins.</p>
                                    <p>*Pins will be provided by admin after checking all the details.</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            YOUR PIN REQUEST HISTORY
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Amount</th>
                                            <th>Pack</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $i =1;
                                        $email = $_SESSION['user_id'];
                                        $query = mysqli_query($con,"select * from pin_request where email='$email' order by id desc");
                                        if (mysqli_num_rows($query)>0) {
                                            while ($row=mysqli_fetch_array($query)) {
                                                $amount = $row['amount'];
                                                $pack = $row['pack'];
                                                $date = $row['date'];
                                                $status = $row['status'];
                                    ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $amount; ?></td>
                                                    <td><?php echo $pack; ?></td>
                                                    <td><?php echo $date; ?></td>
                                                    <td><?php echo $status; ?></td>
                                                </tr>
                                     <?php
                                                $i++;
                                            }
                                        }
                                        else{
                                    ?>
                                        <tr>
                                            <td colspan="5">You have no pin requests yet.</td>
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
