

<?php

session_start();

$stripe = [
    'publishable' => 'pk_test_rF5akTBZXbeeCejFj0ny58hm',
    'private' => 'sk_test_AfLKvJ1Jj1SV45TXlNilhR5f'
];

$error = "";
$success= "";

if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {
    $_SESSION['id'] = $_COOKIE['id'];
}

$vamId = $_GET['vamId'];

//check user info session
//echo"<br><br>";
//echo '================================================';
if (!isset($vamId)){
//    echo"<br>";
    echo "vehicle booking info is not recorded" ;
}else {
    include("connection.php");
    $query_vam = "SELECT * FROM `vehicle_at_markers` WHERE id = $vamId";
    $result_vam = mysqli_query($link, $query_vam);
    $result_rego_check = mysqli_query($link, $query_vam);
    $result_rego = mysqli_query($link, $query_vam);

//    echo"<br>";
//    echo "vehicle booking info recorded, vehicle_at_markers id is: " ;
//    echo $vamId;
}

if (!isset($_SESSION['id'])){
//    echo"<br>";
    echo "session for user info is not saved" ;
}else{
//    echo"<br>";
//    echo "session for user info is saved, user ID is: ";
//    echo $_SESSION['id'];
}
//echo '<br>================================================';

if (array_key_exists("id", $_SESSION)) {
    include("connection.php");
    //query for user name
    $query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
    $result_name = mysqli_query($link, $query_name);
    if (array_key_exists("submit", $_POST)) {
//        if($_POST['from']){
//            $selected = $_POST['from'];
//        }
        if ($_POST['book'] == '1') {
            //check if user has exist booking
            $query_check = "SELECT * FROM `booking` WHERE user_id = " . mysqli_real_escape_string($link, $_SESSION['id']) . " AND status = 'Active'";
            $result_check = mysqli_query($link, $query_check);
            $check = mysqli_num_rows($result_check);


            if ($check > 0) {
                //error message for existing booking
                $error .= "You have an exist booking! <br>
                           View your booking:<br><br>
                           <form action='myBooking.php' >
                                <input style='font-size: 15px;' class='btn btn-success' type='submit' name='mb' value='My Booking'>
                           </form>";
            } else {
                if (!$_POST['from']) {
                    $error .= "You have to select a start date<br>";
                }
                if (!$_POST['to']) {
                    $error .= "You have to select a end date<br>";
                }

                if ($error != "") {
                    $error = "<p>There were error(s) in your form:</p>" . $error;
                } else {
                    //check if selected vehicle exist in booking db
                    //get for rego
                    $check_rego = mysqli_fetch_assoc($result_rego_check);
                    $get_rego = $check_rego['Registration'];
//                    echo '<br><br><br><br>Registration: ';
//                    echo $get_rego;
                    //check rows
                    $query_check_vehicle = "SELECT * FROM `booking` WHERE vehicle_rego = '$get_rego' AND status = 'Active'";
                    $result_check_vehicle = mysqli_query($link, $query_check_vehicle);
                    $check_vehicle = mysqli_num_rows($result_check_vehicle);
//                    echo '<br>rows exist: ';
//                    echo $check_vehicle;
//                    echo '<br>';

//                    $query_check_vam = "SELECT * FROM `vehicle_at_markers` WHERE Registration = '$get_rego'";
//                    $result_check_vam = mysqli_query($link, $query_check_vam);
//                    $check_vam = mysqli_fetch_assoc($result_check_vam);
//
//                    $available = $check_vam['Available'];

                    if($check_vehicle == 0){
                        //booking success
                        $rego = mysqli_fetch_assoc($result_rego);
                        $id = $_SESSION['id'];
                        $df = $_POST['from'];
                        $dt = $_POST['to'];
                        $user_s = date_parse ("$df");
                        $user_e = date_parse ("$dt");
                        $r = $rego['Registration'];
                        $status = 'pending';
                        if($user_s == $user_e or $user_s > $user_e){
                            $error .= "ending date have to be greater than starting date!";
                        }else{
//                            ============
                            $query = "INSERT INTO `booking` (`user_id`,`vehicle_rego`,`start`, `end`, `status`) VALUES ('$id','$r', '$df', '$dt', '$status')";
                            $result = mysqli_query($link, $query);
//                            ============
                            $success .= "Vehicle available! <br>Pay now to complete booking.<br>Use card number<br><b>4242 4242 4242 4242</b><br> for test payment.";
                        }

                    }else{

                        $i=0;
                        $count_failed = 0;
                        $count_success = 0;
                        while ($i < $check_vehicle){
//                            echo $i;
//                            echo '<br>';

                            //get datetime from db by rego
                            $get_date = mysqli_fetch_assoc($result_check_vehicle);
                            $dbs = $get_date['start'];
                            $dbe= $get_date['end'];
                            $db_start = date_parse ("$dbs");
                            $db_end = date_parse ("$dbe");
                            $us = $_POST['from'];
                            $ue = $_POST['to'];
                            $user_start = date_parse ("$us");
                            $user_end = date_parse ("$ue");
                            //===================
                            //print info -- testing

//                            echo '<br><br><br><br>db-start: ';
//                            echo $dbs;
//                            echo '<br>';
//                            echo 'db-end: ';
//                            echo $dbe;
//                            echo '<br>';
//                            echo 'user-start: ';
//                            echo $us;
//                            echo '<br>';
//                            echo 'user-end: ';
//                            echo $ue;
//                            echo '<br>';
                            //===================
                            if($user_start == $user_end){
                                $count_success -= 100;
//                                echo $count_success;
//                                echo '<br>';

                            }elseif($user_start >= $db_end or $user_end <= $db_start){
                                $count_success++;
//                                echo 'success';
//                                echo $count_success;
//                                echo '<br>';
                            }else{
                                $count_failed ++;
//                                echo 'failed';
//                                echo '<br>';
                            }
                            $i++;
                        }
//                        echo '<br>failed count: ';
//                        echo $count_failed;
//                        echo '<br>';
//
//                        echo '<br>success count: ';
//                        echo $count_success;
//                        echo '<br>';

                        if($count_success < 0){
                            $error .= "ending date can't be same as starting date!";
                        }elseif($count_failed > 0){
                            $error .= "Vehicle has been booked,<br>Please search for another time period";
                        }else{

                                $rego = mysqli_fetch_assoc($result_rego);
                                $id = $_SESSION['id'];
                                $df = $_POST['from'];
                                $dt = $_POST['to'];
                                $r = $rego['Registration'];
                                $status = 'pending';
                                $query = "INSERT INTO `booking` (`user_id`,`vehicle_rego`,`start`, `end`, `status`) VALUES ('$id','$r', '$df', '$dt', '$status')";
                                $result = mysqli_query($link, $query);
                                $success .= "Vehicle available! <br>Pay now to complete booking.<br>Use card number<br><b>4242 4242 4242 4242</b><br> for test payment.";
//                            echo '<br><br>';
//                            echo $count_success;

                        }
                    }
                }
            }
        }else{
            echo '<br><br><br><br>';
            echo 'pressed';
        }
    }


}else {
    header("Location: login.php");
}
include("header.php");

