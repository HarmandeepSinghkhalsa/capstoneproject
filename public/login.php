<?php

    session_start();

    $error = "";
    $success= "";

    if (array_key_exists("logout", $_GET)) {

        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";

        session_destroy();

    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {

        header("Location: index_test.php");

    }

    if (array_key_exists("submit", $_POST)) {

        include("connection.php");

        if (!$_POST['email']) {

            $error .= "An email address is required<br>";

        }

        if (!$_POST['password']) {

            $error .= "A password is required<br>";

        }

        if ($error != "") {

            $error = "<p>There were error(s) in your form:</p>".$error;

        } else {

            if ($_POST['signUp'] == '1') {

                $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) {

                    $error = "That email address is taken.";

                } else {

                    $query = "INSERT INTO `users` (`first_name`, `last_name`,`email`, `password`, `phoneNo`) VALUES ('".mysqli_real_escape_string($link, $_POST['first_name'])."', '".mysqli_real_escape_string($link, $_POST['last_name'])."', '".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."', '".mysqli_real_escape_string($link, $_POST['phone_no'])."')";

                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Could not sign you up - please try again later.</p>";
                        echo '<br>';

                        echo $_POST['first_name'];
                        echo '<br>';
                        echo $_POST['last_name'];
                        echo '<br>';
                        echo $_POST['email'];
                        echo '<br>';
                        echo $_POST['password'];
                        echo '<br>';
                        echo $_POST['phone_no'];



                    } else {

                        $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                        #$id = mysqli_insert_id($link);

                        mysqli_query($link, $query);

                        $success .= "Sign up Successful! <br>";

                    }

                }

            } else {

                    $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";

                    $result = mysqli_query($link, $query);

                    $row = mysqli_fetch_array($result);

                    $query_admin = "SELECT * FROM `admin` WHERE admin_email = '".mysqli_real_escape_string($link, $_POST['email'])."'";

                    $result_admin = mysqli_query($link, $query_admin);

                    $row_admin = mysqli_fetch_array($result_admin);



                    if($_POST['email'] == "admin1@admin.com" || $_POST['email'] == "admin2@admin.com"){
                      // admin_check
                      if(isset($row_admin)){
                        if ($_POST['password'] == $row_admin['admin_password']) {

                            $_SESSION['id'] = $row['id'];

                            #if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1') {

                                setcookie("id", $row['id'], time() + 60*60*24*365);

                            #}

                            header("Location: admin.php");

                        }else {

                            $error = "That email/password combination could not be found.";

                        }
                      } else {

                          $error = "That email/password combination could not be found.";

                      }
                    }else{
                      //$error = "testing false";
                      // user_check
                      if (isset($row)) {

                          $hashedPassword = md5(md5($row['id']).$_POST['password']);

                          if ($hashedPassword == $row['password']) {

                              $_SESSION['id'] = $row['id'];

                              #if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1') {

                                  setcookie("id", $row['id'], time() + 60*60*24*365);

                              #}

                              header("Location: index_test.php");

                          } else {

                              $error = "That email/password combination could not be found.";

                          }

                      } else {

                          $error = "That email/password combination could not be found.";

                      }
                    }
                }

        }


    }


?>

<?php include("header.php"); ?>

      <div class="container" id="homePageContainer">

    <h1>Car Sharing</h1>

          <p><strong>Wanna book for your own car?</strong></p>

          <div id="error"><?php if ($error!="") {
    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

} ?></div>

          <div id="error"><?php if ($success!="") {
                  echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

              } ?></div>
