

<?php

session_start();



$error = "";
$success= "";

if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {
    $_SESSION['id'] = $_COOKIE['id'];
}

if (array_key_exists("id", $_SESSION)) {
    include("connection.php");
    //query for user name
    $query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
    $result_name = mysqli_query($link, $query_name);

    $query_check = $query_name = "SELECT * FROM `booking` WHERE user_id = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND status = 'pending' ORDER BY booking_id DESC";
    $result_check = mysqli_query($link, $query_check);
    //check if pending > 1
    $check = mysqli_num_rows($result_check);
    $row_booking_id = mysqli_fetch_assoc($result_check);
    $bid = $row_booking_id['booking_id'];
    if($check > 1){
        $query_delete = "DELETE FROM `booking` WHERE booking_id != '$bid' AND status = 'pending'";
        $result_delete = mysqli_query($link, $query_delete);
    }
    //update status
    $query_active = "UPDATE `booking` SET `status` = 'Active' WHERE user_id = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND status = 'pending'";
    $result_active = mysqli_query($link, $query_active);
    $row_active = mysqli_fetch_assoc($result_active);

    //update payment
    $query_price = "UPDATE `booking` SET `price` = '49.90' WHERE user_id = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND status = 'Active'";
    $result_price = mysqli_query($link, $query_price);

    //get rego and booking times
    $query_rego = "SELECT * FROM `booking` WHERE user_id = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND status = 'Active' LIMIT 1";
    $result_rego = mysqli_query($link, $query_rego);
    $row_booking = mysqli_fetch_assoc($result_rego);

    $rego = $row_booking['vehicle_rego'];
    $from = $row_booking['start'];
    $to = $row_booking['end'];


    $query_vinfo = "SELECT * FROM `Vehicle` WHERE Registration = '$rego' LIMIT 1";
    $result_vinfo = mysqli_query($link, $query_vinfo);

    $success = 'booking success!<br>
                You paid $49.90<br>
                Go to my booking:<br><br>
                <form  action="myBooking.php">
                    <input class="btn btn-success" type="submit" name="submit" value="My booking">
                </form>';


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
        background-color: #ddd;
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

<title>booking</title>

<div class="container" id="homePageContainer">

    <nav class="navbar navbar-light bg-faded navbar-fixed-top">

        <a class="navbar-brand" >Booking vehicle</a>

        <div class="pull-xs-right">
            <a href ="index_test.php?logout=1">
                <button class="btn btn-success-outline" type="submit">Logout</button></a>
        </div>

        <div class="pull-xs-right">
            <a href ="myBooking.php">
                <button class="btn btn-success-outline" type="submit">My Booking</button></a>
        </div>

        <div class="pull-xs-right">
            <a href ="index_test.php">
                <button class="btn btn-success-outline" type="submit">Map</button></a>
        </div>
    </nav>

    <?php $row = mysqli_fetch_assoc($result_name)?>
    <h4>Thank you, <?php echo $row['first_name']; ?>!</h4>

    <div id="error"><?php if ($error!="") {
        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
    } ?>
    </div>

    <div id="error"><?php if ($success!="") {
        echo '<div class="alert alert-success" role="alert">'.$success.'</div>';
    } ?>
    </div>
</div>

<form method="post">
    <table class="stack">
        <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Pick up address</th>
            <th>pick up date</th>
            <th>Return date</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $row_vi = mysqli_fetch_assoc($result_vinfo)?>
        <tr>
            <td><?php echo $row_vi['Make']; ?></td>
            <td><?php echo $row_vi['Model']; ?></td>
            <td><?php echo $row_vi['address']; ?></td>
            <td><?php echo $from; ?></td>
            <td><?php echo $to; ?></td>
        </tr>
        </tbody>
    </table>
</form>


<?php

include("footer.php");
?>





