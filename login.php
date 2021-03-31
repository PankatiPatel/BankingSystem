<?php




include "dbconfig.php";

// connect to the database 
$con = mysqli_connect($host,$username,$password,$dbname)
or die("<br> Connot connect to DB: $dbname on $host" .mysqli_connect_error()); 

// default time zone set to NY
date_default_timezone_set('America/New_York');

