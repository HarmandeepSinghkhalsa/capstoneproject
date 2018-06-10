<?php
echo "$your_name" + "$your_email" + "$your_phone" + "$messagetextarea";

$name=$_POST['your_name'];
$email=$_POST['your_email'];
$phone=$_POST['your_phone'];
$message=$_POST['messagetextarea'];
	
	echo "$name" + "$email" + "$phone" + "$message";

 include("connection.php");
  
$sql = "INSERT INTO `messages`(`name`, `email`, `phone`, `message`) VALUES ([name],[email],[phone],[message])";

echo "Message successfully sent"
?>    