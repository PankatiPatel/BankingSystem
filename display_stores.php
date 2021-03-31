<?php
include "dbconfig.php";


// connect to the database 
$con = mysqli_connect($host,$username,$password,$dbname)
or die ("<br> Cannot connect to DB: $dbname on $host " .mysqli_connect_error());

// default time zone set to NY
date_default_timezone_set('America/New_York');

// check to see if cookie is set 
// if set load customer data
if(!isset($_COOKIE['customer']) and !isset($_COOKIE['customer_id']))
		header("location: p2.html");


$sql = "SELECT sid, Name, address, city, State, Zipcode, concat (latitude, longitude) as gis FROM  CPS3740.Stores WHERE latitude is not null and longitude is not 			null"; 

$result = mysqli_query($con, $sql);
	
	if($result)
		{
			if(mysqli_num_rows($result) > 0)
			{
						echo "<TABLE border= '1' align='center'>\n";
						echo "<TR><TH>Store ID<TH>Name<TH>Address<TH>City<TH>State<TH>Zipcode<TH>Location(Latitude.Longitude)</TH>\n";

						while($row = mysqli_fetch_array($result))
						{

							$storeID = $row["sid"];
							$name = $row["Name"];
							$address = $row["address"];
							$city = $row["city"];
							$state = $row["State"];
							$zipcode = $row["Zipcode"];
							$location = $row["gis"];

							if($storeID <> "")
								echo "<br><tr><td>$storeID<td>$name<td>$address<td>$city<td>$state<td>$zipcode<td>$location<t/d>\n";



						}

						echo "</TABLE>\n";
						mysqli_free_result($result);
			}

		}
	else 
		echo " something wrong in query "; 

		mysqli_close($con); 


?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple Polygon</title>
    <style>
      #map-canvas {
        height: 50%;
        margin: 0;
        padding: 0;
      }
    </style>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
     
   

    <script>

    var i = 0;

    function initialize() {
    	var mapOptions = {
        	zoom: 4,
        	
        	center: new google.maps.LatLng(40.67920195936402,-74.23322305083275),
		mapTypeId: google.maps.MapTypeId.ROADMAP
       };

       var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

       var PolygonCoords = new Array();
       var PolyGon = new Array();

	var lineSymbol = {
		path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
	};
	
    	var infowindow = new google.maps.InfoWindow();
	var location;
	var mySymbol;
	var marker, m;
	var MarkerLocations= [
['<a href=../Kean/IMG_3471s.jpg>Kean/IMG_3471s.jpg</a>',40.67929166666700,-74.23282500000000,309.85500000000000 ] ,
['<a href=../Kean/IMG_3836s.jpg>Kean/IMG_3836s.jpg</a>',40.67929166666700,-74.23323888888900,313.78740157480000 ] ,
['<a href=../Kean/IMG_3837s.jpg>Kean/IMG_3837s.jpg</a>',40.67933333333300,-74.23300277777800,147.65785123967000 ] ,
['<a href=../Kean/IMG_3851s.jpg>Kean/IMG_3851s.jpg</a>',40.67919166666700,-74.23295555555600,0.72740935562653 ] ,
['<a href=../Kean/IMG_4798s.jpg>Kean/IMG_4798s.jpg</a>',40.67891944444400,-74.23282500000000,65.27121001390800 ] ,
['<a href=../Kean/IMG_3836s.jpg>Kean/IMG_3836s.jpg</a>',40.67929166666700,-74.23323888888900,313.78740157480000 ] ,
['<a href=../Kean/IMG_3837s.jpg>Kean/IMG_3837s.jpg</a>',40.67933333333300,-74.23300277777800,147.65785123967000 ] ,
['<a href=../Kean/IMG_4798s.jpg>Kean/IMG_4798s.jpg</a>',40.67891944444400,-74.23282500000000,65.27121001390800 ] 
  	];

for (m = 0; m < MarkerLocations.length; m++) {  

	location = new google.maps.LatLng(MarkerLocations[m][1], MarkerLocations[m][2]),
	mySymbol = { path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW, scale: 5, fillOpacity: 0.0, rotation: MarkerLocations[m][3] };
	marker = new google.maps.Marker({ position: location, map: map, icon: mySymbol });

      google.maps.event.addListener(marker, 'click', (function(marker, m) {
        return function() {
          infowindow.setContent(MarkerLocations[m][0]);
          infowindow.open(map, marker);
        }
      })(marker, m));
 }


                  var Temp = [
// Define the LatLng coordinates for the polygon's path.
new google.maps.LatLng(40.67917551593506,-74.23321232199669),
new google.maps.LatLng(40.67916127716127,-74.23329547047615),
new google.maps.LatLng(40.67891108106843,-74.23324719071388),
new google.maps.LatLng(40.67892735401357,-74.23314794898033),
new google.maps.LatLng(40.678904978712964,-74.23313990235329),
new google.maps.LatLng(40.67894362695476,-74.2328529059887),
new google.maps.LatLng(40.67897210459228,-74.23286363482475),
new google.maps.LatLng(40.67898227517417,-74.23275902867317),
new google.maps.LatLng(40.67924060743364,-74.23282876610756),
new google.maps.LatLng(40.679228402782506,-74.23291727900505),
new google.maps.LatLng(40.679244675650196,-74.23292264342308),
new google.maps.LatLng(40.67920195936402,-74.23322305083275)
];
PolygonCoords.push(Temp);
// Construct the polygon.
PolyGon[0] = new google.maps.Polygon({
paths: PolygonCoords[0],
strokeColor: '#FF0000',
strokeOpacity: 0.8,
strokeWeight: 2,
fillColor: '#FF0000',
fillOpacity: 0.35
});
PolyGon[0].setMap(map);

                }

                google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>

</HTML>