<style>
    /* The message box is shown when the user clicks on the password field */
    h1, p {
        color: grey;
    }
    #message {
        display:none;
        background: #000000;
        color: #ffffff;
        position: relative;
        /*padding: 20px;*/
        /*margin-top: 10px;*/
    }

    #message p {
        /*padding: 10px 35px;*/
        font-size: 12px;
    }

    /* Add a green text color and a checkmark when the requirements are right */
    .valid {
        color: green;
    }

    .valid:before {
        position: relative;
        left: -35px;
        content: "✔";
    }

    /* Add a red text color and an "x" when the requirements are wrong */
    .invalid {
        color: red;
    }

    .invalid:before {
        position: relative;
        left: -35px;
        content: "✖";
    }

    .btn-success {
    color: #fff;
    background-color: #df7366;
    border-color: #df7366;
    transition: background-color 0.35s ease-in-out, color 0.35s ease-in-out, border-bottom-color 0.35s ease-in-out;
    }

    .btn-success:hover, .btn-success:focus{
        background-color: #ef8376;
        border-color: #ef8376;
    }
</style>





<title> login/register</title>

<form method="post" id = "signUpForm">

    <p>Interested? Sign up now.</p>
    <!-- first name -->
    <fieldset class="form-group">

        <input class="form-control" type="first_name" name="first_name" placeholder="First Name" pattern="^[A-Za-z]{0,10}"  oninvalid="this.setCustomValidity('Please Enter valid first name')"
               oninput="setCustomValidity('')" required>

    </fieldset>

    <!-- last name -->
    <fieldset class="form-group">

        <input class="form-control" type="last_name" name="last_name" placeholder="Last Name" pattern="^[A-Za-z]{0,10}"  oninvalid="this.setCustomValidity('Please Enter valid last name')"
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
        <input class="form-control" type="password" id="password" name="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" required>

    </fieldset>

    <div id="message">
        <h5>Password must contain:</h5>
        <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
        <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
        <p id="number" class="invalid">A <b>number</b></p>
        <p id="length" class="invalid">Minimum <b>6 characters</b></p>
    </div>

    <script>

        var myInput = document.getElementById("password");
        var letter = document.getElementById("letter");
        var capital = document.getElementById("capital");
        var number = document.getElementById("number");
        var length = document.getElementById("length");

        // When the user clicks on the password field, show the message box
        myInput.onfocus = function() {
            document.getElementById("message").style.display = "block";
        }

        // When the user clicks outside of the password field, hide the message box
        myInput.onblur = function() {
            document.getElementById("message").style.display = "none";
        }

        // When the user starts to type something inside the password field
        myInput.onkeyup = function() {
            // Validate lowercase letters
            var lowerCaseLetters = /[a-z]/g;
            if(myInput.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            // Validate capital letters
            var upperCaseLetters = /[A-Z]/g;
            if(myInput.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            // Validate numbers
            var numbers = /[0-9]/g;
            if(myInput.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            // Validate length
            if(myInput.value.length >= 6) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }
        }
    </script>


<!--    <div class="checkbox">-->
<!---->
<!--        <label>-->
<!---->
<!--        <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in-->
<!---->
<!--        </label>-->
<!---->
<!--    </div>-->

    <fieldset class="form-group">

        <input type="hidden" name="signUp" value="1">

        <input class="btn btn-success" type="submit" name="submit" value="Sign Up!">

    </fieldset>

    <p><a class="toggleForms">Log in</a></p>

</form>

<form method="post" id = "logInForm">

    <p>Log in using your email and password.</p>

    <fieldset class="form-group">

        <input class="form-control" type="email" name="email" placeholder="Your Email">

    </fieldset>

    <fieldset class="form-group">

        <input class="form-control"type="password" name="password" placeholder="Password">

    </fieldset>
<!---->
<!--    <div class="checkbox">-->
<!---->
<!--        <label>-->
<!---->
<!--            <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in-->
<!---->
<!--        </label>-->
<!---->
<!--    </div>-->

        <input type="hidden" name="signUp" value="0">

    <fieldset class="form-group">

        <input class="btn btn-success" type="submit" name="submit" value="Log In!">

    </fieldset>

    <p><a class="toggleForms">Sign up</a></p>

</form>




      </div>





<?php include("footer.php"); ?>
