<?php
    session_start();

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

        $_SESSION['id'] = $_COOKIE['id'];

    }

    if (array_key_exists("id", $_SESSION)) {
        include("connection.php");

        //query for user name
        $query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
        $result_name = mysqli_query($link, $query_name);

        //query for table content
        $query_table = "SELECT * FROM `vehicle_at_markers`";
        $result_table = mysqli_query($link, $query_table);

        $row_name = mysqli_fetch_assoc($result_name);



        if (array_key_exists('submit', $_POST)) {

//
            if ($_POST['signUp'] == $row_table['Registration']) {
//                $row_1 = mysqli_fetch_array($result_table);
//
//                $_SESSION['Registration'] = $row_1['Registration'];
//                setcookie("id", $row_1['id'], time() + 60*60*24*365);
//                header("Location: book_vehicle.php");


                //query for booking content
//                $row_t = mysqli_fetch_assoc($result_table);
                $query_book = "SELECT * FROM `vehicle_at_markers` WHERE Registration = '".$row_table['Registration']."'";

                $result_book = mysqli_query($link, $query_book);
                //echo $result_book;
                $row_1 = mysqli_fetch_array($result_book);

                $_SESSION_['Registration'] = $row_table['Registration'];
                setcookie("id", $row_table['id'], time() + 60*60*24*365);

                echo "<br><br><br> ";
                echo $row_1['Registration'];
                header("Location: booking_confirmation.php");

           }
        }
    } else {
        header("Location: login.php");
    }
    include("header.php");

?>
<title>select vehicle</title>
<nav class="navbar navbar-light bg-faded navbar-fixed-top">
    <a class="navbar-brand" >Booking Sample</a>
    <div class="pull-xs-right">
        <a href ='login.php?logout=1'>
        <button class="btn btn-success-outline" type="submit">Logout</button></a>
    </div>

    <div class="pull-xs-right">
        <a href ='restructure.php'>
            <button class="btn btn-success-outline" type="submit">test</button></a>
    </div>

    <div class="pull-xs-right">
        <a href ='index_test.php'>

            <button class="btn btn-success-outline" type="submit">Map</button></a>
    </div>

</nav>

<br>
<br>

<link rel="stylesheet" href="css/foundation.css">


<div class="container-fluid" id="containerLoggedInPage">
    <div class="row">
        <div class="medium-12 large-12 columns">
<!--        --><?php //$row = mysqli_fetch_assoc($result_name)?>
            <h4>Hello, <?php echo $row_name['first_name']; ?>!</h4>
<!--        <div class="medium-2  columns"><a class="button hollow success" href="./clients_new.html">ADD NEW CLIENT</a></div>-->
            <form method="post">

            <table class="stack">
                <thead>
                <tr>
                    <th width="200">Parking location</th>
                    <th width="200">Car make</th>
                    <th width="200">Car model</th>
                    <th width="200">Action</th>
                </tr>
                </thead>


                <tbody>
                        <?php while($row_table = mysqli_fetch_assoc($result_table)) : ?>
                        <?php $query_book = "SELECT * FROM `vehicle_at_markers` WHERE Registration = '".$row_table['Registration']."'";
                            ?>
                        <tr>
                            <td><?php echo $row_table['address']; ?></td>
                            <td><?php echo $row_table['Make']; ?></td>
                            <td><?php echo $row_table['Model']; ?></td>
                            <td>
                            <!--<a class="hollow button" href="./clients_new.html">EDIT</a>-->
                            <!--<a class="hollow button warning" name="submit" href="./book_vehicle.php?ids=$row[address]">BOOK</a>-->
                                <input type="hidden" name="signUp" value="<?php $row_table['Registration']?>">
                                <input class="btn btn-success" type="submit" name="submit" value="Book">

                            </td>
                        </tr>
                        <?php endwhile ?>
                </tbody>
            </table>
            </form>
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>
<?php
    include("footer.php");
?>
