<?php
    session_start();

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

        $_SESSION['id'] = $_COOKIE['id'];

    }

if (!isset($_SESSION['id'])){
    echo"<br>";    echo"<br>";
    echo"<br>";
    echo "session for user info is not saved" ;
}else {
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "session for user info is saved: user ID is ";
    echo $_SESSION['id'];
}
include("connection.php");
$query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
$result_name = mysqli_query($link, $query_name);

//query for table content
$query_table = "SELECT * FROM `vehicle_at_markers`";
$result_table = mysqli_query($link, $query_table);
//
//if (array_key_exists('submit', $_POST)) {
////
////        echo 'you pressed';
////        echo $_POST['submit'];
//
////        echo $i;
//    $_SESSION_['Registration'] = $row_table['Registration'];
//
//    header("Location: book_vehicle.php");
//
//
//}







    include("header.php");

?>
<title>Restructure</title>
<nav class="navbar navbar-light bg-faded navbar-fixed-top">
    <a class="navbar-brand" >Restructure--Testing</a>
    <div class="pull-xs-right">
        <a href ='login.php?logout=1'>
        <button class="btn btn-success-outline" type="submit">Logout</button></a>
    </div>

    <div class="pull-xs-right">
        <a href ='loggedinpage.php'>
            <button class="btn btn-success-outline" type="submit">back to logged in page</button></a>
    </div>

</nav>

<br>
<br>

<link rel="stylesheet" href="css/foundation.css">

<div class="container-fluid" id="containerLoggedInPage">
    <div class="row">
        <div class="medium-12 large-12 columns">
            <?php $row_name = mysqli_fetch_assoc($result_name)?>
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
                    <?php
                        $array = [];

                    ?>
                    <?php $i = 0;
                            while($row_table = mysqli_fetch_assoc($result_table)) : ?>
                        <?php $query_book = "SELECT * FROM `vehicle_at_markers` WHERE Registration = '".$row_table['Registration']."'";
                        ?>
                        <tr>
                            <td><?php echo $row_table['address']; ?></td>
                            <td><?php echo $row_table['Make']; ?></td>
                            <td><?php echo $row_table['Model']; ?></td>
                            <td>
                                <!--<a class="hollow button" href="./clients_new.html">EDIT</a>-->
                                <!--<a class="hollow button warning" name="submit" href="./book_vehicle.php?ids=$row[address]">BOOK</a>-->
                                <input type="hidden" name="book" value="<?php echo $row_table['Registration']?>">
                                <input class="btn btn-success" type="submit" name="submit" value="<?php echo $row_table['Registration']?>">
                                <?php

                                echo $i;
                                echo $row_table['Registration'];

                                $array[] = $row_table['Registration'];
//                                $new_array[ $row_table['Registration']] = $row_table;

                                ?>
                            </td>
                        </tr>

<!--                    --><?php //$i++; ?>
                    <?php endwhile ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>


<?php
if (array_key_exists('submit', $_POST)) {
//
        echo 'you pressed ';
        echo $_POST['submit'];
//
////        echo $i;
//    $_SESSION_['Registration'] = $row_table['Registration'];
//    setcookie("Registration", $row_table['Registration'], time() + 60*60*24*365);

    header("Location: book_vehicle.php");


}else{

    echo "WTF";
}
//
//    if ($array != ''){
//        echo ' TRUE: ';
////        echo $array[$i[0]];
////        echo $i[1];
//
//    }else{
//        echo ' FALSE: ';
//        echo 'empty';
//    }

    //header("Location: book_vehicle.php");
//}else{
//    echo "false ";
//}
//?>

<?php
    echo $array[2];

?>




<?php
    include("footer.php");
?>
