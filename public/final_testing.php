<?php

    date_default_timezone_set('Australia/Melbourne');
    $ct = date('Y-m-d H:i:s', time());
    $ct_strtotime = strtotime("$ct");

    echo '<br><br><br>';
    echo "current time : ";
    echo $ct ;
    echo '<br><br>';
    $test_time = '2018-05-18 18:16:00';
    $tt_strtotime = strtotime("$test_time");

    $difference = round(abs($ct_strtotime - $tt_strtotime) / 60,2). " minute";


echo 'test time : ';
    echo $test_time;
    echo '<br><br>';
    if($ct > $test_time){
        echo 'current time is larger';
        echo '<br><br>';
        echo 'difference: '.$difference;

    }else{
        echo 'current time is smaller';
        echo '<br><br>';
        echo 'difference: '.$difference;

    }


//$to_time = strtotime("2008-12-13 10:42:00");
//$from_time = strtotime("2008-12-13 10:21:00");
//echo round(abs($to_time - $from_time) / 60,2). " minute";

?>



<?php
//
//include("connection.php");
//
//$query_booking = "SELECT * FROM `booking` WHERE status = 'Active'";
//$result_booking = mysqli_query($link, $query_booking);
//$booking_active_check = mysqli_num_rows($result_booking);
//echo 'active exists: ';
//echo $booking_active_check;
//$i = 0;
//if($booking_active_check > 0){
//
//    while($i < $booking_active_check){
//        # set region
//        date_default_timezone_set('Australia/Melbourne');
//        $get_date = mysqli_fetch_assoc($result_booking);
//        # get real current time $ct
//        $ct = date('Y-m-d H:i:s', time());
//        # get db start time $dbs
//        $dbs = $get_date['start'];
//        # get db end time $dbe
//        $dbe= $get_date['end'];
//        echo '<br><br>';
//        echo $i.'=========='.$get_date['booking_id'].'=========='.$i;
//        echo '<br>';
//        echo "current time : ";
//        echo $ct ;
//        echo '<br>';
//        echo "db start time : ";
//        echo $dbs ;
//        echo '<br>';
//        echo "db end time : ";
//        echo $dbe ;
//        echo '<br>';
//        $rego = $get_date['vehicle_rego'];
//
//        if($ct > $dbs and $ct < $dbe){
//            echo '$ct > $dbs and $ct < $ dbe';
//            echo '<br>';
//
//            $query_update = "UPDATE `vehicle_at_markers` SET `Available` = '0' WHERE Registration = '$rego'";
//            $result_update = mysqli_query($link, $query_update);
//
//        }
//        echo $i.'=========='.$rego.'=========='.$i;
//
//        # if($ct < $dbs){ good }
//        # if($ct > $dbs and $ct < $ dbe){update available to 0}
//
//        # if($ct > $dbe and available == 0) { message: user over due/cancel=> update available==>1 and status=>cancelled}
//        $i++;
//    }
//
//}
//?>
