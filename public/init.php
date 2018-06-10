<?php

session_start();

$stripe = [
    	'publishable' => 'pk_test_rF5akTBZXbeeCejFj0ny58hm',
    	'private' => 'sk_test_AfLKvJ1Jj1SV45TXlNilhR5f'
   	];


include("connection.php");


$row = mysqli_fetch_assoc($result_name)



?>