<?php
    include('php-includes/check-login.php');
    require('php-includes/connect.php');
    $userid = $_SESSION['user_id'];
    $search = $userid;
    $level = 1;

if (isset($_GET['search-id'])) {
echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
    $search_id = "krishnasamidirect@gmail.com";
    #.............................................................................#

    $lvl1 = mysqli_query($con,"select * from user where sponsorid='$search_id'");
    $lvlr1=mysqli_fetch_array($lvl1);

    foreach ($lvlr1 as $id1) {
            $lvl2 = mysqli_query($con,"select * from user where sponsorid='$id1'");
            $lvlr2=mysqli_fetch_array($lvl2);
            echo $lvlr2['email'];
           }
    }
?>