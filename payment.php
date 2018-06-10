<?php
session_start();
require_once 'init.php';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Payment</title>
	</head>
	<body>
		<form action="myBooking.php" method="POST">
		  <script
		    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
		    data-key="<?php echo $stripe['publishable']; ?>"
		    data-amount="999"
		    data-name="Carshare"
		    data-description="Payment"
		    data-image=""
		    data-email="<?php echo $row['first_name']; ?>"
		    data-locale="auto"
		    data-currency="aud">
		  </script>
		</form>

	</body>
</html>