<?php

    $link = mysqli_connect("localhost", "pma", "pass","phpmyadmin");

        if (mysqli_connect_error()) {

            die ("Database Connection Error");

        }

?>
