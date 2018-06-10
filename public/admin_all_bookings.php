<?php

    session_start();

    $error = "";
    $success="";

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

        $_SESSION['id'] = $_COOKIE['id'];

    }

    if (array_key_exists("id", $_SESSION)) {
      include("connection.php");
      // $query = "SELECT * FROM `booking`";
      // $result = mysqli_query($link, $query);

      if(array_key_exists("submit", $_POST)){

         //$query_delete = "DELETE * FROM `users` WHERE `users`.`email` = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

         //mysqli_query($link, $query_delete);
         if ($_POST['display'] == '1') {

           // $query = "SELECT booking.booking_id,booking.user_id,users.first_name,booking.vehicle_rego,booking.start_date,booking.end_date,booking.time FROM booking JOIN users WHERE booking.user_id = users.id";
           // $result = mysqli_query($link, $query);

          $query = "SELECT * FROM booking JOIN users WHERE booking.user_id = users.id";
           $result = mysqli_query($link, $query);

         }
         if ($_POST['search'] == '1'){
           $query = "SELECT * FROM `booking` WHERE `booking`.`booking_id` = '".mysqli_real_escape_string($link, $_POST['booking_id'])."' LIMIT 1";
           #$query = "DELETE  FROM `users` WHERE `users`.`email` = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

             $result_check = mysqli_query($link, $query);
             if (!$_POST['booking_id']) {

                 $error .= "Please enter a Booking ID to search<br> ";

             }else if (mysqli_num_rows($result_check) == 0) {

                 $error .= "Booking doesn't exist <br>";

             }else{
                 $result = mysqli_query($link, $query);
             }
         }
          if ($_POST['delete'] == '1'){

             $query_check = "SELECT * FROM `booking` WHERE `booking`.`booking_id` = '".mysqli_real_escape_string($link, $_POST['booking_id'])."' LIMIT 1";
             $result_check = mysqli_query($link, $query_check);

              if (!$_POST['booking_id']) {

                  $error .= "Please enter a booking ID to delete<br> ";

              }else if (mysqli_num_rows($result_check) == 0) {

                  $error .= "ID doesn't exist <br>";

              }else{
                  $query = "DELETE  FROM `booking` WHERE `booking`.`booking_id` = '".mysqli_real_escape_string($link, $_POST['booking_id'])."' LIMIT 1";
                  $result = mysqli_query($link, $query);
                  $success .= "Delete Successful <br>";
              }

         }
          

         // $query = "SELECT * FROM `users` WHERE `users`.`email` = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
         // $result = mysqli_query($link, $query);

      }


      }else {

        header("Location: login.php");

    }

	include("header.php");

?>

<style>
table {
    border-collapse: collapse;
    margin: 10px auto;
    border:1px solid #df7366
}

td {
    text-align: center;
    padding: 5px;
     background-color: #DDDDDD; 
     color:black;
     border:1px solid #df7366

}

th {
    background-color: #df7366;
    text-align: center;
    padding: 5px;
    border:1px solid #df7366

}

.btn-success {
    color: #fff;
    background-color: #df7366;
    border-color: #df7366;
    transition: background-color 0.35s ease-in-out, color 0.35s ease-in-out, border-bottom-color 0.35s ease-in-out;
}

.btn-success:hover, .btn-success-outline:hover, .btn-success:focus, .btn-success-outline:focus {
    background-color: #ef8376;
    border-color: #ef8376;
}

.btn-success-outline {
  border-color: #df7366;
  color: #df7366;
}

</style>

<div class="container" id="homePageContainer">

    <div id="error"><?php if ($error!="") {
            echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

        } ?></div>

    <div id="error"><?php if ($success!="") {
            echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

        } ?></div>

<title> Admin Manage - All Bookings</title>


<nav class="navbar navbar-light bg-faded navbar-fixed-top">


  <a class="navbar-brand" href="#">Admin manage system - All Bookings</a>

    <div class="pull-xs-right">
      <a href ='login.php?logout=1'>
        <button class="btn btn-success-outline" type="submit">Logout</button></a>
    </div>
	
	<div class="pull-xs-right">
      <a href ='admin.php'>
        <button class="btn btn-success-outline" type="submit">Back</button></a>
    </div>

</nav>

<form method="post" id = "signUpForm">
  <div class="container-fluid" id="containerLoggedInPage">

    <table>
  <thead>
      <tr>
          <th>Booking ID</th>
          <th>User ID</th>
          <th>User Name</th>
          <th>Vehicle ID</th>
          <th>Start date</th>
          <th>End date</th>
      </tr>
  </thead>
  <tbody>
      <!--Use a while loop to make a table row for every DB row-->
      <?php while($row = mysqli_fetch_assoc($result)) : ?>
      <tr>
          <!--Each table column is echoed in to a td cell-->
          <td><?php echo $row['booking_id']; ?></td>
          <td><?php echo $row['user_id']; ?></td>
          <td><?php echo $row['first_name']; ?></td>
          <td><?php echo $row['vehicle_rego']; ?></td>
          <td><?php echo $row['start']; ?></td>
          <td><?php echo $row['end']; ?></td>
      </tr>
      <?php endwhile ?>
  </tbody>
</table>

  </div>



    <fieldset class="form-group">

        <input type="hidden" name="display" value="1">

        <input  class="btn btn-success" type="submit" name="submit" value="Display All">

    </fieldset>

   <p><a class="toggleForms">search</a></p>

</form>


    <form method="post" id = "logInForm">
      <div class="container-fluid" id="containerLoggedInPage">


      </div>

        <fieldset class="form-group">

            <input  class="form-control" type="booking_id" name="booking_id" placeholder="search by booking ID">

        </fieldset>


        <fieldset class="form-group">

            <input type="hidden" name="search" value="1">

            <input  class="btn btn-success" type="submit" name="submit" value="search">

        </fieldset>

        <p><a class="toggleForms">back</a></p>
    </form>




    <form method="post" id = "Delete">
        <div class="container-fluid" id="containerLoggedInPage">


        </div>

        <fieldset class="form-group">

            <input  class="form-control" type="booking_id" name="booking_id" placeholder="delete by booking ID">

        </fieldset>


        <fieldset class="form-group">

            <input type="hidden" name="delete" value="1">

            <input  class="btn btn-success" type="submit" name="submit" value="Delete">

        </fieldset>

    </form>




</div>

<?php

    include("footer.php");
?>
