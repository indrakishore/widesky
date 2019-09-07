<?php
    include('php-includes/connect.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\PHPException;

    /* Exception class. */
    require 'PHPMailer\src\Exception.php';

    /* The main PHPMailer class. */
    require 'PHPMailer\src\PHPMailer.php';

    /* SMTP class, needed if you want to use SMTP. */
    require 'PHPMailer\src\SMTP.php';
?>
<?php
    if (isset($_POST['recover-submit'])) {
        $email = mysqli_real_escape_string($con,$_POST['email']);
        $query = mysqli_query($con,"select * from user where email = '$email'");
        $count = mysqli_num_rows($query);
        $result = mysqli_fetch_array($query);
        $password = $result['password'];
        if ($count == 1) {
            $link = "http://widesky.online/login";
            $mail = new PHPMailer(TRUE);
            try{
                $mail->setFrom('admin@widesky.online', 'WIDESKY E-RETAILS PVT. LTD.');
                $mail->addAddress($email);
                $mail->Subject = "Your Recovered Password | Members Area | WIDESKY E-RETAILS PVT. LTD.";
                $mail->isHTML(TRUE);
                $mail->isSMTP();
                $mail->Host = "mail.widesky.online";
                // optional
                // used only when SMTP requires authentication  
                $mail->SMTPAuth = true;
                $mail->Username = 'admin@widesky.online';
                $mail->Password = 'wideskyadmin@007#';
                $mail->Body = "<h1>Members Area | WIDESKY E-RETAILS PVT. LTD.</h1>
                <h3>Password Recovery</h3>
                Please use this password for login: <strong>".$password."</strong><br>Go to Members Area WIDESKY E-RETAILS PVT. LTD.: ".$link."
                <p>For any queries please call us at: +91 7452000510</p>
                ";
                if($mail->send()){
                    echo '<script>alert("Your password has been send to your email id.");window.location.assign("index.php");</script>';
                }
                else {
                    echo '<script>alert("Fail to recover your password, try again.");</script>';
                }
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

        }
        else{
            echo '<script>alert("Email is incorrect.");</script>';
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Forgot Password | WIDESKY E-RETAILS PVT. LTD.</title>
    <!-- Favicon-->
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

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
</head>

<body class="fp-page">
    <div class="fp-box">
        <div class="logo">
            <a href="javascript:void(0);"><small><b>WideSky E-Retails Pvt. Ltd.</b></small></a>
            <small>Members Area</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="forgot_password" method="POST">
                    <div class="msg">
                        Enter your email address that you used to register. We'll send you an email with your username and your password. WD
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" placeholder="Email" required autofocus>
                        </div>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" name="recover-submit" type="submit">GET PASSWORD</button>

                    <div class="row m-t-20 m-b--5 align-center">
                        <a href="index.php">Sign In!</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/examples/forgot-password.js"></script>
</body>

</html>