<?php

session_start();

$error = "";
$success= "";

if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

    $_SESSION['id'] = $_COOKIE['id'];

}

if (array_key_exists("Registration", $_COOKIE) && $_COOKIE ['Registration']) {

    $_SESSION['Registration'] = $_COOKIE['Registration'];

}

//get vehicle_at_markers id
$vamId = $_GET['vamId'];

//check user info session
echo"<br><br>";
echo '================================================';

if (!isset($vamId)){
    echo"<br>";
    echo "vehicle booking info is not recorded" ;
}else {
    include("connection.php");
    $query_vam = "SELECT * FROM `vehicle_at_markers` WHERE id = $vamId";
    $result_vam = mysqli_query($link, $query_vam);


    $result_rego = mysqli_query($link, $query_vam);

    echo"<br>";
    echo "vehicle booking info recorded, vehicle_at_markers id is: " ;
    echo $vamId;

}


if (!isset($_SESSION['id'])){
    echo"<br>";
    echo "session for user info is not saved" ;
}else{

    echo"<br>";
    echo "session for user info is saved, user ID is: ";
    echo $_SESSION['id'];
}
echo '<br>================================================';

if (array_key_exists("id", $_SESSION)) {

    include("connection.php");
    //query for user name
    $query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
    $result_name = mysqli_query($link, $query_name);

    if (array_key_exists("submit", $_POST)){

        if (!$_POST['dateFrom']) {
            $error .= "You have to select a start date<br>";
        }
        if (!$_POST['dateTo']) {
            $error .= "You have to select a end date<br>";
        }
        if (!$_POST['time']) {
            $error .= "You have to select a time<br>";
        }
        if ($error != "") {
            $error = "<p>There were error(s) in your form:</p>".$error;
        } else{

            if($_POST['search'] == '1'){

                $rego = mysqli_fetch_assoc($result_rego);


                echo '<br>';
                echo 'Booking info has stored to booking database: <br>';

                echo 'User ID: ';
                echo $_SESSION['id'];
                echo '<br>From: ';
                echo $_POST['dateFrom'];
                echo '<br>To: ';
                echo $_POST['dateTo'];
                echo '<br>Time: ';
                echo $_POST['time'];
                echo '<br>Rego: ';
                echo $rego['Registration'];
                echo '<br>';

                $id = $_SESSION['id'];
                $df = $_POST['dateFrom'];
                $dt = $_POST['dateTo'];
                $t = $_POST['time'];
                $r = $rego['Registration'];
                $query = "INSERT INTO `booking` (`user_id`,`vehicle_rego`,`start_date`, `end_date`,`time`) VALUES ('$id','$r', '$df', '$dt', '$t')";
                $result = mysqli_query($link, $query);
                $success .= "Booking Success! <br>";


            }
        }
    }
}else {
    header("Location: login.php");
}
include("header.php");

?>













<?php
include("footer.php");
?>