?>
<!--<link rel="stylesheet" href="datetimepicker/bootstrap.min.css">
<link rel="stylesheet" href="datetimepicker/bootstrap-datetimepicker.min.css">
-->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">

<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
<script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
<script type="text/javascript" src="datetimepicker/bootstrap.min.js"></script>
<script type="text/javascript" src="datetimepicker/moment.js"></script>
<script type="text/javascript" src="datetimepicker/bootstrap-datetimepicker.min.js"></script>

<style>
    html {

        background: url(mybooking.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;

    }

    h4 {
        color: #444444;
    }

    .container {
        text-align: -webkit-center;
        width: 400px;
    }
    .input-group-addon, .input-group-btn {
        width: 0%;
        white-space: nowrap;
        vertical-align: middle;
    }

    body {
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
        background-color: rgba(0,0,0,.0001);
    }

    .btn {
        padding: 8px 15px;
        font-size: 16px;
        border: 1px solid #5cb85c;
    }

    .navbar-brand {
        font-size: 21px;
    }


    .stripe-button-el{
        visibility: hidden;
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

    <div class="row" >
        <div class="medium-12 large-12 columns">
            <?php $row = mysqli_fetch_assoc($result_name)?>
            <h4>Hello, <?php echo $row['first_name']; ?>!</h4>

            <div id="error"><?php if ($error!="") {
                    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                } ?>
            </div>

            <div id="error"><?php if ($success!="") {
                    echo '<div class="alert alert-success" role="alert">'.$success.'</div>';
                } ?>
            </div>

            <div class="medium-2  columns">BOOKING FOR:</div>
            <div class="medium-1  columns"><b><?php echo $row['email']; ?></b></div>
            <br>
            <?php $row_vam = mysqli_fetch_assoc($result_vam)?>
            <div class="medium-2  columns">Make:</div>
            <div class="medium-1  columns"><b><?php echo $row_vam['Make']; ?></b></div>
            <br>
            <div class="medium-2  columns">Model:</div>
            <div class="medium-1  columns"><b><?php echo $row_vam['Model']; ?></b></div>
            <br>

            <?php

                if($success == "Vehicle available! <br>Pay now to complete booking.<br>Use card number<br><b>4242 4242 4242 4242</b><br> for test payment."){



                    echo '<div class="medium-2  columns">

                <form action="booking_success.php" method="POST">
                    <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="pk_test_rF5akTBZXbeeCejFj0ny58hm"
                            data-amount="4990"
                            data-name="Booking vehicle"
                            data-description="payment"
                            data-image="notes.png"
                            data-email="testPayment@mail.com"
                            data-locale="auto"
                            data-currency="aud">
                    </script>
                 </form>

            </div>';

                }else{
                    echo '
            <form  method="post">
                <div class="row">
                    <div class="form-group">
                        <div class="medium-2  columns">From:</div>
                        <b>
                            <div class=\'input-group date\' id=\'datetimepicker6\'>
                                <input type=\'text\' name="from" class="form-control" />
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </b>
                        <br>
                        <div class="medium-2  columns">To:</div>
                        <b>
                            <div class=\'input-group date\' id=\'datetimepicker7\'>
                                <input type=\'text\' name= "to" class="form-control" />
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </b>
                    </div>
                </div>
                <div class="medium-2  columns">
                    <input type="hidden" name="book" value="1">
                    <input class="btn btn-success" type="submit" name="submit" value="Search">

                </div>
                <br>

            </form>';
                }

            ?>

<?php

?>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
    $(function () {
        $('#datetimepicker6').datetimepicker({
            stepping:60,
            format: "YYYY-MM-DD HH:mm:ss",
            minDate: new Date()
        });
        $('#datetimepicker7').datetimepicker({
            stepping: 30,
            format: "YYYY-MM-DD HH:mm:ss",
            useCurrent: false //Important! See issue #1075
        });
        $("#datetimepicker6").on("dp.change", function (e) {
            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker7").on("dp.change", function (e) {
            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        });
    });
</script>






