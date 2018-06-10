<?php
	session_start();

if (array_key_exists("logout", $_GET)) {

    unset($_SESSION);
    setcookie("id", "", time() - 60*60);
    $_COOKIE["id"] = "";

    session_destroy();

}

if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {

    $_SESSION['id'] = $_COOKIE['id'];

}


if (array_key_exists("id", $_SESSION)) {
    include("connection.php");

    $query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
    $result_name = mysqli_query($link, $query_name);

}

?>


	<head>
		<title>Click 'N' Go</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body class="header">
		<div >

			<!-- Header -->
				<div id="header">

					<!-- Inner -->
						<div class="inner">
							<header>
								<h1><a id="logo" >Click 'N' Go</a></h1>
								<hr />
								<p>Get ready to start your trip</p>
							</header>
							<footer>
								<a href="index_test.php" class="button circled scrolly">Start</a>
							</footer>
						</div>

					<!-- Nav -->
						<nav id="nav">
							<ul>
										<?php
		                        $row = mysqli_fetch_assoc($result_name);
		                        $name = $row['first_name'];
		                        if(!isset($_SESSION['id']))
		                        {
		                            echo '<li><a href="login.php">Login/Register</a></li>';
		                        }
		                        else
		                        {
		                            echo '<li><a>hello, '.$name.'! </a></li>';
		                            echo '<li><a href="index.php?logout=1">Logout</a></li>';


		                        }
		                        ?>
							</ul>
						</nav>

				</div>

		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.onvisible.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
