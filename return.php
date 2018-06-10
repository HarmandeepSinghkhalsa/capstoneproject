<?php
session_start();



if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

    $_SESSION['id'] = $_COOKIE['id'];

}

if (array_key_exists("id", $_SESSION)) {
    include("connection.php");


    $query_booking = "SELECT * FROM `booking` WHERE user_id = ".mysqli_real_escape_string($link, $_SESSION['id'])."";
    $query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";

    $result_booking = mysqli_query($link, $query_booking);
    $result_name = mysqli_query($link, $query_name);

    $query_table = "SELECT * FROM `vehicle_at_markers` WHERE Registration is NULL ";
    $result_table = mysqli_query($link, $query_table);

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
    <title> Return vehicle</title>
    <nav class="navbar navbar-light bg-faded navbar-fixed-top">
        <a class="navbar-brand" href="#">Return vehicle</a>
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
    </nav>
    <?php $row = mysqli_fetch_assoc($result_name)?>
    <h4>Hello, <?php echo $row['first_name']; ?>!</h4>
    <p style="color:#444444;"><strong>click an address to return:</strong></p>
</div>

<div class="container-fluid" id="containerLoggedInPage">
    <div class="row">
        <div class="medium-12 large-12 columns">
            <form method="GET" action="return_success.php">
                <table class="stack">
                    <thead>
                    <tr>
                        <th width="200">Parking address</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($row_table = mysqli_fetch_assoc($result_table)) : ?>
                        <tr>
                            <td>
                                <input class="btn btn-success" type="submit" name="submit" value="<?php echo $row_table['address']; ?>">
                            </td>
                        </tr>
                    <?php endwhile ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<?php
include("footer.php");
?>
