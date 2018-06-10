<?php
session_start();

    $error = "";
    $success="";

//the return address that user clicked
//$address = $_GET['submit'];
$vamid = $_GET['vamID'];

//if (!isset($address)){
//    echo"<br><br><br>";
//    echo "return address is not recorded" ;
//}else {
//    include("connection.php");
//
//    echo"<br><br><br>";
//    echo "vehicle booking info recorded : " ;
//    echo $address;
//    echo "<br>";
//
//}

if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

    $_SESSION['id'] = $_COOKIE['id'];

}

if (array_key_exists("id", $_SESSION)) {
    include("connection.php");
    //query for using vamID to get address picked
    $query_address = "SELECT * FROM `vehicle_at_markers` WHERE id = '$vamid'";
    $result_address = mysqli_query($link, $query_address);
    $row_address= mysqli_fetch_assoc($result_address);
    $address = $row_address['address'];

    //query for user name
    $query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
    $result_name = mysqli_query($link, $query_name);

    //get vehicle registration
    $query_rego = "SELECT * FROM `booking` WHERE user_id = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND status = 'Active' LIMIT 1";
    $result_rego = mysqli_query($link, $query_rego);
    $row_rego = mysqli_fetch_assoc($result_rego);
    $check = mysqli_num_rows($result_rego);

    if($check > 0){
    //success box
    $success .= "Vehicle return success!<br>Current address: $address  ";
    }else{
        if ($_POST['rate'] == '1') {
            $query_rate_user = "SELECT * FROM `booking` WHERE user_id = " . mysqli_real_escape_string($link, $_SESSION['id']) . " order BY booking_id DESC LIMIT 1";
            $result_rate_user = mysqli_query($link, $query_rate_user);
            $row_rate = mysqli_fetch_assoc($result_rate_user);
            $rt = $_POST['rate_selection'];
            $bid = $row_rate['booking_id'];
            $registration = $row_rate['vehicle_rego'];

            $query_rate = "UPDATE `booking` SET `rating` = $rt WHERE booking_id = $bid AND rating is NULL";
            $result_rate = mysqli_query($link, $query_rate);
            $success .= 'Rating success!<br>You rated: ' . $_POST['rate_selection'] . '<br>Refresh page in 3 seconds';
//            echo '<br><br><br>';
//            echo $registration;
            $query_average = "SELECT AVG(rating) FROM booking WHERE status='returned' AND vehicle_rego= '$registration'";
            $result_average = mysqli_query($link, $query_average);
            $row_new = mysqli_fetch_assoc($result_average);
            $average = $row_new['AVG(rating)'];

            $query_update_average = "UPDATE `Vehicle` SET `average_rating` = $average WHERE Registration ='$registration'";
            $result_update_average = mysqli_query($link, $query_update_average);

            header("Refresh:3");
//        echo '<br><br><br><br>';
//        echo 'pressed: ';
//        echo $_POST['rate_selection'];
//        echo '<br>';
//        echo 'user_id: ';
//        echo  $_SESSION['id'];
//        echo '<br>';
//        echo 'current bid: ';
//        echo $bid;
        }else{
            //error box
            $error .= "You don't have any active booking!";
        }
    }

    $rego = $row_rego['vehicle_rego'];

//    echo $rego;

    //update vehicle address
    $query_update_address = "UPDATE `Vehicle` SET `address` = '$address' WHERE Vehicle.Registration = '$rego'";
    $result_update_address = mysqli_query($link, $query_update_address);
    $row_update_address = mysqli_fetch_assoc($result_update_address);

    //remove user from booking (will do change status later -->users are able to see booking history)
    $query_update_status = "UPDATE `booking` SET `status` = 'returned' WHERE vehicle_rego = '$rego'";
    $result_update_status = mysqli_query($link, $query_update_status);

    //update vehicle_at_markers table
    $query_del = "TRUNCATE vehicle_at_markers";

    //$query_del = "Delete from vehicle_at_markers";

    $query_same = "INSERT INTO vehicle_at_markers (markers_id, markers_name, address, lat, lng, type,Registration,Make,Model,Available,Seats,Description,vehicle_rating)
                        SELECT markers.markers_id, markers.markers_name, markers.address , markers.lat, markers.lng, markers.type,Vehicle.Registration, Vehicle.Make, Vehicle.Model, Vehicle.Available, Vehicle.Seats, Vehicle.Description, Vehicle.average_rating 
                        FROM markers 
                        JOIN Vehicle 
                        ON Vehicle.address = markers.address;";

    $query_different = "INSERT INTO vehicle_at_markers (markers_id, markers_name, address, lat, lng, type) 
                            SELECT markers.markers_id, markers.markers_name, markers.address, markers.lat, markers.lng, markers.type 
                            FROM markers 
                            WHERE NOT EXISTS(SELECT markers_id 
                                              FROM vehicle_at_markers 
                                              WHERE vehicle_at_markers.markers_id = markers.markers_id) ";

    $query_index = "alter table vehicle_at_markers AUTO_INCREMENT=1;";

    $result_del = mysqli_query($link, $query_del);
    $result_same = mysqli_query($link, $query_same);
    $result_different = mysqli_query($link, $query_different);
    $result_index = mysqli_query($link, $query_index);
    //update available to 1
    $query_update_ava = "UPDATE `vehicle_at_markers` SET `Available` = '1' WHERE Registration = '$rego'";
    $result_update_ava = mysqli_query($link, $query_update_ava);

}else {
    header("Location: login.php");
}
include("header.php");
?>

