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

    $query_booking = "SELECT * FROM `booking` WHERE status = 'Active'";
    $result_booking = mysqli_query($link, $query_booking);
    $booking_active_check = mysqli_num_rows($result_booking);
//    echo 'active exists: ';
//    echo $booking_active_check;
    $i = 0;
    if($booking_active_check > 0){

        while($i < $booking_active_check){
            # set region
            date_default_timezone_set('Australia/Melbourne');
            $get_date = mysqli_fetch_assoc($result_booking);
            # get real current time $ct
            $ct = date('Y-m-d H:i:s', time());
            # get db start time $dbs
            $dbs = $get_date['start'];
            # get db end time $dbe
            $dbe= $get_date['end'];
//            echo '<br><br>';
//            echo $i.'=========='.$get_date['booking_id'].'=========='.$i;
//            echo '<br>';
//            echo "current time : ";
//            echo $ct ;
//            echo '<br>';
//            echo "db start time : ";
//            echo $dbs ;
//            echo '<br>';
//            echo "db end time : ";
//            echo $dbe ;
//            echo '<br>';
            if($ct >= $dbs and $ct < $dbe){
//                echo '$ct > $dbs and $ct < $ dbe';
//                echo '<br>';
                $rego = $get_date['vehicle_rego'];
                $query_update = "UPDATE `vehicle_at_markers` SET `Available` = '0' WHERE Registration = '$rego'";
                $result_update = mysqli_query($link, $query_update);

            }
//            echo $i.'=========='.$rego.'=========='.$i;

            # if($ct < $dbs){ good }
            # if($ct > $dbs and $ct < $ dbe){update available to 0}

            # if($ct > $dbe and available == 0) { message: user over due/cancel=> update available==>1 and status=>cancelled}
            $i++;
        }

    }

}


//$ids = "'<script>$jump</script>'";
//
//if (array_key_exists('submit', $_POST)) {
//
//    $query_name = "SELECT `id` FROM `vehicle_at_markers`";
//    $result_name = mysqli_query($link, $query_name);
//
//
//
//
//
//
//    if($_POST['booking']){
//
//
//        header("Location: booking_confirmation.php");
////        $query_name = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
////        $result_name = mysqli_query($link, $query_name);
////
////        $row = mysqli_fetch_assoc($result_name);
//            $vamID = $_GET['vehicle_id'];
//
////
////
////        $_SESSION['lastname'] = $row['last_name'];
//
//    }else{
//
//
//    }
//}



?>
<!--
	Helios by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
	<head>
		<title>Click 'N' Go</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<style>
		#map {
        width:100%;
        height:100%;
      }
      body,html {
        width: 100%;
        height: 100%;
      }

      #test {
        display: none;
      }

      #header {
        background: #DDDDDD;
      }

   
    
    </style>
    
	</head>
	<body>
		<div id="header" style = "height = 0px; padding-top: 20px;height: 0px;";>
			<!-- Nav -->
				<nav id="nav">
					<ul>


						<li><a href="index.php">Home</a></li>
						<li><a href="myBooking.php">My Booking</a></li>
						<li><a href="contactUs.php">Contact Us</a></li>
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
                            echo '<li><a href="index_test.php?logout=1">Logout</a></li>';


                        }
                        ?>


                    </ul>
				</nav>
		</div>
		
		<div id="map"></div>
		<p id="demo"></p>

    <script type="text/javascript">
    	var customLabel = {
        	red: {
        		label: 'Red'
        	},
        	grey: {
        		label: 'Grey'
        	},
        	green:{
        		label:'Green'
    		}
    	};
        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(-37.8133954, 144.9651374),
          zoom: 14.7,
          styles:[
          	{featureType: 'poi',
              elementType: 'all',
              stylers: [{visibility: 'off'}]
              },
              {featureType: 'transit',
              elementType: 'all',
              stylers: [{visibility: 'off'}]
              },
          	],
          	streetViewControl: false,
            mapTypeControl: false,
            fullscreenControl: false
        });
        
        var myLocation = new google.maps.Marker({
    		clickable: false,
    		icon: new google.maps.MarkerImage('//maps.gstatic.com/mapfiles/mobile/mobileimgs2.png',
    		new google.maps.Size(22,22),
            new google.maps.Point(0,18),
            new google.maps.Point(11,11)),
    		shadow: null,
    		zIndex: 999,
			map: map,
			});

		if (navigator.geolocation) navigator.geolocation.getCurrentPosition(function(position) {
		 pos = {
			lat:position.coords.latitude,
			lng: position.coords.longitude
        };
		console.log(pos);
		myLocation.setPosition(pos);
		myLocationCircle.setCenter(pos);
		}, function(error){
			'Error: The Geolocation service failed.';
		});
		

