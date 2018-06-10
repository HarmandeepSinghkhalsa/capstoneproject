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

echo"<br><br><br>";



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

                $id = $_SESSION['id'];
                $df = $_POST['dateFrom'];
                $dt = $_POST['dateTo'];
                $t = $_POST['time'];
                $query = "INSERT INTO `booking` (`user_id`,`start_date`, `end_date`,`time`) VALUES ('$id', '$df', '$dt', '$t')";
                $result = mysqli_query($link, $query);


            }
            }
    }
}else {
        header("Location: login.php");
    }
    include("header.php");

?>

<div id="error"><?php if ($error!="") {
        echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

    } ?></div>

<div id="error"><?php if ($success!="") {
        echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

    } ?></div>

<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="css/default.css">
<link rel="stylesheet" href="css/default.date.css">


<title>confirm booking</title>

<nav class="navbar navbar-light bg-faded navbar-fixed-top">

    <a class="navbar-brand" >Confirm booking</a>

    <div class="pull-xs-right">
        <a href ='login.php?logout=1'>
            <button class="btn btn-success-outline" type="submit">Logout</button></a>
    </div>

    <div class="pull-xs-right">
        <a href ='booking.php'>
            <button class="btn btn-success-outline" type="submit">Back</button></a>
    </div>
</nav>
<br>
<br>
            <div class="row">
                <div class="medium-12 large-12 columns">
                    <?php $row = mysqli_fetch_assoc($result_name)?>
                    <h4>Hello, <?php echo $row['first_name']; ?>!</h4>
                    <?php echo $row['email'];?>

                    <div class="medium-2  columns">BOOKING FOR:</div>
                    <br>
                    <br>
                    <?php $row_car_info = mysqli_fetch_assoc($result_table)?>
                    <div class="medium-2  columns">Make:</div>
                    <?php echo $row_car_info['markers_id'];?>
                    <div class="medium-1  columns"><b><?php echo $row_car_info['Make']; ?></b></div>
                    <br>
                    <br>
                    <div class="medium-2  columns">Model:</div>
					<div class="medium-1  columns"><b><?php echo $row_car_info['Model']; ?></b></div>
                    <br>
                    <br><div class="medium-2  columns">Booking Start date:</div>
					<div class="medium-1  columns"><b><?php echo $dateFrom; ?></b></div>
                    <br>
                    <br><div class="medium-2  columns">Booking Start time:</div>
					<div class="medium-1  columns"><b><?php echo $time; ?></b></div>
                    <br>
                    <br><div class="medium-2  columns">Booking End date:</div>
					<div class="medium-1  columns"><b><?php echo $dateTo; ?></b></div>
                    <br>
                    <br><div class="medium-2 columns">Damage Cover:</div>


                </div>
            </div>
<br>
<br>
<br>
<br>

<?php
include("footer.php");
?>