<style>
    html {
        background: url(mybooking.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }

    table, td, th {
        border: 1px solid #66af6f;
    }

    table {
        border-collapse: collapse;
        margin: 0px auto;
        width: 75%;
    }

    td {
        text-align: center;
        padding: 5px;
        width: 75px;
        color: #444444;
    }

    th {
        background-color: #66af6f;
        text-align: center;
        padding: 5px;
        color: #444444;
    }

    h4 {
        color: #444444;
    }

</style>

<div class="container" id="homePageContainer">

    <title> Return success</title>
    <nav class="navbar navbar-light bg-faded navbar-fixed-top">
        <a class="navbar-brand">Return success</a>
        <div class="pull-xs-right">
            <a href ='login.php?logout=1'>
                <button class="btn btn-success-outline" type="submit">Logout</button>
            </a>
        </div>
        <div class="pull-xs-right">
            <a href ='index_test.php'>
                <button class="btn btn-success-outline" type="submit">Map</button>
            </a>
        </div>
    </nav>

    <?php $row = mysqli_fetch_assoc($result_name)?>
    <h4>Thank you, <?php echo $row['first_name']; ?>!</h4>
    <?php echo '<br>'?>

    <div id="error"><?php if ($error!="") {
        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
    } ?>
    </div>

    <div id="error"><?php if ($success!="") {
            echo '<div class="alert alert-success" role="alert">'.$success.'</div>';
    } ?>
    </div>


    <?php
    if($error != "You don't have any active booking!"){
        echo '    
    <p style="color:#444444;"><strong>please rate for the vehicle</strong></p>
    <form method="post">
        <select style="color: black" name="rate_selection">
            <option name="11" value="5" selected="selected">rate out of 5</option>
            <option name="0" value="0">0</option>
            <option name="1" value="1">1</option>
            <option name="2" value="2">2</option>
            <option name="3" value="3">3</option>
            <option name="4" value="4">4</option>
            <option name="5" value="5">5</option>
        </select>

        <div class="medium-2  columns">
            <br>

            <input type="hidden" name="rate" value="1">
            <input class="btn btn-success" type="submit" name="submit_rating" value="submit rating">
        </div>
    </form>
    ';
    }else{
        echo '    <p style="color:#444444;"><strong>Start a new booking</strong></p>
    <form  action="index_test.php">
        <input class="btn btn-success" type="submit" name="submit" value="Book">
    </form>';
    }

    ?>

</div>

<?php
include("footer.php");
?>
