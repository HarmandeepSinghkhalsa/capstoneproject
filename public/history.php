<?php
session_start();

//    $error = "";
//    $success="";

if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

    $_SESSION['id'] = $_COOKIE['id'];

}

if (array_key_exists("id", $_SESSION)) {
    include("connection.php");


    $query_booking = "SELECT * FROM `booking` WHERE user_id = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND status != 'Active' AND status != 'pending'";
    $query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";

    $result_booking = mysqli_query($link, $query_booking);
    $result_name = mysqli_query($link, $query_name);

    $check = mysqli_num_rows($result_booking);
    if($check < 1){
        $error .= "You don't have any booking history";
    }


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
        margin-bottom: 50px;
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



    <title> My Booking History</title>


    <nav class="navbar navbar-light bg-faded navbar-fixed-top">


        <a class="navbar-brand" href="#">My Booking History</a>

        <div class="pull-xs-right">
            <a href ='login.php?logout=1'>
                <button class="btn btn-success-outline" type="submit">Logout</button></a>
        </div>

        <div class="pull-xs-right">
            <a href ='index_test.php'>
                <button class="btn btn-success-outline" type="submit">Map</button></a>
        </div>

        <div class="pull-xs-right">
            <a href ='myBooking.php'>
                <button class="btn btn-success-outline" type="submit">Back</button></a>
        </div>

        <!--        <div class="pull-xs-right">-->
        <!--            <a href ='index_test.php'>-->
        <!--                <button class="btn btn-success-outline" type="submit">Return in map</button></a>-->
        <!--        </div>-->
    </nav>

    <?php $row = mysqli_fetch_assoc($result_name)?>
    <h4>Hello, <?php echo $row['first_name']; ?>!</h4>
    <br>

    <div id="error"><?php if ($error!="") {
            echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

        } ?></div>

    <div id="error"><?php if ($success!="") {
            echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

        } ?></div>

</div>




<table class="stack">
    <thead>
    <tr>

        <th>Make</th>
        <th>Model</th>
        <th>Registration number</th>
        <th>Pick up address</th>
        <th>pick up date</th>
        <th>Return date</th>
        <th>Rating</th>
        <th>Cost</th>
        <th>Status</th>

    </tr>
    </thead>
    <tbody>
    <!--Use a while loop to make a table row for every DB row-->
    <?php
    while($row = mysqli_fetch_assoc($result_booking)) : ?>
        <?php
        $rego = $row['vehicle_rego'];
        $query_vehicle = "SELECT * FROM `Vehicle` WHERE Registration = '".$rego."' ";
        $result_vehicle = mysqli_query($link, $query_vehicle);
        $row_vehicle = mysqli_fetch_assoc($result_vehicle)?>
        <tr>
            <!--Each table column is echoed in to a td cell-->
            <td><?php echo $row_vehicle['Make']; ?></td>
            <td><?php echo $row_vehicle['Model']; ?></td>
            <td><?php echo $row['vehicle_rego']; ?></td>
            <td><?php echo $row_vehicle['address']; ?></td>
            <td><?php echo $row['start']; ?></td>
            <td><?php echo $row['end']; ?></td>
            <td><?php echo $row_vehicle['average_rating']; ?>/5</td>
            <td>$<?php echo $row['price']; ?></td>
            <td><?php echo $row['status']; ?></td>

        </tr>
    <?php endwhile ?>
    </tbody>
</table>

<?php

include("footer.php");
?>
