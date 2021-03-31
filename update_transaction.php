<?php

session_start();

include "dbconfig.php";

date_default_timezone_set('America/New_York');

$con = mysqli_connect($host,$username,$password,$dbname)
or die ("<br> Cannot connect to DB: $dbname on $host " .mysqli_connect_error());

echo '<a href = "logout.php">User Logout</a>';
$datetime = date_create()->format('Y-m-d H:i:s');

if(!isset($_COOKIE['customer']) and !isset($_COOKIE['customer_id']))
	header("location: p2.html");

$customer_id = $_COOKIE['customer_id']; 



$numDel = 0;

if(!empty($_POST["row"]))
{
	foreach($_POST["row"] as $delRow)
	{

	$delSQL = "DELETE FROM CPS3740_2020F.Money_patpanka WHERE code = '$delRow' ";
		if(mysqli_query($con,$delSQL))
		{
			echo "<br>The code " .$delRow. " has been deleted from the database";
			$numDel += 1;
		}
		else
			echo "something wrong in query"; 
	}
	
}
else
			echo " No rows deleted";




		






echo "<br>Records deleted: " .$numDel;
	
mysqli_close($con);

?>