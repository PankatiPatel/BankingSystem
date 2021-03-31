<?php

if (empty($_POST["gender"])) 
{
	die("Gender is required");
}
else
$gender=$_GET['gender']; // the name in the idnex must be the same has the name in the html name 

include "dbconfig.php";

 //accesses mysql database server, if it cannot it displays error message
$con = mysqli_connect($host,$username,$password,$dbname)  
or die("<br> Cannot connect to DB: $dbname on $host\n"); 


// query 
$sql= "SELECT  staffno,fname,lname,salary FROM dreamhome.Staff where sex='$gender'";

// return result and set to PHP variable
$result = mysqli_query($con, $sql);

//echo displays on the browser 
echo "<br> query: $sql\n";

// of there is conecction, and the sql statement is true run the if
if($result)
{	// checks to see if there are rows in the table
	if(mysqli_num_rows($result)>0)
	{
		echo "<br>The following staff are in the database.";
		echo "<TABLE border=1>\n";
		echo "<TR><TH>Staffno<TH>first name<TH>last name<TH>salary\n";
	
		// loop to print the rows 
		while($row = mysqli_fetch_array($result))
		{
			// must be the same as sql columbs this is case sensetive
			// if you rename the column in the sql satement you use the renamed variable
			$staffno = $row["staffno"];
			$fname=$row["fname"];
			$lname=$row["lname"];
			$salary=$row["salary"];
			// if fname is empty done display record
			if ($fname <>"")
				echo "<br><TR><TD>$staffno<TD>$fname<TD>$lname<TD>$salary\n";
			

		}

		echo "</TABLE>\n";
		//release everything from memory, this is null
		mysqli_free_result($result);

	}	
	else 
	{
		echo "<br> No record found. \n";
	}
}
else 
{
	echo "<br> Something wrong in the query. $sql\n";
}



// close connection 
mysqli_close($con);














?>