/*		function success(pos) {
   crd = pos.coords;
  console.log('Your current position is:');
  console.log(`Latitude : ${crd.latitude}`);
  console.log(`Longitude: ${crd.longitude}`);
  console.log(`More or less ${crd.accuracy} meters.`);
  return pos;
}
var myPosition = new navigator.geolocation.getCurrentPosition(success);
console.log(myposition.coords.latlng);*/
		
		//var me = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
	   
	   //myLocation.setPosition(me);
		//}, function(error) {
		//  'Error: The Geolocation service failed.';
		//});
		
		//var myLocationLat = myLocation.getPosition().lat();
		//var myLocationLng = myLocation.getPosition().lng();
		
		//var locationTest = new CurrentLocation();
		//console.log(locationTest);
		var myLocationCircle = new google.maps.Circle({
			strokeColor:'#4683ea',
			strokeOpacity: 0.6,
            strokeWeight: 2,
            fillColor: '#4683ea',
            fillOpacity: 0.35,
            map: map,
            radius: 300,
			clickable: false
		});
		
		

          // Change this depending on the name of your PHP or XML file
          downloadUrl('https://capstonecarshare.tk/vehicle_testing.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('vehicle_marker');
              Array.prototype.forEach.call(markers, function(markerElem, i) {
              var rego = markerElem.getAttribute('registration');
              var make = markerElem.getAttribute('make');
              var model = markerElem.getAttribute('model');
			  var seats = markerElem.getAttribute('seats');
			  var description = markerElem.getAttribute('description');
              var type = markerElem.getAttribute('type');
              var available = markerElem.getAttribute('available');
              var rating = parseFloat(markerElem.getAttribute('rating'));
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));
              var icon = customLabel[type] || {};


				var marker = new google.maps.Marker({
                map: map,
                position: point,
				title: make + ' ' +model
              });
			  
			  //var markerCluster = new MarkerCluster(map,marker{imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

			  /*if(google.maps.geometry.spherical.computeDistanceBetween(marker.getPosition(),myLocationCircle.getCenter())<= myLocationCircle.radius()){
				  console.log('=> is in radius');
			  }*/

        var contentString = '<form method="GET" action = "booking.php">'+
            '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<h1 id="firstHeading" class="firstHeading">'+
      make+
      ' '+
      model+
      '</h1>'+
            '<div id="bodyContent">'+
            '<br>'+
            '<p>'+
      description+
      '</p>'+
      '<br>'+
      '<p>'+ 
	  "Seats:" +
	  seats+
      '</p>'+
	  '<p>'+
	  "Rating:"+
	  rating+
	  "/5"+
	  '</p>'+
            `<input type="hidden" name="vamId" value="${i+1}" >`+
      '<input type="submit" name="submit_type" value = "book">'+
                '</div>'+
            '</div>'+
            '</form>';


                var greyContent = '<form method="GET" action = "return_success.php">'+
                    '<div>'+
                          '<div id="siteNotice">'+
                          '</div>'+
                          '<br>'+
                          '<h1 id="firstHeading" class="firstHeading">'+
                          'This spot is empty, you can park here'+
                          '</h1>'+
                          '<br>'+
                          `<input type="hidden" name="vamID" value="${i+2}" >`+
                          '<input type="submit" name="submit" value = "park here">'+
                          '</button>'+
                          '</div>'+
                          '</form>';



                  if (rego == ''){
                  marker.setIcon('https://capstonecarshare.tk/car-grey.png');
                  var parkWindow = new google.maps.InfoWindow({
                    content:greyContent,
                    maxWidth: 150
                  });
                  marker.addListener('click', function(){
                    parkWindow.open(map, marker);
                  });
                }else{
                  marker.setIcon('https://capstonecarshare.tk/car-green.png');
                  var infoWindow = new google.maps.InfoWindow({
          content:contentString,
          maxWidth: 150
        });
                  marker.addListener('click', function(){
                    infoWindow.open(map, marker);
                  });
                }

                if(available != 1 && available != ''){
                    marker.setIcon('https://capstonecarshare.tk/car-red.png');
                    var infoWindow = new google.maps.InfoWindow({
                        content:contentString,
                        maxWidth: 150
                    });
                    marker.addListener('click', function(){
                        infoWindow.open(map, marker);
                    });
                }
			 });
              
              });
			  
		
        }
		

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}



    </script>
    
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4aTKDkRM_UweRS9dB1uQ0DLlaEIQDrSo&callback=initMap">
    </script>
    
		 <!--Scripts -->
		    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.onvisible.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
			<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry"></script>

	</body>