<?php
	session_start();

//    $error = "";
//    $success="";

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

        $_SESSION['id'] = $_COOKIE['id'];

    }

    if (array_key_exists("id", $_SESSION)) {
      include("connection.php");


            $query_booking = "SELECT * FROM `booking` WHERE user_id = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND status = 'Active'";
            $query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";

             $result_booking = mysqli_query($link, $query_booking);
             $result_name = mysqli_query($link, $query_name);
             $result_r = mysqli_query($link, $query_booking);

             $vrego = mysqli_fetch_assoc($result_r);
             $r = $vrego['vehicle_rego'];


        $check = mysqli_num_rows($result_booking);
             if($check < 1){
                 $error .= "You don't have any existing booking";
             }else{
                 //current time and convert time to string
                 date_default_timezone_set('Australia/Melbourne');
                 $ct = date('Y-m-d H:i:s', time());
                 $ct_strtotime = strtotime("$ct");
                 //get db start and end datetime
                 $result_datetime = mysqli_query($link, $query_booking);
                 $datetime = mysqli_fetch_assoc($result_datetime);

                 $dbs = $datetime['start'];
                 $dbs_strtotime = strtotime("$dbs");

                 $dbe = $datetime['end'];
                 $dbe_strtotime = strtotime("$dbe");


                 if($ct < $dbs){
                     $difference = round(abs($dbs_strtotime - $ct_strtotime) / 60). " minutes";
                     if($difference < 30){
                         $success = $difference." to pick vehicle";
                     }

                 }elseif($ct > $dbe){
                     $difference = round(abs($dbe_strtotime - $ct_strtotime) / 60). " minutes" ;
                     $error = "Your rental is overdue by <br>".$difference." !<br>Please return vehicle ASAP!";

                 }else{
                     $difference = round(abs($dbe_strtotime - $ct_strtotime) / 60). " minutes";
                     if($difference < 30){
                         $error = $difference." left to return vehicle!";
                     }else{
                         $success = "It's your booking time!<br>Drive with care!";
                     }

                 }





             }


        if($_POST['submit'] == 'Cancel'){
                 $query_cancel = "UPDATE `booking` SET `status` = 'cancelled' WHERE user_id = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND status = 'Active'";
                 $result_cancel = mysqli_query($link, $query_cancel);
                $query_update_ava = "UPDATE `vehicle_at_markers` SET `Available` = '1' WHERE Registration = '$r'";
                $result_update_ava = mysqli_query($link, $query_update_ava);

                $success .= 'Cancel Vehicle Success!<br>Refresh page in 3 seconds';
                 header("Refresh:3");
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



    <title> My Bookings</title>


    <nav class="navbar navbar-light bg-faded navbar-fixed-top">


        <a class="navbar-brand" href="#">My Bookings</a>

        <div class="pull-xs-right">
            <a href ='login.php?logout=1'>
                <button class="btn btn-success-outline" type="submit">Logout</button></a>
        </div>

        <div class="pull-xs-right">
            <a href ='history.php'>
                <button class="btn btn-success-outline" type="submit">Booking History</button></a>
        </div>

        <div class="pull-xs-right">
            <a href ='index_test.php'>
                <button class="btn btn-success-outline" type="submit">Map</button></a>
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



<form method="post">
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
        <th>Action</th>

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
            <td><?php echo $row_vehicle['average_rating']; ?></td>
            <td>$<?php echo $row['price']; ?></td>

            <td><input  class="btn btn-success" type="submit" name="submit" value="Cancel"></td>
        </tr>
    <?php endwhile ?>
    </tbody>

</table>
</form>


    <?php

    include("footer.php");
    ?>
