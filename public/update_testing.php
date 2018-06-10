<?php

    $link = mysqli_connect("localhost", "pma", "pass","phpmyadmin");

        if (mysqli_connect_error()) {

            die ("Database Connection Error");

        }


        $query_del = "TRUNCATE vehicle_at_markers";

//        $query_del = "Delete from vehicle_at_markers";


        $query_same = "INSERT INTO vehicle_at_markers (markers_id, markers_name, address, lat, lng, type,Registration,Make,Model,Available,Seats,Description,)
                        SELECT markers.markers_id, markers.markers_name, markers.address , markers.lat, markers.lng, markers.type,Vehicle.Registration, Vehicle.Make, Vehicle.Model, Vehicle.Available, Vehicle.Seats, Vehicle.Description 
                        FROM markers 
                        JOIN Vehicle 
                        ON Vehicle.address = markers.address;";


        $query_different = "INSERT INTO vehicle_at_markers (markers_id, markers_name, address, lat, lng, type) 
                            SELECT markers.markers_id, markers.markers_name, markers.address, markers.lat, markers.lng, markers.type 
                            FROM markers 
                            WHERE NOT EXISTS(SELECT markers_id 
                                              FROM vehicle_at_markers 
                                              WHERE vehicle_at_markers.markers_id = markers.markers_id) ";

        $query_index = "alter table vehicle_at_markers AUTO_INCREMENT=1;";




        $result_del = mysqli_query($link, $query_del);
        $result_same = mysqli_query($link, $query_same);
        $result_different = mysqli_query($link, $query_different);

        $result_index = mysqli_query($link, $query_index);

        ?>
