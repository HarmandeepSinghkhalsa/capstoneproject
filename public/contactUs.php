<?php

session_start();

$error = "";
$success= "";

if (array_key_exists("submit", $_POST)) {

    include("connection.php");


    if ($error != "") {

        $error = "<p>There were error(s) in your form:</p>".$error;

    }else{
        if ($_POST['submit'] == '1'){
            $query = "INSERT INTO `messages` (`name`, `email`,`phone`, `message`)
                        VALUES ('".mysqli_real_escape_string($link, $_POST['name'])."',
                         '".mysqli_real_escape_string($link, $_POST['email'])."',
                          '".mysqli_real_escape_string($link, $_POST['phone_no'])."',
                           '".mysqli_real_escape_string($link, $_POST['message'])."')";
            mysqli_query($link, $query);
            $success .= "Submitted <br>";

        }

    }
}


?>

<?php include("header.php"); ?>

<style>

    html {

        background: url(contactus.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;

    }

    .btn-success {

        border-color: #a7a7a7;
    }



    a {
        color: white;
    }


</style>

<title> Contact US</title>

<div class="container" id="homePageContainer">

    <h1  style="color:#838383;"> Contact Us</h1>

    <p style="color:#838383;"><strong>Feel free to contact us</strong></p>

    <div id="error"><?php if ($error!="") {
            echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

        } ?></div>

    <div id="error"><?php if ($success!="") {
            echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

        } ?>
    </div>




    <form method="post" id = "signUpForm">

        <!-- first name -->
        <fieldset class="form-group">

            <input class="form-control" type="name" name="name" placeholder="Your name" pattern="^[A-Za-z]{0,10}"  oninvalid="this.setCustomValidity('Please Enter valid first name')"
                   oninput="setCustomValidity('')" required>

        </fieldset>



        <!-- email -->
        <fieldset class="form-group">

            <input class="form-control" type="email" name="email" placeholder="Your Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{0,3}$" oninvalid="this.setCustomValidity('Please Enter valid e-mail')" oninput="setCustomValidity('')" required>

        </fieldset>


        <!-- contact number -->
        <fieldset class="form-group">

            <input class="form-control" type="phone_no" name="phone_no" placeholder="Contact Number(ie. 0123456789)" pattern="^\d{10}$" oninvalid="this.setCustomValidity('Please Enter valid phone number')" oninput="setCustomValidity('')" required>

        </fieldset>

        <!-- password -->
        <fieldset class="form-group">
<!--            <input class="form-control" type="message" id="message" name="message" placeholder="message" required>-->

            <textarea class="form-control" type="message" id="message" name="message"  rows="5" cols="50" placeholder="Leave us a message"></textarea>

        </fieldset>



        <fieldset class="form-group">

            <input  type="hidden" name="submit" value="1">

            <input style="background-color:#a7a7a7;" class="btn btn-success" type="submit" name="submit_contact" value="Submit!">

        </fieldset>

        <fieldset class="form-group">

            <button class="btn btn-success" type="button" style="background-color:#a7a7a7;""><a href="index_test.php">back</a></button>

        </fieldset>


    </form>



</div>





<?php include("footer.php"); ?>
