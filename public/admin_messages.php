<?php

    session_start();

    $error = "";
    $success="";

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

        $_SESSION['id'] = $_COOKIE['id'];

    }

    if (array_key_exists("id", $_SESSION)) {
      include("connection.php");
      // $query = "SELECT * FROM `users`";
      // $result = mysqli_query($link, $query);

      if(array_key_exists("submit", $_POST)){

         //$query_delete = "DELETE * FROM `users` WHERE `users`.`email` = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

         //mysqli_query($link, $query_delete);
         if ($_POST['display'] == '1') {

           $query = "SELECT * FROM `messages`";
           $result = mysqli_query($link, $query);

         }
         if ($_POST['search'] == '1'){
           $query = "SELECT * FROM `messages` WHERE `messages`.`email` = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
           #$query = "DELETE  FROM `users` WHERE `users`.`email` = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

             $result_check = mysqli_query($link, $query);
             if (!$_POST['email']) {

                 $error .= "Please enter an email address to search<br> ";

             }else if (mysqli_num_rows($result_check) == 0) {

                 $error .= "User doesn't exist <br>";

             }else{
                 $result = mysqli_query($link, $query);
             }
         }
          if ($_POST['delete'] == '1'){

             $query_check = "SELECT * FROM `messages` WHERE `messages`.`email` = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
             $result_check = mysqli_query($link, $query_check);

              if (!$_POST['email']) {

                  $error .= "Please enter an email address to delete<br> ";

              }else if (mysqli_num_rows($result_check) == 0) {

                  $error .= "E-mail doesn't exist <br>";

              }else{
                  $query = "DELETE  FROM `messages` WHERE `messages`.`email` = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
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


.data {
  width: 70%;
}
</style>

<div class="container" id="homePageContainer">

    <div id="error"><?php if ($error!="") {
            echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

        } ?></div>

    <div id="error"><?php if ($success!="") {
            echo '<div class="alert alert-success" role="alert">'.$success.'</div>';

        } ?></div>

<title> Admin Manager - Messages</title>


<nav class="navbar navbar-light bg-faded navbar-fixed-top">


  <a class="navbar-brand" href="#">Admin manage system - Messages</a>

    <div class="pull-xs-right">
      <a href ='login.php?logout=1'>
        <button class="btn btn-success-outline" type="submit">Logout</button></a>
    </div>
  
  <div class="pull-xs-right">
      <a href ='admin.php'>
        <button class="btn btn-success-outline" type="submit">Back</button></a>
    </div>

</nav>

<form method="post" id = "messagesForm">
  <div class="container-fluid" id="containerLoggedInPage">

    <table>
  <thead>
      <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone Number</th>
          <th>Message</th>
      </tr>
  </thead>
  <tbody>
      <!--Use a while loop to make a table row for every DB row-->
      <?php while($row = mysqli_fetch_assoc($result)) : ?>
      <tr>
          <!--Each table column is echoed in to a td cell-->
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['email']; ?></td>
          <td><?php echo $row['phone']; ?></td>
          <td class="data"><?php echo $row['message']; ?></td>
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

            <input  class="form-control" type="email" name="email" placeholder="search by user email">

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

            <input  class="form-control" type="email" name="email" placeholder="delete by user email">

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