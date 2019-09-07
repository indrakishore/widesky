<?php
    include('php-includes/check-login.php');
    require('php-includes/connect.php');
    $userid = $_SESSION['user_id'];
?>
<?php
    if (isset($_POST['change'])) {
        $password1 = mysqli_real_escape_string($con,$_POST['password1']);
        $password2 = mysqli_real_escape_string($con,$_POST['password2']);
        if ($password1==$password2) {
            mysqli_query($con,"update user set password='$password1' where email='$userid'");
            echo '<script>alert("Password changed successfully.");window.location.assign("home.php");</script>';
        }
        else{
             echo '<script>alert("Some error has occurred. Try Again.");</script>';
        }
    }
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Change Password | WIDESKY E-RETAILS PVT. LTD.</title>
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
                <h1>CHANGE PASSWORD</h1>
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-7 col-sm-10 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <p class="text-center">Use the form below to change your password. Your password cannot be the same as your username.</p>
                            <form method="post" id="passwordForm">
                                <input type="password" class="form-control" name="password1" id="password1" placeholder="New Password" autocomplete="off" required>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 Characters Long<br>
                                        <span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Uppercase Letter
                                    </div>
                                    <div class="col-sm-6">
                                        <span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Lowercase Letter<br>
                                        <span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Number
                                    </div>
                                </div>
                                    <input type="password" class="form-control" name="password2" id="password2" placeholder="Repeat Password" autocomplete="off" required>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Passwords Match
                                    </div>
                                </div>
                                <input type="submit" name="change" class="btn btn-primary btn-load btn-block" data-loading-text="Changing Password..." value="Change Password">
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

    <script>
        $("input[type=password]").keyup(function(){
            var ucase = new RegExp("[A-Z]+");
            var lcase = new RegExp("[a-z]+");
            var num = new RegExp("[0-9]+");
            
            if($("#password1").val().length >= 8){
                $("#8char").removeClass("glyphicon-remove");
                $("#8char").addClass("glyphicon-ok");
                $("#8char").css("color","#00A41E");
            }else{
                $("#8char").removeClass("glyphicon-ok");
                $("#8char").addClass("glyphicon-remove");
                $("#8char").css("color","#FF0004");
            }
            
            if(ucase.test($("#password1").val())){
                $("#ucase").removeClass("glyphicon-remove");
                $("#ucase").addClass("glyphicon-ok");
                $("#ucase").css("color","#00A41E");
            }else{
                $("#ucase").removeClass("glyphicon-ok");
                $("#ucase").addClass("glyphicon-remove");
                $("#ucase").css("color","#FF0004");
            }
            
            if(lcase.test($("#password1").val())){
                $("#lcase").removeClass("glyphicon-remove");
                $("#lcase").addClass("glyphicon-ok");
                $("#lcase").css("color","#00A41E");
            }else{
                $("#lcase").removeClass("glyphicon-ok");
                $("#lcase").addClass("glyphicon-remove");
                $("#lcase").css("color","#FF0004");
            }
            
            if(num.test($("#password1").val())){
                $("#num").removeClass("glyphicon-remove");
                $("#num").addClass("glyphicon-ok");
                $("#num").css("color","#00A41E");
            }else{
                $("#num").removeClass("glyphicon-ok");
                $("#num").addClass("glyphicon-remove");
                $("#num").css("color","#FF0004");
            }
            
            if($("#password1").val() == $("#password2").val()){
                $("#pwmatch").removeClass("glyphicon-remove");
                $("#pwmatch").addClass("glyphicon-ok");
                $("#pwmatch").css("color","#00A41E");
            }else{
                $("#pwmatch").removeClass("glyphicon-ok");
                $("#pwmatch").addClass("glyphicon-remove");
                $("#pwmatch").css("color","#FF0004");
            }
        });
    </script>
</body>

</html>
